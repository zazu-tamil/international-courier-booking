<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    // --- CHARGE TYPES ---
    public function get_charge_types($id = NULL) {
        if ($id) {
            return $this->db->get_where('master_charge_types', array('id' => $id))->row();
        }
        $this->db->order_by('id', 'ASC');
        return $this->db->get('master_charge_types')->result();
    }

    public function get_active_charge_types() {
        $this->db->where('status', 'Active');
        $this->db->order_by('id', 'ASC');
        return $this->db->get('master_charge_types')->result();
    }

    public function add_charge_type($data) {
        return $this->db->insert('master_charge_types', $data);
    }

    public function update_charge_type($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('master_charge_types', $data);
    }

    public function delete_charge_type($id) {
        $this->db->where('id', $id);
        return $this->db->delete('master_charge_types');
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

    // =========================================================================
    // --- SHIPPING RATES MATRIX V2 ---
    // Fields: Destination Country, Service Type, Shipment Type, Courier Partner, Weight, Rate, Status
    // =========================================================================

    public function ensure_rates_v2_schema() {
        static $checked = FALSE;
        if ($checked) return;
        $checked = TRUE;

        $table_check = $this->db->query("SHOW TABLES LIKE 'shipping_rates_v2'");
        if (!$table_check || $table_check->num_rows() == 0) {
            $create_sql = "CREATE TABLE IF NOT EXISTS `shipping_rates_v2` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `destination_country_id` INT(11) NOT NULL,
              `service_type` VARCHAR(50) NOT NULL,
              `shipment_type` VARCHAR(100) NOT NULL,
              `courier_partner_id` INT(11) NOT NULL,
              `weight` DECIMAL(8,3) NOT NULL,
              `rate` DECIMAL(12,2) NOT NULL,
              `status` ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
              `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `deleted_at` DATETIME NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_dest_country` (`destination_country_id`),
              KEY `idx_partner` (`courier_partner_id`),
              KEY `idx_service` (`service_type`),
              KEY `idx_shipment_type` (`shipment_type`),
              KEY `idx_status` (`status`),
              KEY `idx_deleted_at` (`deleted_at`),
              KEY `idx_composite_lookup` (`destination_country_id`, `courier_partner_id`, `service_type`, `shipment_type`, `weight`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($create_sql);

            // Seed initial records if empty
            $c_res = $this->db->query("SELECT id, country_name FROM countries WHERE country_name IN ('United States', 'United Kingdom', 'United Arab Emirates', 'Canada', 'Australia')");
            $countries = array();
            if ($c_res && $c_res->num_rows() > 0) {
                foreach ($c_res->result() as $c) {
                    $countries[$c->country_name] = $c->id;
                }
            }
            $p_res = $this->db->query("SELECT id, partner_name FROM courier_partners LIMIT 4");
            $partners = array();
            if ($p_res && $p_res->num_rows() > 0) {
                foreach ($p_res->result() as $p) {
                    $partners[$p->partner_name] = $p->id;
                }
            }
            if (!empty($countries) && !empty($partners)) {
                $sample_data = array(
                    array('United States', 'Express', 'Documents (Paper / Files)', 'DHL Express', 0.500, 1450.00),
                    array('United States', 'Express', 'Documents (Paper / Files)', 'DHL Express', 1.000, 2150.00),
                    array('United States', 'Express', 'Non-Documents (Commercial Goods / Parcels)', 'DHL Express', 0.500, 1850.00),
                    array('United States', 'Express', 'Non-Documents (Commercial Goods / Parcels)', 'DHL Express', 1.000, 2650.00),
                    array('United States', 'Economy', 'Non-Documents (Commercial Goods / Parcels)', 'FedEx', 1.000, 2200.00),
                    array('United Kingdom', 'Express', 'Documents (Paper / Files)', 'DHL Express', 0.500, 1350.00),
                    array('United Kingdom', 'Express', 'Documents (Paper / Files)', 'DHL Express', 1.000, 1950.00),
                    array('United Arab Emirates', 'Express', 'Documents (Paper / Files)', 'Aramex', 0.500, 950.00),
                    array('United Arab Emirates', 'Express', 'Non-Documents (Commercial Goods / Parcels)', 'Aramex', 1.000, 1750.00),
                );
                foreach ($sample_data as $item) {
                    $c_id = isset($countries[$item[0]]) ? $countries[$item[0]] : reset($countries);
                    $p_id = isset($partners[$item[3]]) ? $partners[$item[3]] : reset($partners);
                    $this->db->insert('shipping_rates_v2', array(
                        'destination_country_id' => $c_id,
                        'service_type'           => $item[1],
                        'shipment_type'          => $item[2],
                        'courier_partner_id'     => $p_id,
                        'weight'                 => $item[4],
                        'rate'                   => $item[5],
                        'status'                 => 'Active'
                    ));
                }
            }
        } else {
            // Table exists: verify deleted_at column exists
            $col_check = $this->db->query("SHOW COLUMNS FROM `shipping_rates_v2` LIKE 'deleted_at'");
            if (!$col_check || $col_check->num_rows() == 0) {
                $this->db->query("ALTER TABLE `shipping_rates_v2` ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `updated_at`, ADD KEY `idx_deleted_at` (`deleted_at`)");
            }
        }
    }

    public function get_rates_v2($filters = array(), $id = NULL) {
        $this->ensure_rates_v2_schema();

        $this->db->select('r.*, c.country_name as destination_country, c.country_code as destination_country_code, p.partner_name as courier_partner_name');
        $this->db->from('shipping_rates_v2 r');
        $this->db->join('countries c', 'c.id = r.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = r.courier_partner_id', 'left');
        $this->db->where('r.deleted_at IS NULL');

        if ($id) {
            $this->db->where('r.id', $id);
            $query = $this->db->get();
            return ($query && $query->num_rows() > 0) ? $query->row() : NULL;
        }

        if (!empty($filters['destination_country_id'])) {
            $this->db->where('r.destination_country_id', $filters['destination_country_id']);
        }
        if (!empty($filters['courier_partner_id'])) {
            $this->db->where('r.courier_partner_id', $filters['courier_partner_id']);
        }
        if (!empty($filters['service_type'])) {
            $this->db->where('r.service_type', $filters['service_type']);
        }
        if (!empty($filters['shipment_type'])) {
            $this->db->where('r.shipment_type', $filters['shipment_type']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('r.status', $filters['status']);
        }

        $this->db->order_by('c.country_name', 'ASC');
        $this->db->order_by('p.partner_name', 'ASC');
        $this->db->order_by('r.service_type', 'ASC');
        $this->db->order_by('r.weight', 'ASC');

        $query = $this->db->get();
        return ($query) ? $query->result() : array();
    }

    public function get_rate_v2($id) {
        return $this->get_rates_v2(array(), $id);
    }

    public function add_rate_v2($data) {
        $this->ensure_rates_v2_schema();
        $insert_data = array(
            'destination_country_id' => intval($data['destination_country_id']),
            'service_type'           => trim($data['service_type']),
            'shipment_type'          => trim($data['shipment_type']),
            'courier_partner_id'     => intval($data['courier_partner_id']),
            'weight'                 => floatval($data['weight']),
            'rate'                   => floatval($data['rate']),
            'status'                 => (!empty($data['status']) && in_array($data['status'], array('Active', 'Inactive'))) ? $data['status'] : 'Active',
            'created_at'             => date('Y-m-d H:i:s'),
            'updated_at'             => date('Y-m-d H:i:s')
        );

        $result = $this->db->insert('shipping_rates_v2', $insert_data);
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Add Shipping Rate v2', 'Country ID: ' . $insert_data['destination_country_id'] . ', Partner: ' . $insert_data['courier_partner_id']);
        }
        return $result;
    }

    public function update_rate_v2($id, $data) {
        $this->ensure_rates_v2_schema();
        $update_data = array(
            'destination_country_id' => intval($data['destination_country_id']),
            'service_type'           => trim($data['service_type']),
            'shipment_type'          => trim($data['shipment_type']),
            'courier_partner_id'     => intval($data['courier_partner_id']),
            'weight'                 => floatval($data['weight']),
            'rate'                   => floatval($data['rate']),
            'status'                 => (!empty($data['status']) && in_array($data['status'], array('Active', 'Inactive'))) ? $data['status'] : 'Active',
            'updated_at'             => date('Y-m-d H:i:s')
        );

        $this->db->where('id', $id);
        $result = $this->db->update('shipping_rates_v2', $update_data);
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Update Shipping Rate v2', 'Rate ID: ' . $id);
        }
        return $result;
    }

    public function delete_rate_v2($id) {
        $this->ensure_rates_v2_schema();
        $this->db->where('id', $id);
        $result = $this->db->update('shipping_rates_v2', array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'status'     => 'Inactive'
        ));
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Soft Delete Shipping Rate v2', 'Rate ID: ' . $id);
        }
        return $result;
    }

    public function toggle_rate_status_v2($id) {
        $this->ensure_rates_v2_schema();
        $rate = $this->db->select('id, status')->where('id', $id)->where('deleted_at IS NULL')->get('shipping_rates_v2')->row();
        if ($rate) {
            $new_status = ($rate->status === 'Active') ? 'Inactive' : 'Active';
            $this->db->where('id', $id)->update('shipping_rates_v2', array('status' => $new_status, 'updated_at' => date('Y-m-d H:i:s')));
            return $new_status;
        }
        return FALSE;
    }

    public function get_all_rates_for_export_v2($filters = array()) {
        $this->ensure_rates_v2_schema();

        $this->db->select('r.*, c.country_name as destination_country, c.country_code as destination_country_code, p.partner_name as courier_partner_name');
        $this->db->from('shipping_rates_v2 r');
        $this->db->join('countries c', 'c.id = r.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = r.courier_partner_id', 'left');
        $this->db->where('r.deleted_at IS NULL');

        if (!empty($filters['destination_country_id'])) {
            $this->db->where('r.destination_country_id', $filters['destination_country_id']);
        }
        if (!empty($filters['courier_partner_id'])) {
            $this->db->where('r.courier_partner_id', $filters['courier_partner_id']);
        }
        if (!empty($filters['service_type'])) {
            $this->db->where('r.service_type', $filters['service_type']);
        }
        if (!empty($filters['shipment_type'])) {
            $this->db->where('r.shipment_type', $filters['shipment_type']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('r.status', $filters['status']);
        }

        $this->db->order_by('c.country_name', 'ASC');
        $this->db->order_by('p.partner_name', 'ASC');
        $this->db->order_by('r.service_type', 'ASC');
        $this->db->order_by('r.weight', 'ASC');

        $query = $this->db->get();
        return ($query) ? $query->result_array() : array();
    }

    public function bulk_import_rates_v2($records, $strategy = 'update') {
        $this->ensure_rates_v2_schema();
        // Pre-fetch Country maps
        $all_countries = $this->db->select('id, country_name, country_code')->get('countries')->result();
        $country_map = array();
        foreach ($all_countries as $c) {
            $country_map[strtolower(trim($c->country_name))] = $c->id;
            $country_map[strtolower(trim($c->country_code))] = $c->id;
        }

        // Pre-fetch Courier Partner maps
        $all_partners = $this->db->select('id, partner_name')->get('courier_partners')->result();
        $partner_map = array();
        foreach ($all_partners as $p) {
            $partner_map[strtolower(trim($p->partner_name))] = $p->id;
        }

        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $errors = array();

        foreach ($records as $index => $row) {
            $row_num = $index + 2; // Assuming row 1 is header

            $country_input = trim($row['destination_country'] ?? '');
            $service_type  = trim($row['service_type'] ?? '');
            $shipment_type = trim($row['shipment_type'] ?? '');
            $partner_input = trim($row['courier_partner'] ?? '');
            $weight_input  = trim($row['weight'] ?? '');
            $rate_input    = trim($row['rate'] ?? '');
            $status_input  = trim($row['status'] ?? 'Active');

            if (empty($country_input) && empty($partner_input) && empty($weight_input)) {
                continue; // Skip empty row
            }

            // 1. Resolve Country
            $country_key = strtolower($country_input);
            if (!isset($country_map[$country_key])) {
                $errors[] = "Row {$row_num}: Unknown destination country '{$country_input}'";
                continue;
            }
            $country_id = $country_map[$country_key];

            // 2. Resolve Partner
            $partner_key = strtolower($partner_input);
            if (!isset($partner_map[$partner_key])) {
                $errors[] = "Row {$row_num}: Unknown courier partner '{$partner_input}'";
                continue;
            }
            $partner_id = $partner_map[$partner_key];

            // 3. Resolve Service Type
            if (empty($service_type)) {
                $service_type = 'Express';
            }

            // 4. Resolve Shipment Type
            if (empty($shipment_type)) {
                $shipment_type = 'Documents (Paper / Files)';
            }

            // 5. Validate Weight & Rate
            if (!is_numeric($weight_input) || floatval($weight_input) <= 0) {
                $errors[] = "Row {$row_num}: Invalid weight '{$weight_input}'. Must be numeric > 0.";
                continue;
            }
            if (!is_numeric($rate_input) || floatval($rate_input) < 0) {
                $errors[] = "Row {$row_num}: Invalid rate '{$rate_input}'. Must be numeric >= 0.";
                continue;
            }

            $weight = floatval($weight_input);
            $rate = floatval($rate_input);
            $status = (strcasecmp($status_input, 'Inactive') === 0) ? 'Inactive' : 'Active';

            // 6. Check existing matching rate
            $this->db->where('destination_country_id', $country_id);
            $this->db->where('courier_partner_id', $partner_id);
            $this->db->where('service_type', $service_type);
            $this->db->where('shipment_type', $shipment_type);
            $this->db->where('weight', $weight);
            $existing = $this->db->get('shipping_rates_v2')->row();

            if ($existing) {
                if ($strategy === 'update') {
                    $this->db->where('id', $existing->id)->update('shipping_rates_v2', array(
                        'rate'       => $rate,
                        'status'     => $status,
                        'deleted_at' => NULL,
                        'updated_at' => date('Y-m-d H:i:s')
                    ));
                    $updated++;
                } else {
                    $skipped++;
                }
            } else {
                $this->db->insert('shipping_rates_v2', array(
                    'destination_country_id' => $country_id,
                    'courier_partner_id'     => $partner_id,
                    'service_type'           => $service_type,
                    'shipment_type'          => $shipment_type,
                    'weight'                 => $weight,
                    'rate'                   => $rate,
                    'status'                 => $status,
                    'created_at'             => date('Y-m-d H:i:s'),
                    'updated_at'             => date('Y-m-d H:i:s')
                ));
                $inserted++;
            }
        }

        if (isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Import Shipping Rates v2', "Inserted: $inserted, Updated: $updated, Skipped: $skipped");
        }

        return array(
            'success'  => true,
            'inserted' => $inserted,
            'updated'  => $updated,
            'skipped'  => $skipped,
            'errors'   => $errors
        );
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

    // --- GEO LOCATIONS (01_geo_location_info) ---
    public function get_geo_locations($id = NULL) {
        if ($id) {
            $this->db->where('id', $id);
            return $this->db->get('01_geo_location_info')->row();
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('01_geo_location_info')->result();
    }

    public function get_geo_locations_datatable($limit, $start, $search = null, $order_col = 'id', $order_dir = 'DESC') {
        $this->db->select('*');
        $this->db->from('01_geo_location_info');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('country_name', $search);
            $this->db->or_like('country_code', $search);
            $this->db->or_like('country_code3', $search);
            $this->db->or_like('state_name', $search);
            $this->db->or_like('state_code', $search);
            $this->db->or_like('district_name', $search);
            $this->db->or_like('city_name', $search);
            $this->db->or_like('postal_code', $search);
            $this->db->or_like('postal_name', $search);
            $this->db->group_end();
        }

        $allowed_cols = array(
            'id', 'country_name', 'state_name', 'district_name', 'city_name', 'postal_code', 'latitude', 'is_active'
        );
        if (in_array($order_col, $allowed_cols)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('id', 'DESC');
        }

        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function count_all_geo_locations() {
        return $this->db->count_all('01_geo_location_info');
    }

    public function count_filtered_geo_locations($search = null) {
        $this->db->from('01_geo_location_info');
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('country_name', $search);
            $this->db->or_like('country_code', $search);
            $this->db->or_like('country_code3', $search);
            $this->db->or_like('state_name', $search);
            $this->db->or_like('state_code', $search);
            $this->db->or_like('district_name', $search);
            $this->db->or_like('city_name', $search);
            $this->db->or_like('postal_code', $search);
            $this->db->or_like('postal_name', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    public function add_geo_location($data) {
        $result = $this->db->insert('01_geo_location_info', $data);
        $id = $this->db->insert_id();
        $this->Audit_model->log_activity('Add Geo Location', 'Location ID: ' . $id . ', Postal: ' . (isset($data['postal_code']) ? $data['postal_code'] : ''));
        return $id;
    }

    public function update_geo_location($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('01_geo_location_info', $data);
        $this->Audit_model->log_activity('Update Geo Location', 'Location ID: ' . $id);
        return $result;
    }

    public function delete_geo_location($id) {
        $geo = $this->get_geo_locations($id);
        $this->db->where('id', $id);
        $result = $this->db->delete('01_geo_location_info');
        if ($geo) {
            $this->Audit_model->log_activity('Delete Geo Location', 'Location ID: ' . $id . ', Postal: ' . $geo->postal_code);
        }
        return $result;
    }

    public function get_all_geo_locations_for_export($limit = 100000) {
        $this->db->select('*');
        $this->db->from('01_geo_location_info');
        $this->db->order_by('id', 'ASC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function upsert_geo_location($data) {
        if (!empty($data['postal_code']) && !empty($data['country_name'])) {
            $this->db->where('postal_code', $data['postal_code']);
            $this->db->where('country_name', $data['country_name']);
            if (!empty($data['city_name'])) {
                $this->db->where('city_name', $data['city_name']);
            }
            $existing = $this->db->get('01_geo_location_info')->row();
            if ($existing) {
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->db->where('id', $existing->id);
                $this->db->update('01_geo_location_info', $data);
                return 'updated';
            }
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('01_geo_location_info', $data);
        return 'inserted';
    }
}
