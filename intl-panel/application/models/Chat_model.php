<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_model extends CI_Model {

    private $has_last_active = FALSE;
    private $has_chat_table = FALSE;

    public function __construct() {
        parent::__construct();
        $this->ensure_schema();
    }

    /**
     * Auto-heal database schema:
     * 1. Creates `internal_chat_messages` table if missing.
     * 2. Adds `last_active` column to `users` table if missing.
     */
    public function ensure_schema() {
        // 1. Check & ensure internal_chat_messages table exists
        $table_check = $this->db->query("SHOW TABLES LIKE 'internal_chat_messages'");
        if ($table_check && $table_check->num_rows() > 0) {
            $this->has_chat_table = TRUE;
        } else {
            // Attempt auto-create table
            $create_sql = "CREATE TABLE IF NOT EXISTS `internal_chat_messages` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sender_id` INT(11) NOT NULL,
                `receiver_id` INT(11) DEFAULT NULL,
                `message` TEXT DEFAULT NULL,
                `attachment` VARCHAR(255) DEFAULT NULL,
                `attachment_name` VARCHAR(255) DEFAULT NULL,
                `attachment_size` INT(11) DEFAULT NULL,
                `attachment_type` VARCHAR(50) DEFAULT NULL,
                `is_read` TINYINT(1) NOT NULL DEFAULT 0,
                `created_at` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                KEY `idx_sender_receiver` (`sender_id`, `receiver_id`),
                KEY `idx_receiver_read` (`receiver_id`, `is_read`),
                KEY `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            $this->db->query($create_sql);
            $recheck = $this->db->query("SHOW TABLES LIKE 'internal_chat_messages'");
            $this->has_chat_table = ($recheck && $recheck->num_rows() > 0);
        }

        // 2. Check & ensure last_active column exists in users table
        $col_check = $this->db->query("SHOW COLUMNS FROM `users` LIKE 'last_active'");
        if ($col_check && $col_check->num_rows() > 0) {
            $this->has_last_active = TRUE;
        } else {
            // Attempt auto-alter table
            $this->db->query("ALTER TABLE `users` ADD COLUMN `last_active` DATETIME NULL AFTER `last_login`");
            $recheck = $this->db->query("SHOW COLUMNS FROM `users` LIKE 'last_active'");
            $this->has_last_active = ($recheck && $recheck->num_rows() > 0);
        }
    }

    /**
     * Update current user's last_active timestamp
     */
    public function update_heartbeat($user_id) {
        if ($this->has_last_active) {
            $this->db->where('id', $user_id);
            $this->db->update('users', array('last_active' => date('Y-m-d H:i:s')));
        }
    }

    /**
     * Get staff contacts (Super Admin, Branch Admin, Franchise User)
     * excluding current user, along with unread counts, latest message, and online status.
     */
    public function get_staff_contacts($current_user_id) {
        $active_field = $this->has_last_active ? 'u.last_active' : 'u.last_login as last_active';

        // Fetch all staff users (roles 1, 2, 3)
        $this->db->select("u.id, u.username, u.email, u.role_id, r.name as role_name, u.branch_id, b.name as branch_name, b.branch_code, u.franchise_id, f.name as franchise_name, f.franchise_code, {$active_field}");
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->join('branches b', 'b.id = u.branch_id', 'left');
        $this->db->join('franchises f', 'f.id = u.franchise_id', 'left');
        $this->db->where_in('u.role_id', array(1, 2, 3));
        $this->db->where('u.id !=', $current_user_id);
        $this->db->where('u.status', 'Active');
        $this->db->where('u.deleted_at IS NULL', NULL, FALSE);
        
        $query = $this->db->get();
        if (!$query) {
            return array();
        }

        $contacts = $query->result();

        // Enrich with unread counts and latest messages
        foreach ($contacts as &$c) {
            $c->unread_count = 0;
            $c->last_message = 'No messages yet';
            $c->last_message_time = NULL;
            $c->last_message_sender_id = NULL;

            if ($this->has_chat_table) {
                // Unread messages from this contact to current user
                $this->db->where('sender_id', $c->id);
                $this->db->where('receiver_id', $current_user_id);
                $this->db->where('is_read', 0);
                $c->unread_count = $this->db->count_all_results('internal_chat_messages');

                // Latest direct message between current user and this contact
                $this->db->select('message, attachment, created_at, sender_id');
                $this->db->from('internal_chat_messages');
                $this->db->group_start();
                    $this->db->group_start();
                        $this->db->where('sender_id', $current_user_id);
                        $this->db->where('receiver_id', $c->id);
                    $this->db->group_end();
                    $this->db->or_group_start();
                        $this->db->where('sender_id', $c->id);
                        $this->db->where('receiver_id', $current_user_id);
                    $this->db->group_end();
                $this->db->group_end();
                $this->db->order_by('id', 'DESC');
                $this->db->limit(1);
                $last_query = $this->db->get();
                $last_msg = $last_query ? $last_query->row() : NULL;

                if ($last_msg) {
                    $c->last_message = ($last_msg->attachment && empty($last_msg->message)) ? '📎 Attachment' : $last_msg->message;
                    $c->last_message_time = $last_msg->created_at;
                    $c->last_message_sender_id = $last_msg->sender_id;
                }
            }

            // Online status (active within last 2 minutes)
            $is_online = FALSE;
            if (!empty($c->last_active)) {
                $last_active_time = strtotime($c->last_active);
                if (time() - $last_active_time <= 120) {
                    $is_online = TRUE;
                }
            }
            $c->is_online = $is_online;

            // Display title / organization badge
            if ($c->role_id == 1) {
                $c->org_badge = 'Head Office';
                $c->role_color = 'danger';
            } elseif ($c->role_id == 2) {
                $c->org_badge = !empty($c->branch_name) ? $c->branch_name : 'Branch';
                $c->role_color = 'primary';
            } else {
                $c->org_badge = !empty($c->franchise_name) ? $c->franchise_name : 'Franchise';
                $c->role_color = 'warning';
            }
        }

        // Sort contacts: unread first, then by latest message time descending, then by username
        usort($contacts, function($a, $b) {
            if ($a->unread_count != $b->unread_count) {
                return $b->unread_count - $a->unread_count;
            }
            if ($a->last_message_time && $b->last_message_time) {
                return strtotime($b->last_message_time) - strtotime($a->last_message_time);
            }
            if ($a->last_message_time) return -1;
            if ($b->last_message_time) return 1;
            return strcmp($a->username, $b->username);
        });

        return $contacts;
    }

    /**
     * Get specific contact info
     */
    public function get_contact_info($contact_id) {
        $active_field = $this->has_last_active ? 'u.last_active' : 'u.last_login as last_active';

        $this->db->select("u.id, u.username, u.email, u.role_id, r.name as role_name, u.branch_id, b.name as branch_name, b.branch_code, b.mobile as branch_mobile, u.franchise_id, f.name as franchise_name, f.franchise_code, {$active_field}");
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->join('branches b', 'b.id = u.branch_id', 'left');
        $this->db->join('franchises f', 'f.id = u.franchise_id', 'left');
        $this->db->where('u.id', $contact_id);
        
        $query = $this->db->get();
        if (!$query) {
            return NULL;
        }

        $contact = $query->row();

        if ($contact) {
            $is_online = FALSE;
            if (!empty($contact->last_active)) {
                if (time() - strtotime($contact->last_active) <= 120) {
                    $is_online = TRUE;
                }
            }
            $contact->is_online = $is_online;

            if ($contact->role_id == 1) {
                $contact->org_badge = 'Head Office';
                $contact->role_color = 'danger';
            } elseif ($contact->role_id == 2) {
                $contact->org_badge = !empty($contact->branch_name) ? $contact->branch_name : 'Branch';
                $contact->role_color = 'primary';
            } else {
                $contact->org_badge = !empty($contact->franchise_name) ? $contact->franchise_name : 'Franchise';
                $contact->role_color = 'warning';
            }
        }

        return $contact;
    }

    /**
     * Fetch conversation messages
     * If $contact_id == 0: fetch broadcast/announcement messages (receiver_id IS NULL)
     * Else: fetch direct messages between current_user and contact_id
     */
    public function get_messages($current_user_id, $contact_id, $last_id = 0) {
        if (!$this->has_chat_table) {
            return array();
        }

        $this->db->select('m.*, u.username as sender_name, u.role_id as sender_role_id, r.name as sender_role_name, b.name as sender_branch_name, f.name as sender_franchise_name');
        $this->db->from('internal_chat_messages m');
        $this->db->join('users u', 'u.id = m.sender_id');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->join('branches b', 'b.id = u.branch_id', 'left');
        $this->db->join('franchises f', 'f.id = u.franchise_id', 'left');

        if ($contact_id == 0) {
            // General Broadcast channel
            $this->db->where('m.receiver_id IS NULL', NULL, FALSE);
        } else {
            // Direct 1-on-1 message
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->where('m.sender_id', $current_user_id);
                    $this->db->where('m.receiver_id', $contact_id);
                $this->db->group_end();
                $this->db->or_group_start();
                    $this->db->where('m.sender_id', $contact_id);
                    $this->db->where('m.receiver_id', $current_user_id);
                $this->db->group_end();
            $this->db->group_end();
        }

        if ($last_id > 0) {
            $this->db->where('m.id >', $last_id);
        }

        $this->db->order_by('m.id', 'ASC');
        
        // Limit initial load to last 150 messages if not incremental
        if ($last_id == 0) {
            $this->db->limit(150);
        }

        $query = $this->db->get();
        if (!$query) {
            return array();
        }

        $messages = $query->result();

        // Mark incoming messages as read
        if ($contact_id > 0) {
            $this->mark_as_read($contact_id, $current_user_id);
        }

        return $messages;
    }

    /**
     * Save new chat message
     */
    public function save_message($data) {
        if (!$this->has_chat_table) {
            return FALSE;
        }

        $this->db->insert('internal_chat_messages', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            // Fetch newly created message with sender info
            $this->db->select('m.*, u.username as sender_name, u.role_id as sender_role_id, r.name as sender_role_name, b.name as sender_branch_name, f.name as sender_franchise_name');
            $this->db->from('internal_chat_messages m');
            $this->db->join('users u', 'u.id = m.sender_id');
            $this->db->join('roles r', 'r.id = u.role_id');
            $this->db->join('branches b', 'b.id = u.branch_id', 'left');
            $this->db->join('franchises f', 'f.id = u.franchise_id', 'left');
            $this->db->where('m.id', $insert_id);
            $query = $this->db->get();
            return $query ? $query->row() : FALSE;
        }

        return FALSE;
    }

    /**
     * Mark messages from a contact as read
     */
    public function mark_as_read($sender_id, $receiver_id) {
        if (!$this->has_chat_table) {
            return FALSE;
        }

        $this->db->where('sender_id', $sender_id);
        $this->db->where('receiver_id', $receiver_id);
        $this->db->where('is_read', 0);
        return $this->db->update('internal_chat_messages', array('is_read' => 1));
    }

    /**
     * Get total unread messages for current user
     */
    public function get_total_unread_count($user_id) {
        if (!$this->has_chat_table) {
            return 0;
        }

        $this->db->where('receiver_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results('internal_chat_messages');
    }

    /**
     * Get single message by ID
     */
    public function get_message_by_id($message_id) {
        if (!$this->has_chat_table) {
            return NULL;
        }

        $this->db->where('id', $message_id);
        $query = $this->db->get('internal_chat_messages');
        return $query ? $query->row() : NULL;
    }

    /**
     * Get recent unread messages with sender info (for dropdown preview)
     */
    public function get_recent_unread($user_id, $limit = 5) {
        if (!$this->has_chat_table) {
            return array();
        }

        $this->db->select('m.id, m.sender_id, m.message, m.attachment, m.created_at, u.username as sender_name, r.name as sender_role');
        $this->db->from('internal_chat_messages m');
        $this->db->join('users u', 'u.id = m.sender_id');
        $this->db->join('roles r', 'r.id = u.role_id');
        $this->db->where('m.receiver_id', $user_id);
        $this->db->where('m.is_read', 0);
        $this->db->order_by('m.id', 'DESC');
        $this->db->limit($limit);

        $query = $this->db->get();
        return $query ? $query->result() : array();
    }
}
