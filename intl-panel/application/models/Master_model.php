<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    // --- CHARGE TYPES ---
    public function get_active_charge_types() {
        $this->db->where('status', 'Active');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('master_charge_types')->result();
    }

    // --- BRANCHES ---
    public function get_branches($id = NULL) {
        $this->db->select('*');
        $this->db->from('branches');
        $this->db->where('deleted_at IS NULL');
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function add_branch($data) {
        $this->db->insert('branches', $data);
        $id = $this->db->insert_id();
        $this->Audit_model->log_activity('Add Branch', 'Branch Code: ' . $data['branch_code']);
        return $id;
    }

    public function update_branch($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('branches', $data);
        $this->Audit_model->log_activity('Update Branch', 'Branch ID: ' . $id);
        return $result;
    }

    public function delete_branch($id) {
        $this->db->where('id', $id);
        $result = $this->db->update('branches', array('deleted_at' => date('Y-m-d H:i:s'), 'status' => 'Inactive'));
        $this->Audit_model->log_activity('Delete Branch', 'Branch ID: ' . $id);
        return $result;
    }

    public function add_branch_user($user_data) {
        $user_data['password'] = password_hash($user_data['password'], PASSWORD_BCRYPT);
        $user_data['status'] = 'Active';
        $user_data['created_at'] = date('Y-m-d H:i:s');
        if (!$this->db->insert('users', $user_data)) {
            log_message('error', 'Add Branch User failed: ' . print_r($this->db->error(), true));
            return false;
        }
        $id = $this->db->insert_id();
        $this->Audit_model->log_activity('Add Branch User', 'User: ' . $user_data['username'] . ' for Branch ID: ' . $user_data['branch_id']);
        return $id;
    }

    public function get_branch_users($branch_id) {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.branch_id', $branch_id);
        $this->db->where('users.deleted_at IS NULL');
        return $this->db->get()->result();
    }

    public function get_user($id) {
        $this->db->where('id', $id);
        $this->db->where('deleted_at IS NULL');
        return $this->db->get('users')->row();
    }

    public function update_user($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']); // do not update if empty
        }
        $this->db->where('id', $id);
        $result = $this->db->update('users', $data);
        $this->Audit_model->log_activity('Update User', 'User ID: ' . $id);
        return $result;
    }

    public function delete_user($id) {
        $this->db->where('id', $id);
        $result = $this->db->update('users', array('deleted_at' => date('Y-m-d H:i:s'), 'status' => 'Inactive'));
        $this->Audit_model->log_activity('Delete User', 'User ID: ' . $id);
        return $result;
    }

    // --- FRANCHISES ---
    public function get_franchises($id = NULL) {
        $this->db->select('franchises.*, users.email as user_email');
        $this->db->from('franchises');
        $this->db->join('users', 'users.id = franchises.user_id', 'left');
        $this->db->where('franchises.deleted_at IS NULL');
        if ($id) {
            $this->db->where('franchises.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function add_franchise($data, $user_data) {
        $this->db->trans_start();

        // Create user for franchise
        $user_data['password'] = password_hash($user_data['password'], PASSWORD_BCRYPT);
        $user_data['role_id'] = 3; // Franchise User
        $user_data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        // Create franchise record
        $data['user_id'] = $user_id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('franchises', $data);
        $franchise_id = $this->db->insert_id();

        // Update user to link franchise_id
        $this->db->where('id', $user_id);
        $this->db->update('users', array('franchise_id' => $franchise_id));

        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        $this->Audit_model->log_activity('Add Franchise', 'Franchise Code: ' . $data['franchise_code']);
        return $franchise_id;
    }

    public function update_franchise($id, $data, $user_data = array()) {
        $this->db->trans_start();
        
        $this->db->where('id', $id);
        $this->db->update('franchises', $data);

        if (!empty($user_data)) {
            if (isset($user_data['password']) && !empty($user_data['password'])) {
                $user_data['password'] = password_hash($user_data['password'], PASSWORD_BCRYPT);
            } else {
                unset($user_data['password']);
            }
            $franchise = $this->get_franchises($id);
            if ($franchise && $franchise->user_id) {
                $this->db->where('id', $franchise->user_id);
                $this->db->update('users', $user_data);
            }
        }

        $this->db->trans_complete();

        $this->Audit_model->log_activity('Update Franchise', 'Franchise ID: ' . $id);
        return $this->db->trans_status();
    }

    public function delete_franchise($id) {
        $franchise = $this->get_franchises($id);
        if ($franchise) {
            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->update('franchises', array('deleted_at' => date('Y-m-d H:i:s'), 'status' => 'Inactive'));
            
            if ($franchise->user_id) {
                $this->db->where('id', $franchise->user_id);
                $this->db->update('users', array('deleted_at' => date('Y-m-d H:i:s'), 'status' => 'Inactive'));
            }
            $this->db->trans_complete();
            
            $this->Audit_model->log_activity('Delete Franchise', 'Franchise ID: ' . $id);
            return $this->db->trans_status();
        }
        return FALSE;
    }

    // --- COUNTRIES ---
    public function get_countries($id = NULL) {
        if ($id) {
            return $this->db->get_where('countries', array('id' => $id))->row();
        }
        return $this->db->get('countries')->result();
    }

    public function add_country($data) {
        $result = $this->db->insert('countries', $data);
        $this->Audit_model->log_activity('Add Country', 'Country: ' . $data['country_name']);
        return $result;
    }

    public function update_country($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('countries', $data);
        $this->Audit_model->log_activity('Update Country', 'Country ID: ' . $id);
        return $result;
    }

    // --- COURIER PARTNERS ---
    public function get_courier_partners($id = NULL) {
        if ($id) {
            return $this->db->get_where('courier_partners', array('id' => $id))->row();
        }
        return $this->db->get('courier_partners')->result();
    }

    public function add_courier_partner($data) {
        $result = $this->db->insert('courier_partners', $data);
        $this->Audit_model->log_activity('Add Courier Partner', 'Partner: ' . $data['partner_name']);
        return $result;
    }

    public function update_courier_partner($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('courier_partners', $data);
        $this->Audit_model->log_activity('Update Courier Partner', 'Partner ID: ' . $id);
        return $result;
    }

    // --- RATES MATRIX ---
    public function get_rates($id = NULL) {
        $this->db->select('rate_master.*, o.country_name as origin_country, d.country_name as destination_country');
        $this->db->from('rate_master');
        $this->db->join('countries o', 'o.id = rate_master.origin_country_id');
        $this->db->join('countries d', 'd.id = rate_master.destination_country_id');
        if ($id) {
            $this->db->where('rate_master.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function add_rate($data) {
        $result = $this->db->insert('rate_master', $data);
        $this->Audit_model->log_activity('Add Shipping Rate', 'From Country: ' . $data['origin_country_id'] . ' To: ' . $data['destination_country_id']);
        return $result;
    }

    public function update_rate($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('rate_master', $data);
        $this->Audit_model->log_activity('Update Shipping Rate', 'Rate ID: ' . $id);
        return $result;
    }

    public function delete_rate($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete('rate_master');
        $this->Audit_model->log_activity('Delete Shipping Rate', 'Rate ID: ' . $id);
        return $result;
    }

    public function calculate_shipping_charges($origin_id, $dest_id, $service_type, $chargeable_weight) {
        $this->db->select('*');
        $this->db->from('rate_master');
        $this->db->where('origin_country_id', $origin_id);
        $this->db->where('destination_country_id', $dest_id);
        $this->db->where('service_type', $service_type);
        $this->db->where('weight_slab_start <=', $chargeable_weight);
        $this->db->order_by('weight_slab_end', 'ASC');
        $query = $this->db->get();
        
        $matched_rate = NULL;
        foreach ($query->result() as $rate) {
            if ($chargeable_weight <= $rate->weight_slab_end) {
                $matched_rate = $rate;
                break;
            }
        }
        
        // If no slab matches, take the largest slab available
        if (!$matched_rate && $query->num_rows() > 0) {
            $matched_rate = $query->row($query->num_rows() - 1);
        }

        if ($matched_rate) {
            $base_rate = $matched_rate->base_rate;
            $fuel_percent = $matched_rate->fuel_surcharge;
            $fuel_surcharge = ($base_rate * $fuel_percent) / 100;
            $handling = $matched_rate->handling_charges;
            $insurance = $matched_rate->insurance_charges;
            $total = $base_rate + $fuel_surcharge + $handling + $insurance;

            return array(
                'base_rate' => $base_rate,
                'fuel_surcharge' => $fuel_surcharge,
                'fuel_percentage' => $fuel_percent,
                'handling_charges' => $handling,
                'insurance_charges' => $insurance,
                'total_charges' => $total
            );
        }
        return FALSE;
    }

    // --- TERMS & CONDITIONS ---
    public function get_terms($id = NULL) {
        if ($id) {
            return $this->db->get_where('terms_conditions_master', array('id' => $id))->row();
        }
        return $this->db->get('terms_conditions_master')->result();
    }

    public function get_active_terms() {
        return $this->db->get_where('terms_conditions_master', array('status' => 'Published'))->row();
    }

    public function add_terms($data) {
        if ($data['status'] == 'Published') {
            // Unpublish other versions
            $this->db->update('terms_conditions_master', array('status' => 'Archived'), array('status' => 'Published'));
        }
        $result = $this->db->insert('terms_conditions_master', $data);
        $this->Audit_model->log_activity('Add Terms version', 'Version: ' . $data['version_number']);
        return $result;
    }

    public function update_terms($id, $data) {
        if (isset($data['status']) && $data['status'] == 'Published') {
            // Unpublish other versions
            $this->db->where('id !=', $id);
            $this->db->update('terms_conditions_master', array('status' => 'Archived'), array('status' => 'Published'));
        }
        $this->db->where('id', $id);
        $result = $this->db->update('terms_conditions_master', $data);
        $this->Audit_model->log_activity('Update Terms version', 'Terms ID: ' . $id);
        return $result;
    }

    // --- APP SETTINGS ---
    public function get_app_settings() {
        $this->db->select('*');
        $this->db->from('app_settings');
        $query = $this->db->get();
        $settings = array();
        foreach ($query->result() as $row) {
            $settings[$row->key] = $row->value;
        }
        return $settings;
    }

    public function update_app_settings($data) {
        $this->db->trans_start();
        foreach ($data as $key => $value) {
            $this->db->where('key', $key);
            $query = $this->db->get('app_settings');
            if ($query->num_rows() > 0) {
                $this->db->where('key', $key);
                $this->db->update('app_settings', array('value' => $value));
            } else {
                $this->db->insert('app_settings', array('key' => $key, 'value' => $value));
            }
        }
        $this->db->trans_complete();
        $this->Audit_model->log_activity('Update App Settings', 'Updated app settings keys');
        return $this->db->trans_status();
    }

    // --- ROLES & PERMISSIONS ---
    public function get_roles($id = NULL) {
        if ($id) {
            return $this->db->get_where('roles', array('id' => $id))->row();
        }
        return $this->db->get('roles')->result();
    }

    public function get_permissions() {
        return $this->db->get('permissions')->result();
    }

    public function get_role_permissions($role_id) {
        $this->db->select('permission_id');
        $this->db->from('role_permissions');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get()->result();
        
        $permissions = array();
        foreach ($query as $row) {
            $permissions[] = $row->permission_id;
        }
        return $permissions;
    }

    public function update_role_permissions($role_id, $permission_ids = array()) {
        $this->db->trans_start();
        
        // Remove existing mapping
        $this->db->where('role_id', $role_id);
        $this->db->delete('role_permissions');
        
        // Insert new mapping
        if (!empty($permission_ids)) {
            foreach ($permission_ids as $perm_id) {
                $this->db->insert('role_permissions', array(
                    'role_id' => $role_id,
                    'permission_id' => $perm_id
                ));
            }
        }
        
        $this->db->trans_complete();
        $this->Audit_model->log_activity('Update Role Permissions', 'Role ID: ' . $role_id);
        return $this->db->trans_status();
    }

    public function add_role($data) {
        $this->db->insert('roles', $data);
        $id = $this->db->insert_id();
        $this->Audit_model->log_activity('Add Role', 'Role: ' . $data['name']);
        return $id;
    }

    public function update_role($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('roles', $data);
        $this->Audit_model->log_activity('Update Role', 'Role ID: ' . $id);
        return $result;
    }

    public function delete_role($id) {
        $this->db->trans_start();
        $this->db->where('role_id', $id);
        $this->db->delete('role_permissions');
        
        $this->db->where('id', $id);
        $result = $this->db->delete('roles');
        
        $this->db->trans_complete();
        $this->Audit_model->log_activity('Delete Role', 'Role ID: ' . $id);
        return $result;
    }

    // --- MOVEMENT STAGES ---
    public function get_movement_stages($id = NULL) {
        $this->db->order_by('id', 'ASC');
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get('movement_stages')->row();
        }
        return $this->db->get('movement_stages')->result();
    }

    public function add_movement_stage($data) {
        $this->db->insert('movement_stages', $data);
        $this->Audit_model->log_activity('Add Movement Stage', 'Stage Name: ' . $data['stage_name']);
        return $this->db->insert_id();
    }

    public function update_movement_stage($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('movement_stages', $data);
        $this->Audit_model->log_activity('Update Movement Stage', 'Stage ID: ' . $id);
        return $result;
    }

    public function delete_movement_stage($id) {
        $stage = $this->get_movement_stages($id);
        $this->db->where('id', $id);
        $result = $this->db->delete('movement_stages');
        if ($stage) {
            $this->Audit_model->log_activity('Delete Movement Stage', 'Stage Name: ' . $stage->stage_name);
        }
        return $result;
    }

    // --- SERVICE TYPES ---
    public function get_service_types($id = NULL) {
        $this->db->select('*');
        $this->db->from('service_types');
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function add_service_type($data) {
        $result = $this->db->insert('service_types', $data);
        $this->Audit_model->log_activity('Add Service Type', 'Service Name: ' . $data['service_name']);
        return $result;
    }

    public function update_service_type($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('service_types', $data);
        $this->Audit_model->log_activity('Update Service Type', 'Service ID: ' . $id);
        return $result;
    }

    public function delete_service_type($id) {
        $service = $this->get_service_types($id);
        $this->db->where('id', $id);
        $result = $this->db->delete('service_types');
        if ($service) {
            $this->Audit_model->log_activity('Delete Service Type', 'Service Name: ' . $service->service_name);
        }
        return $result;
    }

    // --- DOCUMENT TYPES ---
    public function get_document_types($id = NULL) {
        $this->db->select('*');
        $this->db->from('document_types');
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function add_document_type($data) {
        $result = $this->db->insert('document_types', $data);
        $this->Audit_model->log_activity('Add Document Type', 'Document Name: ' . $data['doc_type_name']);
        return $result;
    }

    public function update_document_type($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('document_types', $data);
        $this->Audit_model->log_activity('Update Document Type', 'Document ID: ' . $id);
        return $result;
    }

    public function delete_document_type($id) {
        $doc = $this->get_document_types($id);
        $this->db->where('id', $id);
        $result = $this->db->delete('document_types');
        if ($doc) {
            $this->Audit_model->log_activity('Delete Document Type', 'Document Name: ' . $doc->doc_type_name);
        }
        return $result;
    }
}
