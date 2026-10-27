<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domestic extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        // Access Control (Staff and Admins only, block customers)
        if ($this->session->userdata('role_id') == 4) {
            $this->session->set_flashdata('error', 'Access Denied. You do not have permission to access Domestic operations.');
            redirect('dashboard');
        }

        $this->load->model('Domestic_model');
        $this->load->model('Audit_model');
        $this->load->library('form_validation');
    }

    /**
     * Default Domestic redirect
     */
    public function index() {
        redirect('domestic/bookings');
    }

    /**
     * 1. Domestic Booking List
     */
    public function bookings() {
        $data['page_title'] = 'Domestic Booking List';
        $data['view_path'] = 'domestic/booking_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    /**
     * 2. Domestic Rate Chart
     */
    public function rates() {
        $c_type = $this->input->get('c_type');
        if (!in_array($c_type, array('Air', 'Surface'))) {
            $c_type = null;
        }

        $data['page_title']    = 'Domestic Rate Chart';
        $data['selected_type'] = $c_type;
        $data['slabs']         = $this->Domestic_model->get_domestic_rates_grouped($c_type);
        $data['stats']         = $this->Domestic_model->get_domestic_rate_stats();
        $data['view_path']     = 'domestic/rate_chart';
        $this->load->view('templates/dashboard_layout', $data);
    }

    /**
     * 3. Pincode List Master
     */
    public function pincodes() {
        $data['page_title'] = 'Domestic Pincode List';
        $data['states'] = $this->Domestic_model->get_distinct_states();
        $data['zones'] = $this->Domestic_model->get_distinct_zones();
        $data['stats'] = $this->Domestic_model->get_pincode_stats();
        $data['view_path'] = 'domestic/pincode_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    /**
     * Server-side DataTables AJAX Endpoint for Pincodes
     */
    public function pincodes_ajax() {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        if ($length <= 0) {
            $length = 25;
        }

        $search_data = $this->input->post('search');
        $search = isset($search_data['value']) ? trim($search_data['value']) : null;

        // Custom Filters from UI Toolbar
        $state_filter = trim($this->input->post('state_filter'));
        $zone_filter = trim($this->input->post('zone_filter'));
        $metro_filter = trim($this->input->post('metro_filter'));
        $status_filter = trim($this->input->post('status_filter'));

        $order = $this->input->post('order');
        $col_index = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $order_dir = (isset($order[0]['dir']) && strtolower($order[0]['dir']) === 'asc') ? 'ASC' : 'DESC';

        $column_map = array(
            0 => 'pincode_id',
            1 => 'pincode',
            2 => 'area_name',
            3 => 'district_name',
            4 => 'state_name',
            5 => 'zone',
            6 => 'metro',
            7 => 'circle_name',
            8 => 'status',
            9 => 'pincode_id'
        );
        $order_col = isset($column_map[$col_index]) ? $column_map[$col_index] : 'pincode_id';

        $total_records = $this->Domestic_model->count_all_pincodes();
        $filtered_records = $this->Domestic_model->count_filtered_pincodes($search, $state_filter, $zone_filter, $metro_filter, $status_filter);
        $records = $this->Domestic_model->get_pincodes_datatable($length, $start, $search, $order_col, $order_dir, $state_filter, $zone_filter, $metro_filter, $status_filter);

        $data = array();
        $sno = $start + 1;

        foreach ($records as $row) {
            // Pincode Badge
            $pincode_html = '<span class="badge bg-navy" style="font-size: 13px; font-weight: 600; letter-spacing: 0.5px;"><i class="fa fa-map-pin text-yellow"></i> ' . htmlspecialchars($row->pincode) . '</span>';

            // Area Name
            $area_html = '<strong>' . htmlspecialchars($row->area_name ? $row->area_name : '-') . '</strong>';

            // Zone Badge
            $zone_val = strtoupper(trim($row->zone));
            $zone_class = 'bg-gray';
            if ($zone_val === 'S') $zone_class = 'bg-green';
            elseif ($zone_val === 'N') $zone_class = 'bg-aqua';
            elseif ($zone_val === 'W') $zone_class = 'bg-orange';
            elseif ($zone_val === 'E') $zone_class = 'bg-yellow';
            elseif ($zone_val === 'NE') $zone_class = 'bg-purple';
            elseif ($zone_val === 'NW') $zone_class = 'bg-maroon';

            $zone_html = !empty($zone_val) 
                ? '<span class="badge ' . $zone_class . '" style="min-width: 28px;">' . htmlspecialchars($zone_val) . '</span>' 
                : '<span class="text-muted">-</span>';

            // Metro Badge
            $metro_val = strtoupper(trim($row->metro));
            $metro_html = ($metro_val === 'Y') 
                ? '<span class="label label-primary" title="Metro Area"><i class="fa fa-building"></i> Metro (Y)</span>' 
                : '<span class="label label-default" title="Non-Metro Area">Non-Metro (N)</span>';

            // Status Badge
            $status_html = ($row->status === 'Active') 
                ? '<span class="label label-success"><i class="fa fa-check-circle"></i> Active</span>' 
                : '<span class="label label-warning"><i class="fa fa-pause-circle"></i> InActive</span>';

            // Postal Details (Circle, Region, Division)
            $details_parts = array();
            if (!empty($row->circle_name)) $details_parts[] = '<small><i class="fa fa-circle-o text-muted"></i> ' . htmlspecialchars($row->circle_name) . '</small>';
            if (!empty($row->division_name)) $details_parts[] = '<small><i class="fa fa-sitemap text-muted"></i> ' . htmlspecialchars($row->division_name) . '</small>';
            if (!empty($row->region_name) && $row->region_name !== 'NA') $details_parts[] = '<small><i class="fa fa-globe text-muted"></i> ' . htmlspecialchars($row->region_name) . '</small>';
            
            $details_html = !empty($details_parts) ? implode('<br>', $details_parts) : '<span class="text-muted">-</span>';

            // Actions Buttons
            $actions_html = '
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-xs edit-pincode-btn" data-id="' . $row->pincode_id . '" title="Edit Pincode">
                        <i class="fa fa-pencil"></i> Edit
                    </button>
                    <button type="button" class="btn btn-danger btn-xs delete-pincode-btn" data-id="' . $row->pincode_id . '" data-pincode="' . htmlspecialchars($row->pincode) . '" data-area="' . htmlspecialchars($row->area_name ? $row->area_name : '') . '" title="Delete (Mark as Delete)">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                </div>
            ';

            $data[] = array(
                $row->pincode_id,
                $pincode_html,
                $area_html,
                htmlspecialchars($row->district_name ? $row->district_name : '-'),
                htmlspecialchars($row->state_name ? $row->state_name : '-'),
                $zone_html,
                $metro_html,
                $details_html,
                $status_html,
                $actions_html
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $filtered_records,
            "data" => $data
        );

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    /**
     * AJAX endpoint: Get single pincode details for Edit Modal
     */
    public function get_pincode($id) {
        $record = $this->Domestic_model->get_pincode(intval($id));
        if ($record && $record->status !== 'Delete') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success', 'data' => $record)));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Pincode not found or has been deleted.')));
        }
    }

    /**
     * Add New Pincode
     */
    public function add_pincode() {
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|trim');
        $this->form_validation->set_rules('state_name', 'State Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $metro = $this->input->post('metro');
            $status = $this->input->post('status');

            $data = array(
                'pincode'       => intval(trim($this->input->post('pincode'))),
                'state_name'    => trim($this->input->post('state_name')),
                'district_name' => trim($this->input->post('district_name')),
                'circle_name'   => trim($this->input->post('circle_name')),
                'region_name'   => trim($this->input->post('region_name')),
                'division_name' => trim($this->input->post('division_name')),
                'area_name'     => trim($this->input->post('area_name')),
                'zone'          => strtoupper(trim($this->input->post('zone'))),
                'metro'         => ($metro === 'Y') ? 'Y' : 'N',
                'status'        => ($status === 'InActive') ? 'InActive' : 'Active'
            );

            $new_id = $this->Domestic_model->add_pincode($data);
            if ($new_id) {
                $this->session->set_flashdata('success', 'Pincode ' . $data['pincode'] . ' added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add pincode. Please try again.');
            }
        }
        redirect('domestic/pincodes');
    }

    /**
     * Edit Existing Pincode
     */
    public function edit_pincode($id) {
        $id = intval($id);
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|numeric|trim');
        $this->form_validation->set_rules('state_name', 'State Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $metro = $this->input->post('metro');
            $status = $this->input->post('status');

            $data = array(
                'pincode'       => intval(trim($this->input->post('pincode'))),
                'state_name'    => trim($this->input->post('state_name')),
                'district_name' => trim($this->input->post('district_name')),
                'circle_name'   => trim($this->input->post('circle_name')),
                'region_name'   => trim($this->input->post('region_name')),
                'division_name' => trim($this->input->post('division_name')),
                'area_name'     => trim($this->input->post('area_name')),
                'zone'          => strtoupper(trim($this->input->post('zone'))),
                'metro'         => ($metro === 'Y') ? 'Y' : 'N',
                'status'        => in_array($status, array('Active', 'InActive')) ? $status : 'Active'
            );

            $this->Domestic_model->update_pincode($id, $data);
            $this->session->set_flashdata('success', 'Pincode ' . $data['pincode'] . ' updated successfully.');
        }
        redirect('domestic/pincodes');
    }

    /**
     * Soft Delete Pincode (Sets status to 'Delete')
     * Supports both AJAX and regular HTTP POST/GET requests
     */
    public function delete_pincode($id) {
        $id = intval($id);
        $record = $this->Domestic_model->get_pincode($id);

        if (!$record) {
            if ($this->input->is_ajax_request()) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('status' => 'error', 'message' => 'Record not found.')));
            }
            $this->session->set_flashdata('error', 'Pincode not found.');
            redirect('domestic/pincodes');
        }

        $this->Domestic_model->soft_delete_pincode($id);

        if ($this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'success', 
                    'message' => 'Pincode ' . $record->pincode . ' has been marked as Deleted successfully.'
                )));
        }

        $this->session->set_flashdata('success', 'Pincode ' . $record->pincode . ' has been marked as Deleted.');
        redirect('domestic/pincodes');
    }

    /**
     * Export Pincodes as CSV (High performance for large dataset)
     */
    public function export_pincodes() {
        $state = $this->input->get('state');
        $zone = $this->input->get('zone');
        $metro = $this->input->get('metro');
        $status = $this->input->get('status');

        $records = $this->Domestic_model->get_all_pincodes_for_export($state, $zone, $metro, $status, 50000);

        $filename = 'domestic_pincodes_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, array('Pincode ID', 'Pincode', 'Area Name', 'District', 'State', 'Zone', 'Metro', 'Circle', 'Region', 'Division', 'Status'));

        foreach ($records as $r) {
            fputcsv($output, array(
                $r->pincode_id,
                $r->pincode,
                $r->area_name,
                $r->district_name,
                $r->state_name,
                $r->zone,
                $r->metro,
                $r->circle_name,
                $r->region_name,
                $r->division_name,
                $r->status
            ));
        }

        fclose($output);
        exit;
    }

    /**
     * AJAX: Get Domestic Rate details for Edit Modal
     */
    public function get_rate($id) {
        $id = intval($id);
        $record = $this->Domestic_model->get_domestic_rate_by_id($id);

        if ($record && $record->status !== 'Delete') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success', 'data' => $record)));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Domestic rate record not found.')));
        }
    }

    /**
     * Add Domestic Rate Record
     */
    public function add_rate() {
        $this->form_validation->set_rules('c_type', 'Courier Type', 'required|trim');
        $this->form_validation->set_rules('from_weight', 'Min Weight', 'required|numeric');
        $this->form_validation->set_rules('to_weight', 'Max Weight', 'required|numeric');
        $this->form_validation->set_rules('min_weight', 'Initial Weight', 'required|numeric');
        $this->form_validation->set_rules('min_charges', 'Initial Charges', 'required|numeric');
        $this->form_validation->set_rules('addt_weight', 'Additional Weight Step', 'required|numeric');
        $this->form_validation->set_rules('addt_charges', 'Additional Charges', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $c_type = $this->input->post('c_type');
            if (!in_array($c_type, array('Air', 'Surface'))) {
                $c_type = 'Air';
            }

            $data = array(
                'c_type'        => $c_type,
                'from_weight'   => floatval($this->input->post('from_weight')),
                'to_weight'     => floatval($this->input->post('to_weight')),
                'flg_region'    => $this->input->post('flg_region') ? '1' : '0',
                'flg_state'     => $this->input->post('flg_state') ? '1' : '0',
                'flg_city'      => $this->input->post('flg_city') ? '1' : '0',
                'flg_metro'     => $this->input->post('flg_metro') ? '1' : '0',
                'min_weight'    => floatval($this->input->post('min_weight')),
                'min_charges'   => floatval($this->input->post('min_charges')),
                'addt_weight'   => floatval($this->input->post('addt_weight')),
                'addt_charges'  => floatval($this->input->post('addt_charges')),
                'status'        => 'Active',
                'rate_as_on'    => date('Y-m-d')
            );

            $insert_id = $this->Domestic_model->add_domestic_rate($data);
            if ($insert_id) {
                $this->session->set_flashdata('success', 'Domestic Rate for ' . $c_type . ' (' . number_format($data['from_weight'], 3) . ' - ' . number_format($data['to_weight'], 3) . ' Kg) added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to add domestic rate. Please try again.');
            }
        }

        redirect('domestic/rates');
    }

    /**
     * Edit Domestic Rate Record
     */
    public function edit_rate($id) {
        $id = intval($id);
        $this->form_validation->set_rules('c_type', 'Courier Type', 'required|trim');
        $this->form_validation->set_rules('from_weight', 'Min Weight', 'required|numeric');
        $this->form_validation->set_rules('to_weight', 'Max Weight', 'required|numeric');
        $this->form_validation->set_rules('min_weight', 'Initial Weight', 'required|numeric');
        $this->form_validation->set_rules('min_charges', 'Initial Charges', 'required|numeric');
        $this->form_validation->set_rules('addt_weight', 'Additional Weight Step', 'required|numeric');
        $this->form_validation->set_rules('addt_charges', 'Additional Charges', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $c_type = $this->input->post('c_type');
            if (!in_array($c_type, array('Air', 'Surface'))) {
                $c_type = 'Air';
            }

            $status = $this->input->post('status');
            if (!in_array($status, array('Active', 'InActive'))) {
                $status = 'Active';
            }

            $data = array(
                'c_type'        => $c_type,
                'from_weight'   => floatval($this->input->post('from_weight')),
                'to_weight'     => floatval($this->input->post('to_weight')),
                'flg_region'    => $this->input->post('flg_region') ? '1' : '0',
                'flg_state'     => $this->input->post('flg_state') ? '1' : '0',
                'flg_city'      => $this->input->post('flg_city') ? '1' : '0',
                'flg_metro'     => $this->input->post('flg_metro') ? '1' : '0',
                'min_weight'    => floatval($this->input->post('min_weight')),
                'min_charges'   => floatval($this->input->post('min_charges')),
                'addt_weight'   => floatval($this->input->post('addt_weight')),
                'addt_charges'  => floatval($this->input->post('addt_charges')),
                'status'        => $status,
                'rate_as_on'    => date('Y-m-d')
            );

            $this->Domestic_model->update_domestic_rate($id, $data);
            $this->session->set_flashdata('success', 'Domestic Rate updated successfully.');
        }

        redirect('domestic/rates');
    }

    /**
     * Delete Domestic Rate (Soft delete)
     */
    public function delete_rate($id) {
        $id = intval($id);
        $this->Domestic_model->soft_delete_domestic_rate($id);

        if ($this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success', 'message' => 'Rate marked as Deleted.')));
        }

        $this->session->set_flashdata('success', 'Domestic Rate marked as Deleted.');
        redirect('domestic/rates');
    }

    /**
     * AJAX Domestic Rate Calculator
     */
    public function calculate_rate_ajax() {
        $c_type = $this->input->post('c_type');
        $weight = floatval($this->input->post('weight'));
        $dest_type = $this->input->post('dest_type');

        $result = $this->Domestic_model->calculate_domestic_rate($c_type, $weight, $dest_type);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    // =========================================================================
    // 4. PRODUCT CATEGORIES (crit_product_category_info)
    // =========================================================================

    /**
     * Product Category Directory Page
     */
    public function categories() {
        $data['page_title'] = 'Product Category Master';
        $data['categories'] = $this->Domestic_model->get_product_categories();
        $data['stats']      = $this->Domestic_model->get_product_category_stats();
        $data['view_path']  = 'domestic/product_category_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    /**
     * Fetch Single Product Category via AJAX (for Edit modal)
     */
    public function get_category($id) {
        $category = $this->Domestic_model->get_product_categories(intval($id));
        if ($category && $category->status !== 'Delete') {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success', 'data' => $category)));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Product category not found.')));
        }
    }

    /**
     * Add New Product Category
     */
    public function add_category() {
        $this->form_validation->set_rules('category_name', 'Category Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $status = $this->input->post('status');
            $data = array(
                'category_name' => trim($this->input->post('category_name')),
                'extra_charge'  => floatval($this->input->post('extra_charge')),
                'status'        => in_array($status, array('Active', 'InActive')) ? $status : 'Active'
            );
            $this->Domestic_model->add_product_category($data);
            $this->session->set_flashdata('success', 'Product Category added successfully.');
        }
        redirect('domestic/categories');
    }

    /**
     * Edit Product Category
     */
    public function edit_category($id) {
        $id = intval($id);
        $this->form_validation->set_rules('category_name', 'Category Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $status = $this->input->post('status');
            $data = array(
                'category_name' => trim($this->input->post('category_name')),
                'extra_charge'  => floatval($this->input->post('extra_charge')),
                'status'        => in_array($status, array('Active', 'InActive')) ? $status : 'Active'
            );
            $this->Domestic_model->update_product_category($id, $data);
            $this->session->set_flashdata('success', 'Product Category updated successfully.');
        }
        redirect('domestic/categories');
    }

    /**
     * Soft Delete Product Category
     */
    public function delete_category($id) {
        $id = intval($id);
        $this->Domestic_model->delete_product_category($id);

        if ($this->input->is_ajax_request()) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success', 'message' => 'Product Category marked as Deleted.')));
        }

        $this->session->set_flashdata('success', 'Product Category marked as Deleted successfully.');
        redirect('domestic/categories');
    }
}
