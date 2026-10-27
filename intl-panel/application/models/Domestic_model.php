<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domestic_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    /**
     * Get single pincode by ID
     */
    public function get_pincode($id) {
        return $this->db->get_where('crit_pincode_info', array('pincode_id' => $id))->row();
    }

    /**
     * Helper to apply filters to crit_pincode_info query
     */
    private function apply_pincode_filters($search = null, $state_filter = null, $zone_filter = null, $metro_filter = null, $status_filter = null) {
        if (!empty($status_filter)) {
            $this->db->where('status', $status_filter);
        } else {
            $this->db->where('status !=', 'Delete');
        }

        if (!empty($state_filter)) {
            $this->db->where('state_name', $state_filter);
        }

        if (!empty($zone_filter)) {
            $this->db->where('zone', $zone_filter);
        }

        if (!empty($metro_filter)) {
            $this->db->where('metro', $metro_filter);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('pincode', $search);
            $this->db->or_like('area_name', $search);
            $this->db->or_like('district_name', $search);
            $this->db->or_like('state_name', $search);
            $this->db->or_like('circle_name', $search);
            $this->db->or_like('region_name', $search);
            $this->db->or_like('division_name', $search);
            $this->db->or_like('zone', $search);
            $this->db->group_end();
        }
    }

    /**
     * Total count of all non-deleted pincodes
     */
    public function count_all_pincodes() {
        $this->db->where('status !=', 'Delete');
        return $this->db->count_all_results('crit_pincode_info');
    }

    /**
     * Filtered count for DataTables server-side
     */
    public function count_filtered_pincodes($search = null, $state_filter = null, $zone_filter = null, $metro_filter = null, $status_filter = null) {
        $this->apply_pincode_filters($search, $state_filter, $zone_filter, $metro_filter, $status_filter);
        return $this->db->count_all_results('crit_pincode_info');
    }

    /**
     * Fetch paginated & sorted records for DataTables server-side
     */
    public function get_pincodes_datatable($limit, $start, $search = null, $order_col = 'pincode_id', $order_dir = 'DESC', $state_filter = null, $zone_filter = null, $metro_filter = null, $status_filter = null) {
        $this->apply_pincode_filters($search, $state_filter, $zone_filter, $metro_filter, $status_filter);
        $this->db->order_by($order_col, $order_dir);
        $this->db->limit($limit, $start);
        return $this->db->get('crit_pincode_info')->result();
    }

    /**
     * Insert new pincode
     */
    public function add_pincode($data) {
        $result = $this->db->insert('crit_pincode_info', $data);
        $insert_id = $this->db->insert_id();
        if ($result && $this->Audit_model) {
            $this->Audit_model->log_activity('Add Domestic Pincode', 'ID: ' . $insert_id . ', Pincode: ' . ($data['pincode'] ?? '') . ', State: ' . ($data['state_name'] ?? ''));
        }
        return $insert_id;
    }

    /**
     * Update existing pincode
     */
    public function update_pincode($id, $data) {
        $this->db->where('pincode_id', $id);
        $result = $this->db->update('crit_pincode_info', $data);
        if ($result && $this->Audit_model) {
            $this->Audit_model->log_activity('Update Domestic Pincode', 'ID: ' . $id . ', Pincode: ' . ($data['pincode'] ?? ''));
        }
        return $result;
    }

    /**
     * Soft delete pincode - sets status to 'Delete'
     */
    public function soft_delete_pincode($id) {
        $pincode = $this->get_pincode($id);
        $this->db->where('pincode_id', $id);
        $result = $this->db->update('crit_pincode_info', array('status' => 'Delete'));
        if ($result && $this->Audit_model) {
            $pin_text = $pincode ? $pincode->pincode . ' (' . $pincode->area_name . ')' : $id;
            $this->Audit_model->log_activity('Soft Delete Domestic Pincode', 'Pincode ID: ' . $id . ', Pincode: ' . $pin_text);
        }
        return $result;
    }

    /**
     * Get distinct list of states for filter & form dropdowns
     */
    public function get_distinct_states() {
        $this->db->select('state_name');
        $this->db->where('status !=', 'Delete');
        $this->db->where('state_name IS NOT NULL', NULL, FALSE);
        $this->db->where("state_name != ''", NULL, FALSE);
        $this->db->group_by('state_name');
        $this->db->order_by('state_name', 'ASC');
        $query = $this->db->get('crit_pincode_info');
        return $query->result();
    }

    /**
     * Get distinct list of zones
     */
    public function get_distinct_zones() {
        $this->db->select('zone');
        $this->db->where('status !=', 'Delete');
        $this->db->where('zone IS NOT NULL', NULL, FALSE);
        $this->db->where("zone != ''", NULL, FALSE);
        $this->db->group_by('zone');
        $this->db->order_by('zone', 'ASC');
        $query = $this->db->get('crit_pincode_info');
        return $query->result();
    }

    /**
     * Get summary KPI statistics
     */
    public function get_pincode_stats() {
        $sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_cnt,
            SUM(CASE WHEN status = 'InActive' THEN 1 ELSE 0 END) as inactive_cnt,
            SUM(CASE WHEN metro = 'Y' AND status != 'Delete' THEN 1 ELSE 0 END) as metro_cnt,
            SUM(CASE WHEN (metro = 'N' OR metro IS NULL OR metro = '') AND status != 'Delete' THEN 1 ELSE 0 END) as non_metro_cnt,
            COUNT(DISTINCT CASE WHEN status != 'Delete' AND state_name IS NOT NULL AND state_name != '' THEN state_name END) as state_cnt
        FROM crit_pincode_info
        WHERE status != 'Delete'";
        return $this->db->query($sql)->row();
    }

    /**
     * Fetch records for Excel / CSV export
     */
    public function get_all_pincodes_for_export($state = null, $zone = null, $metro = null, $status = null, $limit = 50000) {
        $this->apply_pincode_filters(null, $state, $zone, $metro, $status);
        $this->db->order_by('pincode', 'ASC');
        $this->db->limit($limit);
        return $this->db->get('crit_pincode_info')->result();
    }

    /**
     * Fetch all domestic rates grouped by courier type and weight slab
     */
    public function get_domestic_rates_grouped($c_type = null, $status = null) {
        $this->db->select('*');
        $this->db->from('crit_domestic_rate_info_v3');
        
        if (!empty($status)) {
            $this->db->where('status', $status);
        } else {
            $this->db->where('status !=', 'Delete');
        }

        if (!empty($c_type) && in_array($c_type, array('Air', 'Surface'))) {
            $this->db->where('c_type', $c_type);
        }

        // Custom order to match the exact sequence shown in the reference image:
        // 1. Region:0 State:0 City:0 Metro:0 (All ✖)
        // 2. Metro:1 (Metro ✔)
        // 3. Region:1 State:0 City:0 Metro:0 (Region ✔)
        // 4. Region:1 State:1 City:0 Metro:0 (Region ✔ State ✔)
        // 5. Region:1 State:1 City:1 Metro:0 (Region ✔ State ✔ City ✔)
        $this->db->order_by('c_type', 'ASC');
        $this->db->order_by('from_weight', 'ASC');
        $this->db->order_by('to_weight', 'ASC');
        $this->db->order_by("
            CASE 
                WHEN flg_region = '0' AND flg_metro = '0' AND flg_state = '0' AND flg_city = '0' THEN 1
                WHEN flg_metro = '1' THEN 2
                WHEN flg_region = '1' AND flg_state = '0' AND flg_city = '0' THEN 3
                WHEN flg_region = '1' AND flg_state = '1' AND flg_city = '0' THEN 4
                WHEN flg_region = '1' AND flg_state = '1' AND flg_city = '1' THEN 5
                ELSE 6
            END
        ", 'ASC', FALSE);

        $records = $this->db->get()->result();

        $slabs = array();
        foreach ($records as $row) {
            $key = $row->c_type . '_' . number_format($row->from_weight, 3, '.', '') . '_' . number_format($row->to_weight, 3, '.', '');
            if (!isset($slabs[$key])) {
                $slabs[$key] = array(
                    'c_type'      => $row->c_type,
                    'from_weight' => $row->from_weight,
                    'to_weight'   => $row->to_weight,
                    'slab_title'  => $row->c_type . ' Weight: ' . number_format($row->from_weight, 3) . ' - ' . number_format($row->to_weight, 3) . ' Kg',
                    'rates'       => array()
                );
            }
            $slabs[$key]['rates'][] = $row;
        }

        return $slabs;
    }

    /**
     * Get single domestic rate row by ID
     */
    public function get_domestic_rate_by_id($id) {
        return $this->db->get_where('crit_domestic_rate_info_v3', array('domestic_rate_id' => intval($id)))->row();
    }

    /**
     * Add new domestic rate record
     */
    public function add_domestic_rate($data) {
        $user_id = $this->session->userdata('user_id');
        $data['created_by'] = $user_id ? $user_id : 1;
        $data['created_datetime'] = date('Y-m-d H:i:s');
        $data['rate_as_on'] = !empty($data['rate_as_on']) ? $data['rate_as_on'] : date('Y-m-d');

        $result = $this->db->insert('crit_domestic_rate_info_v3', $data);
        $insert_id = $this->db->insert_id();

        if ($result && $this->Audit_model) {
            $this->Audit_model->log_activity('Add Domestic Rate', 'ID: ' . $insert_id . ', Mode: ' . ($data['c_type'] ?? '') . ', Range: ' . ($data['from_weight'] ?? '') . '-' . ($data['to_weight'] ?? ''));
        }
        return $insert_id;
    }

    /**
     * Update domestic rate record
     */
    public function update_domestic_rate($id, $data) {
        $user_id = $this->session->userdata('user_id');
        $data['updated_by'] = $user_id ? $user_id : 1;
        $data['update_datetime'] = date('Y-m-d H:i:s');
        $data['rate_as_on'] = date('Y-m-d');

        $this->db->where('domestic_rate_id', intval($id));
        $result = $this->db->update('crit_domestic_rate_info_v3', $data);

        if ($result && $this->Audit_model) {
            $this->Audit_model->log_activity('Update Domestic Rate', 'ID: ' . $id . ', Mode: ' . ($data['c_type'] ?? '') . ', Init Charges: ' . ($data['min_charges'] ?? ''));
        }
        return $result;
    }

    /**
     * Soft delete domestic rate
     */
    public function soft_delete_domestic_rate($id) {
        $rate = $this->get_domestic_rate_by_id($id);
        $this->db->where('domestic_rate_id', intval($id));
        $result = $this->db->update('crit_domestic_rate_info_v3', array(
            'status'          => 'Delete',
            'updated_by'      => $this->session->userdata('user_id'),
            'update_datetime' => date('Y-m-d H:i:s')
        ));

        if ($result && $this->Audit_model) {
            $desc = $rate ? $rate->c_type . ' ' . $rate->from_weight . '-' . $rate->to_weight . 'Kg' : $id;
            $this->Audit_model->log_activity('Soft Delete Domestic Rate', 'Rate ID: ' . $id . ' (' . $desc . ')');
        }
        return $result;
    }

    /**
     * Get Domestic Rate Chart Stats
     */
    public function get_domestic_rate_stats() {
        $sql = "SELECT 
            COUNT(*) as total_rates,
            SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_rates,
            SUM(CASE WHEN c_type = 'Air' AND status != 'Delete' THEN 1 ELSE 0 END) as air_rates,
            SUM(CASE WHEN c_type = 'Surface' AND status != 'Delete' THEN 1 ELSE 0 END) as surface_rates,
            COUNT(DISTINCT CASE WHEN c_type = 'Air' AND status != 'Delete' THEN CONCAT(from_weight, '-', to_weight) END) as air_slabs,
            COUNT(DISTINCT CASE WHEN c_type = 'Surface' AND status != 'Delete' THEN CONCAT(from_weight, '-', to_weight) END) as surface_slabs
        FROM crit_domestic_rate_info_v3
        WHERE status != 'Delete'";
        return $this->db->query($sql)->row();
    }

    /**
     * Calculate Domestic Rate Tariff
     */
    public function calculate_domestic_rate($c_type, $weight, $dest_type) {
        $weight = floatval($weight);
        if ($weight <= 0) {
            return array('status' => 'error', 'message' => 'Please enter a valid weight in Kg.');
        }

        // Destination flag mapping
        $flg_region = '0';
        $flg_state = '0';
        $flg_city = '0';
        $flg_metro = '0';

        switch ($dest_type) {
            case 'city':
                $flg_region = '1';
                $flg_state = '1';
                $flg_city = '1';
                break;
            case 'state':
                $flg_region = '1';
                $flg_state = '1';
                break;
            case 'region':
                $flg_region = '1';
                break;
            case 'metro':
                $flg_metro = '1';
                break;
            default: // 'national' / general
                break;
        }

        // Find matching slab
        $this->db->where('c_type', $c_type);
        $this->db->where('status', 'Active');
        $this->db->where('from_weight <=', $weight);
        $this->db->where('to_weight >=', $weight);
        $this->db->where('flg_region', $flg_region);
        $this->db->where('flg_state', $flg_state);
        $this->db->where('flg_city', $flg_city);
        $this->db->where('flg_metro', $flg_metro);

        $rate = $this->db->get('crit_domestic_rate_info_v3')->row();

        // If not found in specific destination flag, fallback to national (all 0)
        if (!$rate) {
            $this->db->where('c_type', $c_type);
            $this->db->where('status', 'Active');
            $this->db->where('from_weight <=', $weight);
            $this->db->where('to_weight >=', $weight);
            $this->db->where('flg_region', '0');
            $this->db->where('flg_state', '0');
            $this->db->where('flg_city', '0');
            $this->db->where('flg_metro', '0');
            $rate = $this->db->get('crit_domestic_rate_info_v3')->row();
        }

        if (!$rate) {
            return array(
                'status'  => 'error', 
                'message' => 'No active domestic rate configured for ' . $c_type . ' mode at ' . number_format($weight, 3) . ' Kg.'
            );
        }

        // Calculation logic
        $min_weight = floatval($rate->min_weight);
        $min_charges = floatval($rate->min_charges);
        $addt_weight = floatval($rate->addt_weight);
        $addt_charges = floatval($rate->addt_charges);

        $base_charge = $min_charges;
        $extra_charge = 0;
        $extra_steps = 0;
        $excess_weight = 0;

        if ($weight > $min_weight && $addt_weight > 0) {
            $excess_weight = $weight - $min_weight;
            $extra_steps = ceil($excess_weight / $addt_weight);
            $extra_charge = $extra_steps * $addt_charges;
        }

        $total_charge = $base_charge + $extra_charge;

        return array(
            'status'        => 'success',
            'data'          => array(
                'c_type'         => $rate->c_type,
                'weight'         => $weight,
                'from_weight'    => $rate->from_weight,
                'to_weight'      => $rate->to_weight,
                'min_weight'     => $min_weight,
                'min_charges'    => $min_charges,
                'excess_weight'  => $excess_weight,
                'addt_weight'    => $addt_weight,
                'addt_charges'   => $addt_charges,
                'extra_steps'    => $extra_steps,
                'extra_charge'   => $extra_charge,
                'total_charge'   => $total_charge,
                'rate_id'        => $rate->domestic_rate_id
            )
        );
    }

    // =========================================================================
    // 4. PRODUCT CATEGORIES (crit_product_category_info)
    // =========================================================================

    /**
     * Get all product categories or a single one by ID (excluding soft-deleted)
     */
    public function get_product_categories($id = NULL) {
        if ($id === NULL) {
            $this->db->where('status !=', 'Delete');
            $this->db->order_by('category_name', 'ASC');
            return $this->db->get('crit_product_category_info')->result();
        }
        $this->db->where('category_id', intval($id));
        return $this->db->get('crit_product_category_info')->row();
    }

    /**
     * Add new product category
     */
    public function add_product_category($data) {
        $result = $this->db->insert('crit_product_category_info', $data);
        $insert_id = $this->db->insert_id();
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Add Product Category', 'Category: ' . ($data['category_name'] ?? '') . ', ID: ' . $insert_id);
        }
        return $insert_id;
    }

    /**
     * Update product category
     */
    public function update_product_category($id, $data) {
        $this->db->where('category_id', intval($id));
        $result = $this->db->update('crit_product_category_info', $data);
        if ($result && isset($this->Audit_model)) {
            $this->Audit_model->log_activity('Update Product Category', 'Category ID: ' . $id);
        }
        return $result;
    }

    /**
     * Soft delete product category
     */
    public function delete_product_category($id) {
        $cat = $this->get_product_categories($id);
        $this->db->where('category_id', intval($id));
        $result = $this->db->update('crit_product_category_info', array('status' => 'Delete'));
        if ($result && isset($this->Audit_model)) {
            $name = $cat ? $cat->category_name : $id;
            $this->Audit_model->log_activity('Delete Product Category', 'Category: ' . $name . ', ID: ' . $id);
        }
        return $result;
    }

    /**
     * Get category statistics for KPI summary widgets
     */
    public function get_product_category_stats() {
        $sql = "SELECT 
            COUNT(*) as total_categories,
            SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_categories,
            SUM(CASE WHEN status = 'InActive' THEN 1 ELSE 0 END) as inactive_categories,
            SUM(CASE WHEN extra_charge > 0 AND status != 'Delete' THEN 1 ELSE 0 END) as extra_charge_categories
        FROM crit_product_category_info
        WHERE status != 'Delete'";
        return $this->db->query($sql)->row();
    }
}

