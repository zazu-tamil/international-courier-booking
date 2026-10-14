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

    // =========================================================================
    // --- ODA (OUT OF DELIVERY AREA) CHARGES MASTER ---
    // Fields: Destination Country, Courier Partner, Location (Pincode/Range/City),
    //         Calculation Type, Min Charge, Per KG Rate, Flat Charge, Status
    // =========================================================================

    public function ensure_oda_charges_schema() {
        static $checked = FALSE;
        if ($checked) return;
        $checked = TRUE;

        $table_check = $this->db->query("SHOW TABLES LIKE 'master_oda_charges'");
        if (!$table_check || $table_check->num_rows() == 0) {
            $create_sql = "CREATE TABLE IF NOT EXISTS `master_oda_charges` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `destination_country_id` INT(11) NOT NULL COMMENT 'References countries.id',
              `courier_partner_id` INT(11) NOT NULL DEFAULT 0 COMMENT 'References courier_partners.id (0 = All Partners)',
              `location_type` ENUM('pincode', 'pincode_range', 'city') NOT NULL DEFAULT 'pincode',
              `postal_code` VARCHAR(30) NULL COMMENT 'Single postal code or exact match',
              `postal_code_from` VARCHAR(30) NULL COMMENT 'Starting postal code in range',
              `postal_code_to` VARCHAR(30) NULL COMMENT 'Ending postal code in range',
              `city_name` VARCHAR(150) NULL COMMENT 'City, Town, or Island name',
              `state_name` VARCHAR(150) NULL COMMENT 'State / Province (optional)',
              `calculation_type` ENUM('higher_of', 'flat', 'per_kg') NOT NULL DEFAULT 'higher_of',
              `min_charge` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Minimum base ODA surcharge',
              `per_kg_rate` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Rate per kg for ODA',
              `flat_charge` DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Fixed charge if calculation_type is flat',
              `status` ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
              `created_by` INT(11) NULL,
              `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `deleted_at` DATETIME NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `idx_oda_dest_country` (`destination_country_id`),
              KEY `idx_oda_partner` (`courier_partner_id`),
              KEY `idx_oda_postal` (`postal_code`),
              KEY `idx_oda_postal_range` (`postal_code_from`, `postal_code_to`),
              KEY `idx_oda_city` (`city_name`),
              KEY `idx_oda_status` (`status`),
              KEY `idx_oda_deleted_at` (`deleted_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($create_sql);
        }

        // Ensure service_type column exists
        $col_check = $this->db->query("SHOW COLUMNS FROM `master_oda_charges` LIKE 'service_type'");
        if (!$col_check || $col_check->num_rows() == 0) {
            $this->db->query("ALTER TABLE `master_oda_charges` ADD COLUMN `service_type` VARCHAR(50) NOT NULL DEFAULT 'All' AFTER `courier_partner_id`, ADD KEY `idx_oda_service_type` (`service_type`)");
        }

        // Ensure geo_location_id column exists
        $geo_check = $this->db->query("SHOW COLUMNS FROM `master_oda_charges` LIKE 'geo_location_id'");
        if (!$geo_check || $geo_check->num_rows() == 0) {
            $this->db->query("ALTER TABLE `master_oda_charges` ADD COLUMN `geo_location_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `location_type`, ADD KEY `idx_oda_geo_location_id` (`geo_location_id`)");
        }

        // Seed initial sample ODA entries if empty
        $count = $this->db->where('deleted_at IS NULL')->count_all_results('master_oda_charges');
        if ($count == 0) {
            $c_res = $this->db->query("SELECT id, country_name FROM countries WHERE country_name IN ('United States', 'United Kingdom', 'United Arab Emirates', 'Canada', 'Australia', 'Greece')");
            $countries = array();
            if ($c_res && $c_res->num_rows() > 0) {
                foreach ($c_res->result() as $c) {
                    $countries[$c->country_name] = $c->id;
                }
            }
            $p_res = $this->db->query("SELECT id, partner_name FROM courier_partners LIMIT 5");
            $partners = array();
            if ($p_res && $p_res->num_rows() > 0) {
                foreach ($p_res->result() as $p) {
                    $partners[$p->partner_name] = $p->id;
                }
            }

            if (!empty($countries)) {
                $sample_oda = array(
                    array('United Arab Emirates', 'Aramex', 'All', 'city', '', '', '', 'Hatta', 'Dubai', 'flat', 0.00, 0.00, 1500.00),
                    array('United Arab Emirates', 'DHL Express', 'Express', 'city', '', '', '', 'Al Madam', 'Sharjah', 'higher_of', 1800.00, 35.00, 0.00),
                    array('United States', 'DHL Express', 'All', 'pincode', '99501', '', '', 'Anchorage', 'Alaska', 'higher_of', 2100.00, 42.00, 0.00),
                    array('United States', 'FedEx', 'All', 'pincode_range', '', '99502', '99950', 'Remote Alaska', 'Alaska', 'higher_of', 2150.00, 45.00, 0.00),
                    array('United Kingdom', 'DHL Express', 'All', 'pincode_range', '', 'IV1', 'IV63', 'Highlands & Islands', 'Inverness', 'higher_of', 1850.00, 35.00, 0.00),
                    array('Australia', 'FedEx', 'All', 'pincode', '0870', '', '', 'Alice Springs', 'Northern Territory', 'higher_of', 2200.00, 48.00, 0.00)
                );

                foreach ($sample_oda as $item) {
                    $c_id = isset($countries[$item[0]]) ? $countries[$item[0]] : reset($countries);
                    $p_id = isset($partners[$item[1]]) ? $partners[$item[1]] : 0;
                    $this->db->insert('master_oda_charges', array(
                        'destination_country_id' => $c_id,
                        'courier_partner_id'     => $p_id,
                        'service_type'           => $item[2],
                        'location_type'          => $item[3],
                        'postal_code'            => $item[4] ? $item[4] : NULL,
                        'postal_code_from'       => $item[5] ? $item[5] : NULL,
                        'postal_code_to'         => $item[6] ? $item[6] : NULL,
                        'city_name'              => $item[7] ? $item[7] : NULL,
                        'state_name'             => $item[8] ? $item[8] : NULL,
                        'calculation_type'       => $item[9],
                        'min_charge'             => $item[10],
                        'per_kg_rate'            => $item[11],
                        'flat_charge'            => $item[12],
                        'status'                 => 'Active',
                        'created_at'             => date('Y-m-d H:i:s')
                    ));
                }
            }
        }
    }

    /**
     * Get ODA charges for DataTables server-side processing
     */
    public function get_oda_charges_datatable($filters, $limit, $start, $search = null, $order_col = 'id', $order_dir = 'DESC') {
        $this->ensure_oda_charges_schema();

        $this->db->select('o.*, c.country_name as destination_country, c.country_code as destination_country_code, p.partner_name as courier_partner_name, g.district_name as geo_district_name, g.postal_name as geo_postal_name');
        $this->db->from('master_oda_charges o');
        $this->db->join('countries c', 'c.id = o.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = o.courier_partner_id', 'left');
        $this->db->join('01_geo_location_info g', 'g.id = o.geo_location_id', 'left');
        $this->db->where('o.deleted_at IS NULL');

        $this->_apply_oda_filters($filters);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.country_name', $search);
            $this->db->or_like('p.partner_name', $search);
            $this->db->or_like('o.service_type', $search);
            $this->db->or_like('o.postal_code', $search);
            $this->db->or_like('o.postal_code_from', $search);
            $this->db->or_like('o.postal_code_to', $search);
            $this->db->or_like('o.city_name', $search);
            $this->db->or_like('o.state_name', $search);
            $this->db->or_like('g.postal_name', $search);
            $this->db->or_like('g.district_name', $search);
            $this->db->group_end();
        }

        // Map column ordering
        $col_map = array(
            0 => 'o.id',
            1 => 'c.country_name',
            2 => 'p.partner_name',
            3 => 'o.service_type',
            4 => 'o.location_type',
            5 => 'o.postal_code',
            6 => 'o.calculation_type',
            7 => 'o.status',
            8 => 'o.id'
        );
        $order_by_col = isset($col_map[$order_col]) ? $col_map[$order_col] : 'o.id';
        $this->db->order_by($order_by_col, $order_dir);

        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return ($query) ? $query->result() : array();
    }

    public function count_all_oda_charges($filters = array()) {
        $this->ensure_oda_charges_schema();
        $this->db->from('master_oda_charges o');
        $this->db->where('o.deleted_at IS NULL');
        $this->_apply_oda_filters($filters);
        return $this->db->count_all_results();
    }

    public function count_filtered_oda_charges($filters = array(), $search = null) {
        $this->ensure_oda_charges_schema();
        $this->db->from('master_oda_charges o');
        $this->db->join('countries c', 'c.id = o.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = o.courier_partner_id', 'left');
        $this->db->join('01_geo_location_info g', 'g.id = o.geo_location_id', 'left');
        $this->db->where('o.deleted_at IS NULL');

        $this->_apply_oda_filters($filters);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.country_name', $search);
            $this->db->or_like('p.partner_name', $search);
            $this->db->or_like('o.service_type', $search);
            $this->db->or_like('o.postal_code', $search);
            $this->db->or_like('o.postal_code_from', $search);
            $this->db->or_like('o.postal_code_to', $search);
            $this->db->or_like('o.city_name', $search);
            $this->db->or_like('o.state_name', $search);
            $this->db->or_like('g.postal_name', $search);
            $this->db->or_like('g.district_name', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    private function _apply_oda_filters($filters) {
        if (!empty($filters['destination_country_id'])) {
            $this->db->where('o.destination_country_id', intval($filters['destination_country_id']));
        }
        if (isset($filters['courier_partner_id']) && $filters['courier_partner_id'] !== '') {
            $this->db->where('o.courier_partner_id', intval($filters['courier_partner_id']));
        }
        if (!empty($filters['service_type']) && $filters['service_type'] !== 'All') {
            $this->db->where('o.service_type', $filters['service_type']);
        }
        if (!empty($filters['location_type'])) {
            $this->db->where('o.location_type', $filters['location_type']);
        }
        if (!empty($filters['calculation_type'])) {
            $this->db->where('o.calculation_type', $filters['calculation_type']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('o.status', $filters['status']);
        }
    }

    public function get_oda_charge_by_id($id) {
        $this->ensure_oda_charges_schema();
        $this->db->select('o.*, c.country_name as destination_country, p.partner_name as courier_partner_name, g.postal_code as geo_postal_code, g.city_name as geo_city_name, g.state_name as geo_state_name, g.district_name as geo_district_name, g.postal_name as geo_postal_name');
        $this->db->from('master_oda_charges o');
        $this->db->join('countries c', 'c.id = o.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = o.courier_partner_id', 'left');
        $this->db->join('01_geo_location_info g', 'g.id = o.geo_location_id', 'left');
        $this->db->where('o.id', intval($id));
        $this->db->where('o.deleted_at IS NULL');
        $query = $this->db->get();
        return ($query && $query->num_rows() > 0) ? $query->row() : NULL;
    }

    /**
     * Search verified geo locations from 01_geo_location_info
     */
    public function search_geo_locations($q = '', $country_id = null, $limit = 30) {
        $this->db->select('g.id, g.country_name, g.country_code, g.country_code3, g.state_name, g.state_code, g.district_name, g.city_name, g.postal_code, g.postal_name, g.latitude, g.longitude');
        $this->db->from('01_geo_location_info g');
        $this->db->where('g.is_active', 1);

        $has_country_filter = false;
        if (!empty($country_id)) {
            $country = $this->db->get_where('countries', array('id' => intval($country_id)))->row();
            if ($country) {
                $has_country_filter = true;
                $this->db->group_start();
                $this->db->where('g.country_name', $country->country_name);
                if (!empty($country->iso_code)) {
                    $this->db->or_where('g.country_code3', $country->iso_code);
                    $this->db->or_where('g.country_code', $country->iso_code);
                }
                $this->db->group_end();
            }
        }

        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('g.postal_code', $q);
            $this->db->or_like('g.city_name', $q);
            $this->db->or_like('g.state_name', $q);
            $this->db->or_like('g.postal_name', $q);
            $this->db->or_like('g.district_name', $q);
            $this->db->group_end();
        }

        $this->db->order_by('g.country_name', 'ASC');
        $this->db->order_by('g.city_name', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get();
        $results = ($query) ? $query->result() : array();

        // If specific country was selected but returned 0 results and $q was provided,
        // fallback to searching all countries so user still finds matching location
        if (empty($results) && $has_country_filter && !empty($q)) {
            $this->db->select('g.id, g.country_name, g.country_code, g.country_code3, g.state_name, g.state_code, g.district_name, g.city_name, g.postal_code, g.postal_name, g.latitude, g.longitude');
            $this->db->from('01_geo_location_info g');
            $this->db->where('g.is_active', 1);
            $this->db->group_start();
            $this->db->like('g.postal_code', $q);
            $this->db->or_like('g.city_name', $q);
            $this->db->or_like('g.state_name', $q);
            $this->db->or_like('g.postal_name', $q);
            $this->db->or_like('g.district_name', $q);
            $this->db->group_end();
            $this->db->order_by('g.country_name', 'ASC');
            $this->db->order_by('g.city_name', 'ASC');
            $this->db->limit($limit);
            $fb_query = $this->db->get();
            if ($fb_query && $fb_query->num_rows() > 0) {
                return $fb_query->result();
            }
        }

        return $results;
    }

    public function add_oda_charge($data) {
        $this->ensure_oda_charges_schema();
        $insert_data = array(
            'destination_country_id' => intval($data['destination_country_id']),
            'courier_partner_id'     => intval($data['courier_partner_id']),
            'service_type'           => !empty($data['service_type']) ? trim($data['service_type']) : 'All',
            'location_type'          => in_array($data['location_type'], array('pincode', 'pincode_range', 'city')) ? $data['location_type'] : 'pincode',
            'geo_location_id'        => !empty($data['geo_location_id']) ? intval($data['geo_location_id']) : NULL,
            'postal_code'            => !empty($data['postal_code']) ? trim($data['postal_code']) : NULL,
            'postal_code_from'       => !empty($data['postal_code_from']) ? trim($data['postal_code_from']) : NULL,
            'postal_code_to'         => !empty($data['postal_code_to']) ? trim($data['postal_code_to']) : NULL,
            'city_name'              => !empty($data['city_name']) ? trim($data['city_name']) : NULL,
            'state_name'             => !empty($data['state_name']) ? trim($data['state_name']) : NULL,
            'calculation_type'       => in_array($data['calculation_type'], array('higher_of', 'flat', 'per_kg')) ? $data['calculation_type'] : 'higher_of',
            'min_charge'             => floatval($data['min_charge']),
            'per_kg_rate'            => floatval($data['per_kg_rate']),
            'flat_charge'            => floatval($data['flat_charge']),
            'status'                 => (!empty($data['status']) && in_array($data['status'], array('Active', 'Inactive'))) ? $data['status'] : 'Active',
            'created_by'             => isset($data['created_by']) ? intval($data['created_by']) : NULL,
            'created_at'             => date('Y-m-d H:i:s'),
            'updated_at'             => date('Y-m-d H:i:s')
        );

        $result = $this->db->insert('master_oda_charges', $insert_data);
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Add ODA Charge', 'Country ID: ' . $insert_data['destination_country_id'] . ', Partner: ' . $insert_data['courier_partner_id'] . ', Service: ' . $insert_data['service_type']);
        }
        return $result;
    }

    public function update_oda_charge($id, $data) {
        $this->ensure_oda_charges_schema();
        $update_data = array(
            'destination_country_id' => intval($data['destination_country_id']),
            'courier_partner_id'     => intval($data['courier_partner_id']),
            'service_type'           => !empty($data['service_type']) ? trim($data['service_type']) : 'All',
            'location_type'          => in_array($data['location_type'], array('pincode', 'pincode_range', 'city')) ? $data['location_type'] : 'pincode',
            'geo_location_id'        => !empty($data['geo_location_id']) ? intval($data['geo_location_id']) : NULL,
            'postal_code'            => !empty($data['postal_code']) ? trim($data['postal_code']) : NULL,
            'postal_code_from'       => !empty($data['postal_code_from']) ? trim($data['postal_code_from']) : NULL,
            'postal_code_to'         => !empty($data['postal_code_to']) ? trim($data['postal_code_to']) : NULL,
            'city_name'              => !empty($data['city_name']) ? trim($data['city_name']) : NULL,
            'state_name'             => !empty($data['state_name']) ? trim($data['state_name']) : NULL,
            'calculation_type'       => in_array($data['calculation_type'], array('higher_of', 'flat', 'per_kg')) ? $data['calculation_type'] : 'higher_of',
            'min_charge'             => floatval($data['min_charge']),
            'per_kg_rate'            => floatval($data['per_kg_rate']),
            'flat_charge'            => floatval($data['flat_charge']),
            'status'                 => (!empty($data['status']) && in_array($data['status'], array('Active', 'Inactive'))) ? $data['status'] : 'Active',
            'updated_at'             => date('Y-m-d H:i:s')
        );

        $this->db->where('id', intval($id));
        $this->db->where('deleted_at IS NULL');
        $result = $this->db->update('master_oda_charges', $update_data);
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Update ODA Charge', 'ID: ' . $id);
        }
        return $result;
    }

    public function delete_oda_charge($id) {
        $this->ensure_oda_charges_schema();
        $this->db->where('id', intval($id));
        $result = $this->db->update('master_oda_charges', array(
            'deleted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Delete ODA Charge (Soft)', 'ID: ' . $id);
        }
        return $result;
    }

    public function toggle_oda_charge_status($id) {
        $this->ensure_oda_charges_schema();
        $charge = $this->get_oda_charge_by_id($id);
        if ($charge) {
            $new_status = ($charge->status === 'Active') ? 'Inactive' : 'Active';
            $this->db->where('id', intval($id))->update('master_oda_charges', array('status' => $new_status, 'updated_at' => date('Y-m-d H:i:s')));
            return $new_status;
        }
        return FALSE;
    }

    public function get_all_oda_charges_for_export($filters = array()) {
        $this->ensure_oda_charges_schema();
        $this->db->select('o.*, c.country_name as destination_country, p.partner_name as courier_partner_name, g.postal_name as geo_postal_name, g.district_name as geo_district_name');
        $this->db->from('master_oda_charges o');
        $this->db->join('countries c', 'c.id = o.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = o.courier_partner_id', 'left');
        $this->db->join('01_geo_location_info g', 'g.id = o.geo_location_id', 'left');
        $this->db->where('o.deleted_at IS NULL');

        $this->_apply_oda_filters($filters);
        $this->db->order_by('c.country_name', 'ASC');
        $this->db->order_by('p.partner_name', 'ASC');
        $this->db->order_by('o.id', 'ASC');
        $query = $this->db->get();
        return ($query) ? $query->result_array() : array();
    }
    public function bulk_import_oda_charges($records, $strategy = 'update') {
        $this->ensure_oda_charges_schema();

        // Pre-fetch Country and Partner maps
        $all_countries = $this->db->select('id, country_name, country_code')->get('countries')->result();
        $country_map = array();
        foreach ($all_countries as $c) {
            $country_map[strtolower(trim($c->country_name))] = $c->id;
            $country_map[strtolower(trim($c->country_code))] = $c->id;
        }

        $all_partners = $this->db->select('id, partner_name')->get('courier_partners')->result();
        $partner_map = array();
        foreach ($all_partners as $p) {
            $partner_map[strtolower(trim($p->partner_name))] = $p->id;
        }

        $inserted = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = array();

        foreach ($records as $idx => $row) {
            $row_num = $idx + 2;

            $country_raw = isset($row['destination_country']) ? trim($row['destination_country']) : (isset($row['country']) ? trim($row['country']) : '');
            $partner_raw = isset($row['courier_partner']) ? trim($row['courier_partner']) : (isset($row['partner']) ? trim($row['partner']) : '');

            $country_key = strtolower($country_raw);
            if (!isset($country_map[$country_key])) {
                $errors[] = "Row {$row_num}: Unknown destination country '{$country_raw}'.";
                $skipped++;
                continue;
            }
            $dest_country_id = $country_map[$country_key];

            $courier_partner_id = 0; // 0 = All Partners
            if (!empty($partner_raw) && strtolower($partner_raw) !== 'all' && strtolower($partner_raw) !== 'all partners') {
                $partner_key = strtolower($partner_raw);
                if (isset($partner_map[$partner_key])) {
                    $courier_partner_id = $partner_map[$partner_key];
                }
            }

            $service_type = isset($row['service_type']) ? trim($row['service_type']) : (isset($row['service']) ? trim($row['service']) : 'All');
            if (empty($service_type)) {
                $service_type = 'All';
            }

            $loc_type = isset($row['location_type']) ? strtolower(trim($row['location_type'])) : 'pincode';
            if (!in_array($loc_type, array('pincode', 'pincode_range', 'city'))) {
                $loc_type = 'pincode';
            }

            $geo_location_id  = !empty($row['geo_location_id']) ? intval($row['geo_location_id']) : NULL;
            $postal_code      = isset($row['postal_code']) ? trim($row['postal_code']) : NULL;
            $postal_code_from = isset($row['postal_code_from']) ? trim($row['postal_code_from']) : NULL;
            $postal_code_to   = isset($row['postal_code_to']) ? trim($row['postal_code_to']) : NULL;
            $city_name        = isset($row['city_name']) ? trim($row['city_name']) : (isset($row['city']) ? trim($row['city']) : NULL);
            $state_name       = isset($row['state_name']) ? trim($row['state_name']) : (isset($row['state']) ? trim($row['state']) : NULL);

            // Auto-link to 01_geo_location_info if postal_code provided and geo_location_id missing
            if (empty($geo_location_id) && !empty($postal_code)) {
                $this->db->select('id');
                $this->db->from('01_geo_location_info');
                $this->db->where('postal_code', $postal_code);
                $this->db->where('is_active', 1);
                $geo_match = $this->db->limit(1)->get()->row();
                if ($geo_match) {
                    $geo_location_id = $geo_match->id;
                }
            }

            $calc_type = isset($row['calculation_type']) ? strtolower(trim($row['calculation_type'])) : 'higher_of';
            if (!in_array($calc_type, array('higher_of', 'flat', 'per_kg'))) {
                $calc_type = 'higher_of';
            }

            $min_charge  = isset($row['min_charge']) ? floatval($row['min_charge']) : 0.00;
            $per_kg_rate = isset($row['per_kg_rate']) ? floatval($row['per_kg_rate']) : 0.00;
            $flat_charge = isset($row['flat_charge']) ? floatval($row['flat_charge']) : 0.00;
            $status      = (isset($row['status']) && in_array(ucfirst(strtolower(trim($row['status']))), array('Active', 'Inactive'))) ? ucfirst(strtolower(trim($row['status']))) : 'Active';

            // Check if existing record matches
            $this->db->where('destination_country_id', $dest_country_id);
            $this->db->where('courier_partner_id', $courier_partner_id);
            $this->db->where('service_type', $service_type);
            $this->db->where('location_type', $loc_type);
            $this->db->where('deleted_at IS NULL');
            if ($loc_type === 'pincode') {
                $this->db->where('postal_code', $postal_code);
            } elseif ($loc_type === 'pincode_range') {
                $this->db->where('postal_code_from', $postal_code_from);
                $this->db->where('postal_code_to', $postal_code_to);
            } else {
                $this->db->where('LOWER(city_name)', strtolower($city_name));
            }
            $existing = $this->db->get('master_oda_charges')->row();

            $save_data = array(
                'destination_country_id' => $dest_country_id,
                'courier_partner_id'     => $courier_partner_id,
                'service_type'           => $service_type,
                'location_type'          => $loc_type,
                'geo_location_id'        => $geo_location_id,
                'postal_code'            => $postal_code,
                'postal_code_from'       => $postal_code_from,
                'postal_code_to'         => $postal_code_to,
                'city_name'              => $city_name,
                'state_name'             => $state_name,
                'calculation_type'       => $calc_type,
                'min_charge'             => $min_charge,
                'per_kg_rate'            => $per_kg_rate,
                'flat_charge'            => $flat_charge,
                'status'                 => $status,
                'updated_at'             => date('Y-m-d H:i:s')
            );

            if ($existing) {
                if ($strategy === 'skip') {
                    $skipped++;
                } else {
                    $this->db->where('id', $existing->id)->update('master_oda_charges', $save_data);
                    $updated++;
                }
            } else {
                $save_data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('master_oda_charges', $save_data);
                $inserted++;
            }
        }

        return array(
            'inserted' => $inserted,
            'updated'  => $updated,
            'skipped'  => $skipped,
            'errors'   => $errors
        );
    }

    /**
     * Core ODA Detection and Rate Calculation Engine
     * Matches receiver location against active ODA rules, taking into account
     * destination country, courier partner, service type, and 01_geo_location_info.
     */
    public function check_oda_status($country_id, $partner_id = 0, $pincode = '', $city = '', $weight = 0, $service_type = '') {
        $this->ensure_oda_charges_schema();

        $country_id   = intval($country_id);
        $partner_id   = intval($partner_id);
        $pincode      = strtoupper(trim($pincode));
        $city         = trim($city);
        $weight       = floatval($weight);
        $service_type = trim($service_type);

        if ($country_id <= 0) {
            return array('is_oda' => FALSE, 'oda_amount' => 0.00);
        }

        // Enrich location information using 01_geo_location_info if available
        $geo_record = NULL;
        if (!empty($pincode) || !empty($city)) {
            $this->db->select('g.id, g.country_name, g.country_code, g.state_name, g.city_name, g.postal_code');
            $this->db->from('01_geo_location_info g');
            $this->db->where('g.is_active', 1);

            $country = $this->db->get_where('countries', array('id' => $country_id))->row();
            if ($country) {
                $this->db->group_start();
                $this->db->where('g.country_name', $country->country_name);
                if (!empty($country->iso_code)) {
                    $this->db->or_where('g.country_code', $country->iso_code);
                }
                $this->db->group_end();
            }

            if (!empty($pincode) && !empty($city)) {
                $this->db->group_start();
                $this->db->where('g.postal_code', $pincode);
                $this->db->or_like('g.city_name', $city);
                $this->db->group_end();
            } elseif (!empty($pincode)) {
                $this->db->where('g.postal_code', $pincode);
            } elseif (!empty($city)) {
                $this->db->like('g.city_name', $city);
            }

            $geo_record = $this->db->limit(1)->get()->row();
            if ($geo_record) {
                if (empty($city) && !empty($geo_record->city_name)) {
                    $city = $geo_record->city_name;
                }
                if (empty($pincode) && !empty($geo_record->postal_code)) {
                    $pincode = strtoupper(trim($geo_record->postal_code));
                }
            }
        }

        // Fetch candidate active rules for country & partner (or universal partner_id = 0)
        $this->db->select('o.*, p.partner_name as courier_partner_name, c.country_name, g.postal_name as geo_postal_name, g.district_name as geo_district_name');
        $this->db->from('master_oda_charges o');
        $this->db->join('countries c', 'c.id = o.destination_country_id', 'left');
        $this->db->join('courier_partners p', 'p.id = o.courier_partner_id', 'left');
        $this->db->join('01_geo_location_info g', 'g.id = o.geo_location_id', 'left');
        $this->db->where('o.destination_country_id', $country_id);
        $this->db->where('o.status', 'Active');
        $this->db->where('o.deleted_at IS NULL');

        if ($partner_id > 0) {
            $this->db->where_in('o.courier_partner_id', array($partner_id, 0));
        }

        if (!empty($service_type) && strtolower($service_type) !== 'all') {
            $this->db->where_in('o.service_type', array($service_type, 'All', ''));
        }

        // Priority ordering:
        // 1. Specific courier partner before universal 0
        // 2. Specific service_type before 'All'
        if ($partner_id > 0) {
            $this->db->order_by('o.courier_partner_id', 'DESC');
        }
        if (!empty($service_type) && strtolower($service_type) !== 'all') {
            $this->db->order_by("CASE WHEN o.service_type = ".$this->db->escape($service_type)." THEN 1 ELSE 2 END", 'ASC', FALSE);
        }
        $this->db->order_by('o.id', 'ASC');

        $rules = $this->db->get()->result();
        if (empty($rules)) {
            return array('is_oda' => FALSE, 'oda_amount' => 0.00);
        }

        $matched_rule = NULL;
        $match_reason = '';

        // Priority 0: Exact Geo Location ID Match (linked from 01_geo_location_info)
        if ($geo_record && !empty($geo_record->id)) {
            foreach ($rules as $r) {
                if (!empty($r->geo_location_id) && intval($r->geo_location_id) === intval($geo_record->id)) {
                    $matched_rule = $r;
                    $match_reason = "Verified Geo Location match [ID #{$geo_record->id}: {$geo_record->city_name}, {$geo_record->postal_code}]";
                    break;
                }
            }
        }

        // Priority 1: Exact Pincode Match
        if (!$matched_rule && !empty($pincode)) {
            foreach ($rules as $r) {
                if ($r->location_type === 'pincode' && !empty($r->postal_code)) {
                    // Check exact or comma-separated list
                    $postal_codes = array_map('trim', explode(',', strtoupper($r->postal_code)));
                    if (in_array($pincode, $postal_codes)) {
                        $matched_rule = $r;
                        $match_reason = "Postal code '{$pincode}' is mapped as ODA";
                        break;
                    }
                }
            }
        }

        // Priority 2: Pincode Range Match (e.g. from 99501 to 99950)
        if (!$matched_rule && !empty($pincode)) {
            foreach ($rules as $r) {
                if ($r->location_type === 'pincode_range' && !empty($r->postal_code_from) && !empty($r->postal_code_to)) {
                    $from = strtoupper(trim($r->postal_code_from));
                    $to   = strtoupper(trim($r->postal_code_to));

                    // Numeric comparison if both are numeric, otherwise natural string comparison
                    if (is_numeric($from) && is_numeric($to) && is_numeric($pincode)) {
                        $num_pin = floatval($pincode);
                        if ($num_pin >= floatval($from) && $num_pin <= floatval($to)) {
                            $matched_rule = $r;
                            $match_reason = "Postal code '{$pincode}' falls in ODA range [{$from} - {$to}]";
                            break;
                        }
                    } else {
                        // String prefix / natural comparison
                        if (strnatcasecmp($pincode, $from) >= 0 && strnatcasecmp($pincode, $to) <= 0) {
                            $matched_rule = $r;
                            $match_reason = "Postal code '{$pincode}' matches ODA range [{$from} - {$to}]";
                            break;
                        }
                    }
                }
            }
        }

        // Priority 3: City / Area Name Match
        if (!$matched_rule && !empty($city)) {
            $city_lower = strtolower($city);
            foreach ($rules as $r) {
                if ($r->location_type === 'city' && !empty($r->city_name)) {
                    $rule_city = strtolower(trim($r->city_name));
                    if ($city_lower === $rule_city || strpos($city_lower, $rule_city) !== false || strpos($rule_city, $city_lower) !== false) {
                        $matched_rule = $r;
                        $match_reason = "City '{$city}' is categorized as ODA location ({$r->city_name})";
                        break;
                    }
                }
            }
        }

        if ($matched_rule) {
            $oda_amount = 0.00;
            $formula_note = '';

            if ($matched_rule->calculation_type === 'higher_of') {
                $calc_kg = $weight * floatval($matched_rule->per_kg_rate);
                $min_c   = floatval($matched_rule->min_charge);
                if ($calc_kg > $min_c) {
                    $oda_amount = $calc_kg;
                    $formula_note = "₹{$matched_rule->per_kg_rate}/kg × {$weight} kg = ₹" . number_format($calc_kg, 2) . " (Higher of min ₹" . number_format($min_c, 2) . ")";
                } else {
                    $oda_amount = $min_c;
                    $formula_note = "Minimum fixed ODA charge ₹" . number_format($min_c, 2) . " applied (vs ₹" . number_format($calc_kg, 2) . " by weight)";
                }
            } elseif ($matched_rule->calculation_type === 'flat') {
                $oda_amount = floatval($matched_rule->flat_charge);
                $formula_note = "Flat ODA surcharge of ₹" . number_format($oda_amount, 2);
            } elseif ($matched_rule->calculation_type === 'per_kg') {
                $oda_amount = $weight * floatval($matched_rule->per_kg_rate);
                $formula_note = "₹{$matched_rule->per_kg_rate}/kg × {$weight} kg = ₹" . number_format($oda_amount, 2);
            }

            return array(
                'is_oda'             => TRUE,
                'rule_id'            => $matched_rule->id,
                'service_type'       => !empty($matched_rule->service_type) ? $matched_rule->service_type : 'All',
                'geo_location_id'    => !empty($matched_rule->geo_location_id) ? $matched_rule->geo_location_id : NULL,
                'partner_name'       => !empty($matched_rule->courier_partner_name) ? $matched_rule->courier_partner_name : 'All Courier Partners',
                'location_matched'   => $match_reason,
                'calculation_type'   => $matched_rule->calculation_type,
                'min_charge'         => floatval($matched_rule->min_charge),
                'per_kg_rate'        => floatval($matched_rule->per_kg_rate),
                'flat_charge'        => floatval($matched_rule->flat_charge),
                'oda_amount'         => round($oda_amount, 2),
                'formula_note'       => $formula_note
            );
        }

        return array('is_oda' => FALSE, 'oda_amount' => 0.00);
    }

    /**
     * Calculate International Rate Options for Dashboard Modal Calculator
     */
    public function calculate_international_rate_options($dest_id, $weight, $shipment_type = 'Documents', $partner_id = 0, $service_type = 'All', $pincode = '', $city = '') {
        $country = $this->db->get_where('countries', array('id' => intval($dest_id)))->row();
        if (!$country) {
            return array('status' => 'error', 'message' => 'Please select a valid destination country.');
        }

        $weight = floatval($weight);
        if ($weight <= 0) {
            $weight = 0.500;
        }

        // Query active rates in shipping_rates_v2
        $this->db->select('r.*, p.partner_name');
        $this->db->from('shipping_rates_v2 r');
        $this->db->join('courier_partners p', 'p.id = r.courier_partner_id');
        $this->db->where('r.destination_country_id', intval($dest_id));
        $this->db->where('r.status', 'Active');
        $this->db->where('r.deleted_at IS NULL');

        if ($partner_id > 0) {
            $this->db->where('r.courier_partner_id', intval($partner_id));
        }

        if (!empty($service_type) && $service_type !== 'All') {
            $this->db->where('r.service_type', $service_type);
        }

        // Shipment type matching
        if (stripos($shipment_type, 'Documents') !== false && stripos($shipment_type, 'Non') === false) {
            $this->db->like('r.shipment_type', 'Documents');
            $this->db->not_like('r.shipment_type', 'Non-Documents');
            $this->db->not_like('r.shipment_type', 'Non Documents');
        } else {
            $this->db->like('r.shipment_type', 'Non-Documents');
        }

        $all_rates = $this->db->get()->result();

        // Group rates by carrier partner + service type
        $grouped = array();
        foreach ($all_rates as $r) {
            $key = $r->courier_partner_id . '_' . $r->service_type;
            if (!isset($grouped[$key])) {
                $grouped[$key] = array();
            }
            $grouped[$key][] = $r;
        }

        $quotes = array();

        foreach ($grouped as $key => $rates_list) {
            // Sort slabs by weight ascending
            usort($rates_list, function($a, $b) {
                return floatval($a->weight) <=> floatval($b->weight);
            });

            $partner_id_curr = $rates_list[0]->courier_partner_id;
            $partner_name_curr = $rates_list[0]->partner_name;
            $service_type_curr = $rates_list[0]->service_type;

            // Find smallest slab >= $weight
            $matched_slab = NULL;
            foreach ($rates_list as $slab) {
                if (floatval($slab->weight) >= $weight) {
                    $matched_slab = $slab;
                    break;
                }
            }

            $base_rate = 0.00;
            $slab_weight = 0.00;
            $is_extrapolated = FALSE;

            if ($matched_slab) {
                $base_rate = floatval($matched_slab->rate);
                $slab_weight = floatval($matched_slab->weight);
            } else {
                // Weight exceeds max configured slab: take highest available slab and extrapolate proportionally
                $max_slab = end($rates_list);
                $slab_w = floatval($max_slab->weight);
                $slab_r = floatval($max_slab->rate);
                if ($slab_w > 0) {
                    $per_kg = $slab_r / $slab_w;
                    $base_rate = round($per_kg * $weight, 2);
                    $slab_weight = $slab_w;
                    $is_extrapolated = TRUE;
                }
            }

            // ODA Check if receiver pincode or city provided
            $oda_amount = 0.00;
            $oda_note = '';
            $is_oda = FALSE;

            if (!empty($pincode) || !empty($city)) {
                $oda_check = $this->check_oda_status($dest_id, $partner_id_curr, $pincode, $city, $weight, $service_type_curr);
                if (!empty($oda_check['is_oda'])) {
                    $is_oda = TRUE;
                    $oda_amount = floatval($oda_check['oda_amount']);
                    $oda_note = !empty($oda_check['formula_note']) ? $oda_check['formula_note'] : 'Out of Delivery Area Surcharge';
                }
            }

            // Total is base shipping charges + ODA charge (if applicable)
            $total = $base_rate + $oda_amount;

            $quotes[] = array(
                'partner_id'        => $partner_id_curr,
                'partner_name'      => $partner_name_curr,
                'service_type'      => $service_type_curr,
                'base_rate'         => $base_rate,
                'slab_weight'       => $slab_weight,
                'is_extrapolated'   => $is_extrapolated,
                'is_oda'            => $is_oda,
                'oda_amount'        => $oda_amount,
                'oda_note'          => $oda_note,
                'total_charges'     => round($total, 2),
                'transit_estimate'  => (strtolower($service_type_curr) === 'express') ? '2 - 4 Business Days' : '5 - 8 Business Days'
            );
        }

        // Sort quotes by total ascending (best price first)
        usort($quotes, function($a, $b) {
            return $a['total_charges'] <=> $b['total_charges'];
        });

        if (!empty($quotes)) {
            $quotes[0]['is_cheapest'] = TRUE;
        }

        return array(
            'status'              => 'success',
            'destination_country' => $country->country_name,
            'country_code'        => $country->country_code,
            'chargeable_weight'   => $weight,
            'quotes_count'        => count($quotes),
            'quotes'              => $quotes
        );
    }
}
