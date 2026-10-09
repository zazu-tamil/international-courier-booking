<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Access control: Ensure user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }

        // Restrict customers from internal staff chat
        if ($this->session->userdata('role_id') == 4) {
            $this->session->set_flashdata('error', 'Access Denied: Internal Chat is for Admin, Branch, and Franchise staff only.');
            redirect('dashboard');
        }

        $this->load->model('Chat_model');
        $this->load->helper(array('download', 'date'));
    }

    /**
     * Main Internal Chat View
     */
    public function index($contact_id = 0) {
        $current_user_id = $this->session->userdata('user_id');
        $current_role_id = $this->session->userdata('role_id');
        $is_superadmin   = ($current_role_id == 1);

        $this->Chat_model->update_heartbeat($current_user_id);

        $data['current_user_id'] = $current_user_id;
        $data['current_role_id'] = $current_role_id;
        $data['is_superadmin']   = $is_superadmin;

        // Check if Super Admin is observing a specific conversation pair (u1 & u2)
        $observe_u1 = intval($this->input->get('u1'));
        $observe_u2 = intval($this->input->get('u2'));
        $is_observing_thread = ($is_superadmin && $observe_u1 > 0 && $observe_u2 > 0);

        $data['is_observing_thread'] = $is_observing_thread;
        $data['observe_u1'] = $observe_u1;
        $data['observe_u2'] = $observe_u2;

        if ($is_observing_thread) {
            $data['active_contact_id'] = 0;
            $data['observe_user1'] = $this->Chat_model->get_contact_info($observe_u1);
            $data['observe_user2'] = $this->Chat_model->get_contact_info($observe_u2);

            if (!$data['observe_user1'] || !$data['observe_user2']) {
                redirect('chat');
            }

            $data['active_contact'] = (object) array(
                'id' => -1,
                'username' => $data['observe_user1']->username . ' ↔ ' . $data['observe_user2']->username,
                'role_name' => 'Live Surveillance Mode',
                'org_badge' => 'Audit & Monitoring',
                'role_color' => 'purple',
                'is_online' => ($data['observe_user1']->is_online || $data['observe_user2']->is_online)
            );
        } else {
            $data['active_contact_id'] = intval($contact_id);

            // If regular staff tries to view a contact that is Super Admin, block it (invisible Super Admin)
            if ($data['active_contact_id'] > 0) {
                $contact_info = $this->Chat_model->get_contact_info($data['active_contact_id']);
                if ($contact_info && $contact_info->role_id == 1 && !$is_superadmin) {
                    redirect('chat');
                }
                $data['active_contact'] = $contact_info;
            } else {
                $data['active_contact'] = (object) array(
                    'id' => 0,
                    'username' => 'Team Announcements & General Discussions',
                    'role_name' => 'Broadcast Channel',
                    'org_badge' => 'All Branches & Franchises',
                    'role_color' => 'success',
                    'is_online' => TRUE
                );
            }
        }

        $data['contacts'] = $this->Chat_model->get_staff_contacts($current_user_id);
        $data['total_unread'] = $this->Chat_model->get_total_unread_count($current_user_id);
        $data['all_conversations'] = $is_superadmin ? $this->Chat_model->get_all_staff_conversations() : array();

        $data['page_title'] = $is_superadmin ? 'Internal Staff Chat & Audit Hub' : 'Internal Staff Chat';
        $data['view_path'] = 'chat/chat_view';
        $this->load->view('templates/dashboard_layout', $data);
    }

    /**
     * AJAX: Get contacts list with live online status and unread badges
     */
    public function ajax_get_contacts() {
        $current_user_id = $this->session->userdata('user_id');
        $current_role_id = $this->session->userdata('role_id');
        $is_superadmin   = ($current_role_id == 1);

        $this->Chat_model->update_heartbeat($current_user_id);

        $contacts = $this->Chat_model->get_staff_contacts($current_user_id);
        $total_unread = $this->Chat_model->get_total_unread_count($current_user_id);
        $all_conversations = $is_superadmin ? $this->Chat_model->get_all_staff_conversations() : array();

        echo json_encode(array(
            'status' => 'success',
            'contacts' => $contacts,
            'all_conversations' => $all_conversations,
            'total_unread' => $total_unread
        ));
        exit;
    }

    /**
     * AJAX: Fetch messages for active conversation or live thread surveillance
     */
    public function ajax_get_messages() {
        $current_user_id = $this->session->userdata('user_id');
        $current_role_id = $this->session->userdata('role_id');
        $is_superadmin   = ($current_role_id == 1);

        $this->Chat_model->update_heartbeat($current_user_id);

        $contact_id = intval($this->input->get('contact_id'));
        $u1 = intval($this->input->get('u1'));
        $u2 = intval($this->input->get('u2'));
        $last_id = intval($this->input->get('last_id'));

        $is_observing = ($is_superadmin && $u1 > 0 && $u2 > 0);

        if ($is_observing) {
            $messages = $this->Chat_model->get_conversation_messages($u1, $u2, $last_id);
        } else {
            // Guard: non-superadmin cannot query Super Admin messages directly
            if ($contact_id > 0 && !$is_superadmin) {
                $chk = $this->Chat_model->get_contact_info($contact_id);
                if ($chk && $chk->role_id == 1) {
                    echo json_encode(array('status' => 'error', 'message' => 'Access denied.'));
                    exit;
                }
            }
            $messages = $this->Chat_model->get_messages($current_user_id, $contact_id, $last_id);
        }

        // Format message fields for client display
        foreach ($messages as &$m) {
            $time = strtotime($m->created_at);
            if (date('Y-m-d', $time) === date('Y-m-d')) {
                $m->formatted_time = date('h:i A', $time);
            } elseif (date('Y-m-d', $time) === date('Y-m-d', strtotime('-1 day'))) {
                $m->formatted_time = 'Yesterday ' . date('h:i A', $time);
            } else {
                $m->formatted_time = date('M d, h:i A', $time);
            }

            if ($is_observing) {
                // In observer mode, differentiate sides by user1 vs user2
                $m->is_observer_mode = TRUE;
                $m->is_mine = ($m->sender_id == $u1);
                $m->is_user1 = ($m->sender_id == $u1);
            } else {
                $m->is_observer_mode = FALSE;
                $m->is_mine = ($m->sender_id == $current_user_id);
            }

            // Format attachment details
            if (!empty($m->attachment)) {
                $m->attachment_url = base_url('assets/chat_attachments/' . $m->attachment);
                $m->download_url = site_url('chat/download/' . $m->id);
                $ext = strtolower(pathinfo($m->attachment_name, PATHINFO_EXTENSION));
                $m->is_image = in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'));
                
                // Format file size
                if ($m->attachment_size > 1048576) {
                    $m->formatted_size = round($m->attachment_size / 1048576, 1) . ' MB';
                } elseif ($m->attachment_size > 1024) {
                    $m->formatted_size = round($m->attachment_size / 1024, 1) . ' KB';
                } else {
                    $m->formatted_size = $m->attachment_size . ' B';
                }
            }
        }

        echo json_encode(array(
            'status' => 'success',
            'is_observing' => $is_observing,
            'messages' => $messages
        ));
        exit;
    }

    /**
     * AJAX: Send message + optional attachment
     */
    public function ajax_send_message() {
        $current_user_id = $this->session->userdata('user_id');
        $current_role_id = $this->session->userdata('role_id');
        $is_superadmin   = ($current_role_id == 1);

        $this->Chat_model->update_heartbeat($current_user_id);

        $receiver_id = intval($this->input->post('receiver_id'));
        $message_text = trim($this->input->post('message', TRUE));

        // FEATURE 2: ONLY Super Admin can post in Team Announcements & General Discussions!
        if ($receiver_id === 0) {
            if (!$is_superadmin) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Permission Denied: Only Head Office / Super Admin can post in Team Announcements.'
                ));
                exit;
            }
        }

        // FEATURE 1: Super Admin is invisible to all; regular staff cannot message Super Admin directly
        if ($receiver_id > 0 && !$is_superadmin) {
            $recipient = $this->Chat_model->get_contact_info($receiver_id);
            if ($recipient && $recipient->role_id == 1) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Super Admin is unavailable for direct messages.'
                ));
                exit;
            }
        }

        $has_attachment = (!empty($_FILES['attachment']['name']));

        if (empty($message_text) && !$has_attachment) {
            echo json_encode(array('status' => 'error', 'message' => 'Cannot send an empty message.'));
            exit;
        }

        $attachment_file = NULL;
        $attachment_orig_name = NULL;
        $attachment_size = NULL;
        $attachment_type = NULL;

        if ($has_attachment) {
            $upload_path = './assets/chat_attachments/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp|pdf|doc|docx|xls|xlsx|csv|zip|txt';
            $config['max_size']      = 15360; // 15MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('attachment')) {
                echo json_encode(array('status' => 'error', 'message' => $this->upload->display_errors('', '')));
                exit;
            }

            $upload_data = $this->upload->data();
            $attachment_file      = $upload_data['file_name'];
            $attachment_orig_name = $upload_data['orig_name'];
            $attachment_size      = $upload_data['file_size'] * 1024;
            $attachment_type      = $upload_data['file_type'];
        }

        $message_data = array(
            'sender_id'       => $current_user_id,
            'receiver_id'     => ($receiver_id > 0) ? $receiver_id : NULL,
            'message'         => $message_text,
            'attachment'      => $attachment_file,
            'attachment_name' => $attachment_orig_name,
            'attachment_size' => $attachment_size,
            'attachment_type' => $attachment_type,
            'is_read'         => 0,
            'created_at'      => date('Y-m-d H:i:s')
        );

        $saved_msg = $this->Chat_model->save_message($message_data);

        if ($saved_msg) {
            $saved_msg->formatted_time = date('h:i A');
            $saved_msg->is_mine = TRUE;
            if (!empty($saved_msg->attachment)) {
                $saved_msg->attachment_url = base_url('assets/chat_attachments/' . $saved_msg->attachment);
                $saved_msg->download_url = site_url('chat/download/' . $saved_msg->id);
                $ext = strtolower(pathinfo($saved_msg->attachment_name, PATHINFO_EXTENSION));
                $saved_msg->is_image = in_array($ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'));
                $saved_msg->formatted_size = round($saved_msg->attachment_size / 1024, 1) . ' KB';
            }

            echo json_encode(array(
                'status'  => 'success',
                'message' => $saved_msg
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to save message.'));
        }
        exit;
    }

    /**
     * AJAX: Global lightweight unread counter for top navbar and sidebar badge
     */
    public function ajax_unread_count() {
        $current_user_id = $this->session->userdata('user_id');
        $this->Chat_model->update_heartbeat($current_user_id);

        $total_unread = $this->Chat_model->get_total_unread_count($current_user_id);

        // Fetch recent unread message senders for dropdown preview
        $recent_unread = $this->Chat_model->get_recent_unread($current_user_id, 5);

        foreach ($recent_unread as &$ru) {
            $ru->time_ago = timespan(strtotime($ru->created_at), time(), 1) . ' ago';
            $ru->snippet = !empty($ru->message) ? substr($ru->message, 0, 45) . (strlen($ru->message) > 45 ? '...' : '') : '📎 Attachment';
        }

        echo json_encode(array(
            'status'        => 'success',
            'total_unread'  => $total_unread,
            'recent_unread' => $recent_unread
        ));
        exit;
    }

    /**
     * Secure Attachment Download
     */
    public function download_attachment($message_id) {
        $current_user_id = $this->session->userdata('user_id');
        $current_role_id = $this->session->userdata('role_id');
        $message = $this->Chat_model->get_message_by_id($message_id);

        if (!$message || empty($message->attachment)) {
            show_404();
        }

        // Allow if broadcast OR Super Admin OR participant
        if ($message->receiver_id !== NULL && $current_role_id != 1 && $message->sender_id != $current_user_id && $message->receiver_id != $current_user_id) {
            show_error('Unauthorized access to file attachment.', 403);
        }

        $file_path = './assets/chat_attachments/' . $message->attachment;
        if (!file_exists($file_path)) {
            show_404();
        }

        force_download($message->attachment_name, file_get_contents($file_path));
    }
}
