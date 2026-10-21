<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masters extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        // Access Control (Only Super Admin can view master settings)
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Access Denied. Only Super Admin can access Master Settings.');
            redirect('dashboard');
        }
        $this->load->model('Master_model');
        $this->load->model('Audit_model');
    }
    // --- CHARGE TYPES ---
    public function charge_types() {
        $data['page_title'] = 'Billing Charge Types';
        $data['charge_types'] = $this->Master_model->get_charge_types();
        $data['view_path'] = 'masters/charge_types_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_charge_type() {
        $this->form_validation->set_rules('charge_name', 'Charge Name', 'required|is_unique[master_charge_types.charge_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'charge_name' => $this->input->post('charge_name'),
                'status' => $this->input->post('status')
            );
            $this->Master_model->add_charge_type($data);
            $this->session->set_flashdata('success', 'Charge Type added successfully.');
        }
        redirect('charge-types');
    }

    public function edit_charge_type($id) {
        $original = $this->Master_model->get_charge_types($id);
        $is_unique = '';
        if ($this->input->post('charge_name') != $original->charge_name) {
            $is_unique = '|is_unique[master_charge_types.charge_name]';
        }
        $this->form_validation->set_rules('charge_name', 'Charge Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'charge_name' => $this->input->post('charge_name'),
                'status' => $this->input->post('status')
            );
            $this->Master_model->update_charge_type($id, $data);
            $this->session->set_flashdata('success', 'Charge Type updated successfully.');
        }
        redirect('charge-types');
    }

    public function delete_charge_type($id) {
        $this->Master_model->delete_charge_type($id);
        $this->session->set_flashdata('success', 'Charge Type deleted successfully.');
        redirect('charge-types');
    }

    // --- BRANCHES ---
    public function branches() {
        $data['page_title'] = 'Manage Branches';
        $data['branches'] = $this->Master_model->get_branches();
        
        // Fetch roles to display in the create user modal
        $all_roles = $this->Master_model->get_roles();
        $filtered_roles = array();
        foreach ($all_roles as $r) {
            // Exclude Super Admin (1), Franchise User (3), and Customer (4)
            if ($r->id != 1 && $r->id != 3 && $r->id != 4) {
                $filtered_roles[] = $r;
            }
        }
        $data['roles'] = $filtered_roles;
        
        $data['view_path'] = 'masters/branches_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_branch() {
        $this->form_validation->set_rules('name', 'Branch Name', 'required');
        $this->form_validation->set_rules('branch_code', 'Branch Code', 'required|is_unique[branches.branch_code]');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add New Branch';
            $data['view_path'] = 'masters/branch_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_branch($post);
            $this->session->set_flashdata('success', 'Branch created successfully.');
            redirect('branches');
        }
    }

    public function edit_branch($id) {
        $this->form_validation->set_rules('name', 'Branch Name', 'required');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Branch';
            $data['branch'] = $this->Master_model->get_branches($id);
            $data['view_path'] = 'masters/branch_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_branch($id, $post);
            $this->session->set_flashdata('success', 'Branch details updated.');
            redirect('branches');
        }
    }

    public function delete_branch($id) {
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Only Super Admin can delete records.');
            redirect('branches');
        }
        $this->Master_model->delete_branch($id);
        $this->session->set_flashdata('success', 'Branch deleted.');
        redirect('branches');
    }

    public function create_branch_user() {
        $this->form_validation->set_rules('branch_id', 'Branch', 'required|numeric');
        $this->form_validation->set_rules('role_id', 'Role', 'required|numeric');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            $user_data = array(
                'username' => $post['username'],
                'email' => $post['email'],
                'password' => $post['password'],
                'role_id' => $post['role_id'],
                'branch_id' => $post['branch_id']
            );
            
            if ($this->Master_model->add_branch_user($user_data)) {
                $this->session->set_flashdata('success', 'Branch user created successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to create branch user.');
            }
        }
        redirect('branches');
    }
    public function branch_users($branch_id) {
        $branch = $this->Master_model->get_branches($branch_id);
        if (!$branch) {
            $this->session->set_flashdata('error', 'Branch not found.');
            redirect('branches');
        }

        $data['page_title'] = 'Users for Branch: ' . $branch->name;
        $data['branch'] = $branch;
        $data['users'] = $this->Master_model->get_branch_users($branch_id);
        
        $all_roles = $this->Master_model->get_roles();
        $filtered_roles = array();
        foreach ($all_roles as $r) {
            if ($r->id != 1 && $r->id != 3 && $r->id != 4) {
                $filtered_roles[] = $r;
            }
        }
        $data['roles'] = $filtered_roles;

        $data['view_path'] = 'masters/branch_users_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function edit_branch_user($user_id) {
        $user = $this->Master_model->get_user($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('branches');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[4]');
        $this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }
        $this->form_validation->set_rules('role_id', 'Role', 'required|numeric');
        $this->form_validation->set_rules('status', 'Status', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            // Check unique active only if changed
            if ($post['email'] !== $user->email) {
                $exists = $this->db->get_where('users', array('email' => $post['email'], 'deleted_at IS NULL' => null))->row();
                if ($exists) {
                    $this->session->set_flashdata('error', 'The Email Address is already in use.');
                    redirect('branches/users/' . $user->branch_id);
                }
            }
            if ($post['username'] !== $user->username) {
                $exists = $this->db->get_where('users', array('username' => $post['username'], 'deleted_at IS NULL' => null))->row();
                if ($exists) {
                    $this->session->set_flashdata('error', 'The Username is already in use.');
                    redirect('branches/users/' . $user->branch_id);
                }
            }

            $update_data = array(
                'username' => $post['username'],
                'email' => $post['email'],
                'role_id' => $post['role_id'],
                'status' => $post['status']
            );
            if (!empty($post['password'])) {
                $update_data['password'] = $post['password'];
            }

            if ($this->Master_model->update_user($user_id, $update_data)) {
                $this->session->set_flashdata('success', 'User updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update user.');
            }
        }
        redirect('branches/users/' . $user->branch_id);
    }
    public function delete_branch_user($user_id) {
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('branches');
        }

        $user = $this->Master_model->get_user($user_id);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('branches');
        }

        if ($this->Master_model->delete_user($user_id)) {
            $this->session->set_flashdata('success', 'User deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user.');
        }
        redirect('branches/users/' . $user->branch_id);
    }

    // --- FRANCHISES ---
    public function franchises() {
        $data['page_title'] = 'Manage Franchises';
        $data['franchises'] = $this->Master_model->get_franchises();
        $data['view_path'] = 'masters/franchises_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_franchise() {
        $this->form_validation->set_rules('name', 'Franchise Name', 'required');
        $this->form_validation->set_rules('franchise_code', 'Franchise Code', 'required|is_unique[franchises.franchise_code]');
        $this->form_validation->set_rules('email', 'Login Email', 'required|valid_email|is_unique_active[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('deposit_amount', 'Deposit Amount', 'numeric');
        $this->form_validation->set_rules('revenue_sharing_percentage', 'Revenue Split', 'numeric');
        $this->form_validation->set_rules('commission_percentage', 'Commission', 'numeric');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Register New Franchise';
            $data['view_path'] = 'masters/franchise_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            $user_data = array(
                'username' => $post['name'],
                'email' => $post['email'],
                'password' => $post['password'],
                'branch_id' => 1 // Headquarters by default
            );

            $franchise_data = array(
                'name' => $post['name'],
                'franchise_code' => $post['franchise_code'],
                'deposit_amount' => $post['deposit_amount'],
                'agreement_date' => $post['agreement_date'],
                'revenue_sharing_percentage' => $post['revenue_sharing_percentage'],
                'commission_percentage' => $post['commission_percentage'],
                'status' => 'Active'
            );

            $this->Master_model->add_franchise($franchise_data, $user_data);
            $this->session->set_flashdata('success', 'Franchise added successfully.');
            redirect('franchises');
        }
    }

    public function edit_franchise($id) {
        $franchise = $this->Master_model->get_franchises($id);
        if (!$franchise) {
            $this->session->set_flashdata('error', 'Franchise not found.');
            redirect('franchises');
        }

        $this->form_validation->set_rules('name', 'Franchise Name', 'required');
        $this->form_validation->set_rules('deposit_amount', 'Deposit Amount', 'numeric');
        $this->form_validation->set_rules('revenue_sharing_percentage', 'Revenue Split', 'numeric');
        $this->form_validation->set_rules('commission_percentage', 'Commission', 'numeric');
        $this->form_validation->set_rules('email', 'Login Email', 'required|valid_email');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('franchises');
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            if ($post['email'] !== $franchise->user_email) {
                $exists = $this->db->get_where('users', array('email' => $post['email'], 'deleted_at IS NULL' => null))->row();
                if ($exists) {
                    $this->session->set_flashdata('error', 'The Login Email is already in use.');
                    redirect('franchises');
                }
            }

            $franchise_data = array(
                'name' => $post['name'],
                'deposit_amount' => $post['deposit_amount'],
                'agreement_date' => $post['agreement_date'],
                'revenue_sharing_percentage' => $post['revenue_sharing_percentage'],
                'commission_percentage' => $post['commission_percentage'],
                'status' => $post['status']
            );

            $user_data = array(
                'email' => $post['email'],
                'status' => $post['status']
            );
            if (!empty($post['password'])) {
                $user_data['password'] = $post['password'];
            }

            $this->Master_model->update_franchise($id, $franchise_data, $user_data);
            $this->session->set_flashdata('success', 'Franchise details updated.');
            redirect('franchises');
        }
    }

    public function delete_franchise($id) {
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Only Super Admin can delete records.');
            redirect('franchises');
        }
        $this->Master_model->delete_franchise($id);
        $this->session->set_flashdata('success', 'Franchise profile removed.');
        redirect('franchises');
    }

    // --- COUNTRIES ---
    public function countries() {
        $data['page_title'] = 'Country Master';
        $data['countries'] = $this->Master_model->get_countries();
        $data['view_path'] = 'masters/countries_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_country() {
        $this->form_validation->set_rules('country_name', 'Country Name', 'required|is_unique[countries.country_name]');
        $this->form_validation->set_rules('iso_code', 'ISO Code', 'required|exact_length[3]|is_unique[countries.iso_code]');
        $this->form_validation->set_rules('country_code', 'Country dialing Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add New Country';
            $data['view_path'] = 'masters/country_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $post['customs_required'] = isset($post['customs_required']) ? 1 : 0;
            $this->Master_model->add_country($post);
            $this->session->set_flashdata('success', 'Country added.');
            redirect('countries');
        }
    }

    public function edit_country($id) {
        $this->form_validation->set_rules('country_name', 'Country Name', 'required');
        $this->form_validation->set_rules('country_code', 'Country dialing Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Country';
            $data['country'] = $this->Master_model->get_countries($id);
            $data['view_path'] = 'masters/country_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $post['customs_required'] = isset($post['customs_required']) ? 1 : 0;
            $this->Master_model->update_country($id, $post);
            $this->session->set_flashdata('success', 'Country updated.');
            redirect('countries');
        }
    }

    // --- COURIER PARTNERS ---
    public function partners() {
        $data['page_title'] = 'Courier Partners';
        $data['partners'] = $this->Master_model->get_courier_partners();
        $data['view_path'] = 'masters/partners_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_partner() {
        $this->form_validation->set_rules('partner_name', 'Partner Name', 'required|is_unique[courier_partners.partner_name]');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Add Courier Partner';
            $data['view_path'] = 'masters/partner_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_courier_partner($post);
            $this->session->set_flashdata('success', 'Courier partner added.');
            redirect('partners');
        }
    }

    public function edit_partner($id) {
        $this->form_validation->set_rules('partner_name', 'Partner Name', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Courier Partner';
            $data['partner'] = $this->Master_model->get_courier_partners($id);
            $data['view_path'] = 'masters/partner_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_courier_partner($id, $post);
            $this->session->set_flashdata('success', 'Courier partner updated.');
            redirect('partners');
        }
    }

    // =========================================================================
    // --- SHIPPING RATES MATRIX V2 ---
    // Fields: Destination Country, Service Type, Shipment Type, Courier Partner, Weight, Rate, Status
    // =========================================================================

    public function rates() {
        $filters = array(
            'destination_country_id' => $this->input->post_get('dest_country'),
            'courier_partner_id'     => $this->input->post_get('partner'),
            'service_type'           => $this->input->post_get('service_type'),
            'shipment_type'          => $this->input->post_get('shipment_type'),
            'status'                 => $this->input->post_get('status')
        );

        $data['page_title']       = 'Shipping Rates Matrix v2';
        $data['countries']        = $this->Master_model->get_countries();
        $data['courier_partners'] = $this->Master_model->get_courier_partners();
        $data['service_types']    = $this->Master_model->get_service_types();
        $data['rates']            = $this->Master_model->get_rates_v2($filters);
        $data['filters']          = $filters;

        // Statistics
        $all_v2 = $this->Master_model->get_rates_v2();
        $data['total_count']     = count($all_v2);
        $data['active_count']    = count(array_filter($all_v2, function($r) { return $r->status === 'Active'; }));
        $unique_countries        = array_unique(array_column($all_v2, 'destination_country_id'));
        $data['countries_count'] = count($unique_countries);
        $unique_partners         = array_unique(array_column($all_v2, 'courier_partner_id'));
        $data['partners_count']  = count($unique_partners);

        $data['view_path'] = 'masters/rates_list_v2';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_rate() {
        $this->form_validation->set_rules('destination_country_id', 'Destination Country', 'required|numeric');
        $this->form_validation->set_rules('courier_partner_id', 'Courier Partner', 'required|numeric');
        $this->form_validation->set_rules('service_type', 'Service Type', 'required|trim');
        $this->form_validation->set_rules('shipment_type', 'Shipment Type', 'required|trim');
        $this->form_validation->set_rules('weight', 'Weight (kg)', 'required|numeric');
        $this->form_validation->set_rules('rate', 'Rate (INR)', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_rate_v2($post);
            $this->session->set_flashdata('success', 'New shipping rate added successfully.');
        }
        redirect('rates');
    }

    public function edit_rate($id = NULL) {
        if (!$id) {
            $id = $this->input->post('id');
        }

        $this->form_validation->set_rules('destination_country_id', 'Destination Country', 'required|numeric');
        $this->form_validation->set_rules('courier_partner_id', 'Courier Partner', 'required|numeric');
        $this->form_validation->set_rules('service_type', 'Service Type', 'required|trim');
        $this->form_validation->set_rules('shipment_type', 'Shipment Type', 'required|trim');
        $this->form_validation->set_rules('weight', 'Weight (kg)', 'required|numeric');
        $this->form_validation->set_rules('rate', 'Rate (INR)', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_rate_v2($id, $post);
            $this->session->set_flashdata('success', 'Shipping rate updated successfully.');
        }
        redirect('rates');
    }

    public function delete_rate($id) {
        $this->Master_model->delete_rate_v2($id);
        $this->session->set_flashdata('success', 'Shipping rate deleted.');
        redirect('rates');
    }

    public function get_rate_json($id) {
        $rate = $this->Master_model->get_rate_v2($id);
        if ($rate) {
            echo json_encode(array('status' => 'success', 'data' => $rate));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Rate not found.'));
        }
        exit;
    }

    public function toggle_rate_status($id) {
        $new_status = $this->Master_model->toggle_rate_status_v2($id);
        if ($new_status) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'success', 'new_status' => $new_status));
                exit;
            }
            $this->session->set_flashdata('success', "Rate status updated to {$new_status}.");
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to update status.'));
                exit;
            }
            $this->session->set_flashdata('error', 'Failed to toggle rate status.');
        }
        redirect('rates');
    }

    public function export_rates() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        require_once APPPATH . 'third_party/PHPExcel.php';

        $filters = array(
            'destination_country_id' => $this->input->post_get('dest_country'),
            'courier_partner_id'     => $this->input->post_get('partner'),
            'service_type'           => $this->input->post_get('service_type'),
            'shipment_type'          => $this->input->post_get('shipment_type'),
            'status'                 => $this->input->post_get('status')
        );

        $records = $this->Master_model->get_all_rates_for_export_v2($filters);

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Courier Syndicate")
                                     ->setTitle("Shipping Rates Matrix v2 Export");

        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Rates Matrix v2');

        $headers = array(
            'A1' => 'Destination Country',
            'B1' => 'Service Type',
            'C1' => 'Shipment Type',
            'D1' => 'Courier Partner',
            'E1' => 'Weight (kg)',
            'F1' => 'Rate (INR)',
            'G1' => 'Status'
        );

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Header style
        $headerStyle = array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3C8DBC')
            ),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        $row_num = 2;
        foreach ($records as $r) {
            $sheet->setCellValueExplicit('A' . $row_num, $r['destination_country'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $row_num, $r['service_type'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row_num, $r['shipment_type'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row_num, $r['courier_partner_name'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $row_num, number_format($r['weight'], 3, '.', ''), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('F' . $row_num, number_format($r['rate'], 2, '.', ''), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('G' . $row_num, $r['status'], PHPExcel_Cell_DataType::TYPE_STRING);
            $row_num++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'shipping_rates_v2_' . date('Y-m-d_His') . '.xls';
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function download_rates_template() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

        require_once APPPATH . 'third_party/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Rate Import Template');

        $headers = array(
            'A1' => 'Destination Country',
            'B1' => 'Service Type',
            'C1' => 'Shipment Type',
            'D1' => 'Courier Partner',
            'E1' => 'Weight (kg)',
            'F1' => 'Rate (INR)',
            'G1' => 'Status'
        );

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Header style
        $headerStyle = array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '2E7D32')
            ),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

        // Sample dummy data
        $sample_rows = array(
            array('United States', 'Express', 'Documents (Paper / Files)', 'DHL Express', '0.500', '1450.00', 'Active'),
            array('United States', 'Express', 'Documents (Paper / Files)', 'DHL Express', '1.000', '2150.00', 'Active'),
            array('United States', 'Economy', 'Non-Documents (Commercial Goods / Parcels)', 'FedEx', '1.000', '2200.00', 'Active'),
            array('United Kingdom', 'Express', 'Documents (Paper / Files)', 'DHL Express', '0.500', '1350.00', 'Active'),
            array('United Arab Emirates', 'Express', 'Non-Documents (Commercial Goods / Parcels)', 'Aramex', '1.000', '1750.00', 'Active')
        );

        $row_idx = 2;
        foreach ($sample_rows as $row) {
            $sheet->setCellValueExplicit('A' . $row_idx, $row[0], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $row_idx, $row[1], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row_idx, $row[2], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $row_idx, $row[3], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $row_idx, $row[4], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('F' . $row_idx, $row[5], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('G' . $row_idx, $row[6], PHPExcel_Cell_DataType::TYPE_STRING);
            $row_idx++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'sample_shipping_rates_v2_template.xls';
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function import_rates() {
        if (empty($_FILES['excel_file']['name'])) {
            $this->session->set_flashdata('error', 'Please choose an Excel (.xls / .xlsx) file to upload.');
            redirect('rates');
        }

        $strategy = $this->input->post('strategy') ? $this->input->post('strategy') : 'update';

        $ext = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, array('xls', 'xlsx', 'csv'))) {
            $this->session->set_flashdata('error', 'Invalid file type. Please upload .xls, .xlsx, or .csv');
            redirect('rates');
        }

        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
        require_once APPPATH . 'third_party/PHPExcel.php';

        try {
            $file_path = $_FILES['excel_file']['tmp_name'];
            $inputFileType = PHPExcel_IOFactory::identify($file_path);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($file_path);
            $sheet = $objPHPExcel->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            $records = array();
            for ($r = 2; $r <= $highestRow; $r++) {
                $country = trim((string)$sheet->getCell('A' . $r)->getValue());
                $service = trim((string)$sheet->getCell('B' . $r)->getValue());
                $shipment = trim((string)$sheet->getCell('C' . $r)->getValue());
                $partner = trim((string)$sheet->getCell('D' . $r)->getValue());
                $weight  = trim((string)$sheet->getCell('E' . $r)->getValue());
                $rate    = trim((string)$sheet->getCell('F' . $r)->getValue());
                $status  = trim((string)$sheet->getCell('G' . $r)->getValue());

                if (empty($country) && empty($partner) && empty($weight)) {
                    continue;
                }

                $records[] = array(
                    'destination_country' => $country,
                    'service_type'        => $service,
                    'shipment_type'       => $shipment,
                    'courier_partner'     => $partner,
                    'weight'              => $weight,
                    'rate'                => $rate,
                    'status'              => $status
                );
            }

            if (empty($records)) {
                $this->session->set_flashdata('error', 'No valid data rows found in the uploaded file.');
                redirect('rates');
            }

            $result = $this->Master_model->bulk_import_rates_v2($records, $strategy);

            $msg = "Import Complete: {$result['inserted']} new rates added, {$result['updated']} rates updated, {$result['skipped']} skipped.";
            if (!empty($result['errors'])) {
                $msg .= "<br><strong>Warnings/Errors:</strong><br>" . implode("<br>", array_slice($result['errors'], 0, 10));
                if (count($result['errors']) > 10) {
                    $msg .= "<br>...and " . (count($result['errors']) - 10) . " more errors.";
                }
            }

            $this->session->set_flashdata('success', $msg);
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error reading Excel file: ' . $e->getMessage());
        }

        redirect('rates');
    }

    // --- TERMS & CONDITIONS ---
    public function terms() {
        $data['page_title'] = 'Terms & Conditions Versions';
        $data['terms'] = $this->Master_model->get_terms();
        $data['view_path'] = 'masters/terms_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_terms() {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('version_number', 'Version Number', 'required');
        $this->form_validation->set_rules('effective_date', 'Effective Date', 'required');
        $this->form_validation->set_rules('terms_content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Create Terms Version';
            $data['view_path'] = 'masters/terms_add';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->add_terms($post);
            $this->session->set_flashdata('success', 'Terms & Conditions version added.');
            redirect('terms');
        }
    }

    public function edit_terms($id) {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('terms_content', 'Content', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Terms Version';
            $data['terms'] = $this->Master_model->get_terms($id);
            $data['view_path'] = 'masters/terms_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $this->Master_model->update_terms($id, $post);
            $this->session->set_flashdata('success', 'Terms & Conditions version updated.');
            redirect('terms');
        }
    }

    // --- RESTRICTED ITEMS LIST ---
    public function restricted_items() {
        $data['page_title'] = 'Restricted Items Directory';
        $data['countries'] = $this->Master_model->get_countries();
        $data['view_path'] = 'masters/restricted_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_restricted_item() {
        $this->form_validation->set_rules('country_id', 'Country', 'required|numeric');
        $this->form_validation->set_rules('item', 'Restricted Item', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $country_id = $this->input->post('country_id');
            $new_item = trim($this->input->post('item'));
            
            $country = $this->Master_model->get_countries($country_id);
            if ($country) {
                $current_items = trim($country->restricted_items);
                if (empty($current_items)) {
                    $updated_items = $new_item;
                } else {
                    // Check if item is already in the list
                    $items_arr = array_map('trim', explode(',', $current_items));
                    if (!in_array($new_item, $items_arr)) {
                        $updated_items = $current_items . ', ' . $new_item;
                    } else {
                        $updated_items = $current_items;
                    }
                }
                
                $this->Master_model->update_country($country_id, array('restricted_items' => $updated_items));
                $this->session->set_flashdata('success', 'Restricted item added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Country not found.');
            }
        }
        redirect('restricted-items');
    }

    public function edit_restricted_items($id) {
        $this->form_validation->set_rules('restricted_items', 'Restricted Items', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $items = $this->input->post('restricted_items');
            // Clean up comma formatting: trim all items
            if (!empty($items)) {
                $items_arr = array_map('trim', explode(',', $items));
                // Filter out empty items
                $items_arr = array_filter($items_arr, function($value) { return $value !== ''; });
                $cleaned_items = implode(', ', $items_arr);
            } else {
                $cleaned_items = NULL;
            }
            
            $this->Master_model->update_country($id, array('restricted_items' => $cleaned_items));
            $this->session->set_flashdata('success', 'Restricted items list updated successfully.');
        }
        redirect('restricted-items');
    }

    public function delete_restricted_item($country_id, $item_name) {
        $item_to_delete = trim(urldecode($item_name));
        $country = $this->Master_model->get_countries($country_id);
        
        if ($country) {
            $current_items = trim($country->restricted_items);
            if (!empty($current_items)) {
                $items_arr = array_map('trim', explode(',', $current_items));
                // Find and remove the item
                $new_items_arr = array_filter($items_arr, function($value) use ($item_to_delete) {
                    return strcasecmp($value, $item_to_delete) !== 0;
                });
                
                $updated_items = !empty($new_items_arr) ? implode(', ', $new_items_arr) : NULL;
                $this->Master_model->update_country($country_id, array('restricted_items' => $updated_items));
                $this->session->set_flashdata('success', 'Restricted item deleted successfully.');
            }
        } else {
            $this->session->set_flashdata('error', 'Country not found.');
        }
        redirect('restricted-items');
    }

    // --- MOVEMENT STAGES ---
    public function movement_stages() {
        $data['page_title'] = 'Movement Status Stages';
        $data['stages'] = $this->Master_model->get_movement_stages();
        $data['view_path'] = 'masters/movement_stages_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_movement_stage() {
        $this->form_validation->set_rules('stage_name', 'Stage Name', 'required|is_unique[movement_stages.stage_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'stage_name' => $this->input->post('stage_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->add_movement_stage($data);
            $this->session->set_flashdata('success', 'Movement status stage added successfully.');
        }
        redirect('movement-stages');
    }

    public function edit_movement_stage($id) {
        $original = $this->Master_model->get_movement_stages($id);
        $is_unique = '';
        if ($this->input->post('stage_name') != $original->stage_name) {
            $is_unique = '|is_unique[movement_stages.stage_name]';
        }
        $this->form_validation->set_rules('stage_name', 'Stage Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'stage_name' => $this->input->post('stage_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->update_movement_stage($id, $data);
            $this->session->set_flashdata('success', 'Movement status stage updated successfully.');
        }
        redirect('movement-stages');
    }

    public function delete_movement_stage($id) {
        $this->Master_model->delete_movement_stage($id);
        $this->session->set_flashdata('success', 'Movement status stage deleted successfully.');
        redirect('movement-stages');
    }

    // --- SERVICE TYPES ---
    public function service_types() {
        $data['page_title'] = 'Service Types Master';
        $data['service_types'] = $this->Master_model->get_service_types();
        $data['view_path'] = 'masters/service_types_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_service_type() {
        $this->form_validation->set_rules('service_name', 'Service Name', 'required|is_unique[service_types.service_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'service_name' => $this->input->post('service_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->add_service_type($data);
            $this->session->set_flashdata('success', 'Service Type added successfully.');
        }
        redirect('service-types');
    }

    public function edit_service_type($id) {
        $original = $this->Master_model->get_service_types($id);
        $is_unique = '';
        if ($this->input->post('service_name') != $original->service_name) {
            $is_unique = '|is_unique[service_types.service_name]';
        }
        $this->form_validation->set_rules('service_name', 'Service Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'service_name' => $this->input->post('service_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->update_service_type($id, $data);
            $this->session->set_flashdata('success', 'Service Type updated successfully.');
        }
        redirect('service-types');
    }

    public function delete_service_type($id) {
        $this->Master_model->delete_service_type($id);
        $this->session->set_flashdata('success', 'Service Type deleted successfully.');
        redirect('service-types');
    }

    // --- DOCUMENT TYPES ---
    public function document_types() {
        $data['page_title'] = 'Document Types Master';
        $data['document_types'] = $this->Master_model->get_document_types();
        $data['view_path'] = 'masters/document_types_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_document_type() {
        $this->form_validation->set_rules('doc_type_name', 'Document Type Name', 'required|is_unique[document_types.doc_type_name]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'doc_type_name' => $this->input->post('doc_type_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->add_document_type($data);
            $this->session->set_flashdata('success', 'Document Type added successfully.');
        }
        redirect('document-types');
    }

    public function edit_document_type($id) {
        $original = $this->Master_model->get_document_types($id);
        $is_unique = '';
        if ($this->input->post('doc_type_name') != $original->doc_type_name) {
            $is_unique = '|is_unique[document_types.doc_type_name]';
        }
        $this->form_validation->set_rules('doc_type_name', 'Document Type Name', 'required' . $is_unique);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'doc_type_name' => $this->input->post('doc_type_name'),
                'description' => $this->input->post('description')
            );
            $this->Master_model->update_document_type($id, $data);
            $this->session->set_flashdata('success', 'Document Type updated successfully.');
        }
        redirect('document-types');
    }

    public function delete_document_type($id) {
        $this->Master_model->delete_document_type($id);
        $this->session->set_flashdata('success', 'Document Type deleted successfully.');
        redirect('document-types');
    }

    // --- GENERAL APP SETTINGS ---
    public function app_settings() {
        $this->form_validation->set_rules('company_name', 'Company Name', 'required');
        $this->form_validation->set_rules('company_email', 'Company Email', 'valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Application & Gateway Settings';
            $data['settings'] = $this->Master_model->get_app_settings();
            $data['view_path'] = 'masters/app_settings';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            
            // Handle unchecked checkboxes
            $checkboxes = array('smtp_enabled', 'sms_enabled', 'whatsapp_enabled');
            foreach ($checkboxes as $cb) {
                if (!isset($post[$cb])) {
                    $post[$cb] = '0';
                }
            }

            // Handle company logo upload
            if (!empty($_FILES['company_logo']['name'])) {
                $config['upload_path'] = './assets/img/';
                $config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
                $config['max_size'] = 2048; // 2MB max
                $config['file_name'] = 'logo_' . time();
                
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('company_logo')) {
                    $uploadData = $this->upload->data();
                    $post['company_logo'] = $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('app-settings');
                    return;
                }
            }

            $this->Master_model->update_app_settings($post);
            $this->session->set_flashdata('success', 'Application settings updated successfully.');
            redirect('app-settings');
        }
    }

    public function notification_logs() {
        // Access Control (Only Super Admin, Branch Admin and Branch Staff can view logs, i.e., not customer)
        if ($this->session->userdata('role_id') == 4) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect('dashboard');
        }
        
        $data['page_title'] = 'Notification Logs';
        
        // Fetch notifications sorted by id DESC (newest first)
        $this->db->select('notifications.*, users.username');
        $this->db->from('notifications');
        $this->db->join('users', 'users.id = notifications.user_id', 'left');
        $this->db->order_by('notifications.id', 'DESC');
        $data['logs'] = $this->db->get()->result();
        
        $data['view_path'] = 'masters/notification_logs';
        $this->load->view('templates/dashboard_layout', $data);
    }

    // --- ROLES & PERMISSIONS ---
    public function roles() {
        $data['page_title'] = 'Roles & Permissions';
        $data['roles'] = $this->Master_model->get_roles();
        $data['permissions'] = $this->Master_model->get_permissions();
        
        // Build an array of permission IDs per role
        $data['role_permissions'] = array();
        foreach ($data['roles'] as $role) {
            $data['role_permissions'][$role->id] = $this->Master_model->get_role_permissions($role->id);
        }
        
        $data['view_path'] = 'masters/roles_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_role() {
        $this->form_validation->set_rules('name', 'Role Name', 'required|is_unique[roles.name]');
        $this->form_validation->set_rules('description', 'Description', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            $role_data = array(
                'name' => $post['name'],
                'description' => $post['description']
            );
            $this->Master_model->add_role($role_data);
            $this->session->set_flashdata('success', 'New role created successfully.');
        }
        redirect('roles');
    }

    public function edit_role($id) {
        // Prevent editing default system roles (1 to 4)
        if ($id <= 4) {
            $this->session->set_flashdata('error', 'Default system roles cannot be modified.');
            redirect('roles');
        }

        $this->form_validation->set_rules('name', 'Role Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $post = $this->input->post(NULL, TRUE);
            $role_data = array(
                'name' => $post['name'],
                'description' => $post['description']
            );
            $this->Master_model->update_role($id, $role_data);
            $this->session->set_flashdata('success', 'Role details updated successfully.');
        }
        redirect('roles');
    }

    public function delete_role($id) {
        // Prevent deleting default system roles (1 to 4)
        if ($id <= 4) {
            $this->session->set_flashdata('error', 'Default system roles cannot be deleted.');
            redirect('roles');
        }

        if ($this->Master_model->delete_role($id)) {
            $this->session->set_flashdata('success', 'Role and its permission mappings deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete role.');
        }
        redirect('roles');
    }

    public function save_role_permissions() {
        $role_id = $this->input->post('role_id');
        $permission_ids = $this->input->post('permissions');
        
        if (empty($permission_ids)) {
            $permission_ids = array();
        }

        if ($role_id) {
            $this->Master_model->update_role_permissions($role_id, $permission_ids);
            $this->session->set_flashdata('success', 'Permissions mapped to role successfully.');
        } else {
            $this->session->set_flashdata('error', 'Invalid role selection.');
        }
        redirect('roles');
    }

    // --- GEO LOCATIONS (01_geo_location_info) ---
    public function geo_locations() {
        $data['page_title'] = 'Geo Locations Master';
        $data['countries'] = $this->Master_model->get_countries();
        $data['total_count'] = $this->Master_model->count_all_geo_locations();
        $data['view_path'] = 'masters/geo_location_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function geo_locations_ajax() {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        if ($length <= 0) {
            $length = 10;
        }

        $search_data = $this->input->post('search');
        $search = isset($search_data['value']) ? trim($search_data['value']) : null;

        $order = $this->input->post('order');
        $col_index = isset($order[0]['column']) ? intval($order[0]['column']) : 0;
        $order_dir = (isset($order[0]['dir']) && strtolower($order[0]['dir']) === 'asc') ? 'ASC' : 'DESC';

        $column_map = array(
            0 => 'id',
            1 => 'country_name',
            2 => 'state_name',
            3 => 'district_name',
            4 => 'city_name',
            5 => 'postal_code',
            6 => 'latitude',
            7 => 'is_active',
            8 => 'id'
        );
        $order_col = isset($column_map[$col_index]) ? $column_map[$col_index] : 'id';

        $total_records = $this->Master_model->count_all_geo_locations();
        $filtered_records = $this->Master_model->count_filtered_geo_locations($search);
        $records = $this->Master_model->get_geo_locations_datatable($length, $start, $search, $order_col, $order_dir);

        $data = array();
        foreach ($records as $row) {
            $country_html = '<strong>' . htmlspecialchars($row->country_name) . '</strong>';
            if (!empty($row->country_code)) {
                $country_html .= ' <span class="label label-primary">' . htmlspecialchars($row->country_code) . '</span>';
            }
            if (!empty($row->country_code3)) {
                $country_html .= ' <small class="text-muted">' . htmlspecialchars($row->country_code3) . '</small>';
            }

            $state_html = htmlspecialchars($row->state_name ? $row->state_name : '-');
            if (!empty($row->state_code)) {
                $state_html .= ' <span class="label label-default">' . htmlspecialchars($row->state_code) . '</span>';
            }

            $postal_html = '<strong>' . htmlspecialchars($row->postal_code ? $row->postal_code : '-') . '</strong>';
            if (!empty($row->postal_name)) {
                $postal_html .= '<br><small class="text-muted"><i class="fa fa-map-pin"></i> ' . htmlspecialchars($row->postal_name) . '</small>';
            }

            $coords_html = '-';
            if (!empty($row->latitude) || !empty($row->longitude)) {
                $coords_html = '<span class="text-muted"><small>' . htmlspecialchars($row->latitude) . ',<br>' . htmlspecialchars($row->longitude) . '</small></span>';
            }

            $status_html = ($row->is_active == 1) 
                ? '<span class="label label-success">Active</span>' 
                : '<span class="label label-danger">Inactive</span>';

            $actions_html = '
                <button type="button" class="btn btn-primary btn-xs edit-geo-btn" data-id="' . $row->id . '">
                    <i class="fa fa-pencil"></i> Edit
                </button>
                <a href="' . site_url('geo-locations/delete/' . $row->id) . '" class="btn btn-danger btn-xs" onclick="return confirm(\'Are you sure you want to delete this geo location?\');">
                    <i class="fa fa-trash"></i> Delete
                </a>
            ';

            $data[] = array(
                $row->id,
                $country_html,
                $state_html,
                htmlspecialchars($row->district_name ? $row->district_name : '-'),
                htmlspecialchars($row->city_name ? $row->city_name : '-'),
                $postal_html,
                $coords_html,
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

    public function get_geo_location($id) {
        $record = $this->Master_model->get_geo_locations($id);
        if ($record) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'success', 'data' => $record)));
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('status' => 'error', 'message' => 'Record not found')));
        }
    }

    public function add_geo_location() {
        $this->form_validation->set_rules('country_name', 'Country Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'country_code'   => strtoupper(trim($this->input->post('country_code'))),
                'country_code3'  => strtoupper(trim($this->input->post('country_code3'))),
                'country_name'   => trim($this->input->post('country_name')),
                'state_code'     => trim($this->input->post('state_code')),
                'state_name'     => trim($this->input->post('state_name')),
                'state_type'     => trim($this->input->post('state_type')),
                'district_code'  => trim($this->input->post('district_code')),
                'district_name'  => trim($this->input->post('district_name')),
                'district_type'  => trim($this->input->post('district_type')),
                'city_name'      => trim($this->input->post('city_name')),
                'city_type'      => trim($this->input->post('city_type')),
                'postal_code'    => trim($this->input->post('postal_code')),
                'postal_name'    => trim($this->input->post('postal_name')),
                'latitude'       => $this->input->post('latitude') !== '' ? $this->input->post('latitude') : NULL,
                'longitude'      => $this->input->post('longitude') !== '' ? $this->input->post('longitude') : NULL,
                'is_active'      => intval($this->input->post('is_active')),
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            );

            $this->Master_model->add_geo_location($data);
            $this->session->set_flashdata('success', 'Geo Location added successfully.');
        }
        redirect('geo-locations');
    }

    public function edit_geo_location($id) {
        $this->form_validation->set_rules('country_name', 'Country Name', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'country_code'   => strtoupper(trim($this->input->post('country_code'))),
                'country_code3'  => strtoupper(trim($this->input->post('country_code3'))),
                'country_name'   => trim($this->input->post('country_name')),
                'state_code'     => trim($this->input->post('state_code')),
                'state_name'     => trim($this->input->post('state_name')),
                'state_type'     => trim($this->input->post('state_type')),
                'district_code'  => trim($this->input->post('district_code')),
                'district_name'  => trim($this->input->post('district_name')),
                'district_type'  => trim($this->input->post('district_type')),
                'city_name'      => trim($this->input->post('city_name')),
                'city_type'      => trim($this->input->post('city_type')),
                'postal_code'    => trim($this->input->post('postal_code')),
                'postal_name'    => trim($this->input->post('postal_name')),
                'latitude'       => $this->input->post('latitude') !== '' ? $this->input->post('latitude') : NULL,
                'longitude'      => $this->input->post('longitude') !== '' ? $this->input->post('longitude') : NULL,
                'is_active'      => intval($this->input->post('is_active')),
                'updated_at'     => date('Y-m-d H:i:s')
            );

            $this->Master_model->update_geo_location($id, $data);
            $this->session->set_flashdata('success', 'Geo Location updated successfully.');
        }
        redirect('geo-locations');
    }

    public function delete_geo_location($id) {
        $this->Master_model->delete_geo_location($id);
        $this->session->set_flashdata('success', 'Geo Location deleted successfully.');
        redirect('geo-locations');
    }

    public function export_geo_locations() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        require_once APPPATH . 'third_party/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Courier Syndicate")
                                     ->setTitle("Geo Locations Export");

        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Geo Locations');

        $headers = array(
            'A1' => 'ID',
            'B1' => 'Country Code',
            'C1' => 'Country Code 3',
            'D1' => 'Country Name',
            'E1' => 'State Code',
            'F1' => 'State Name',
            'G1' => 'State Type',
            'H1' => 'District Code',
            'I1' => 'District Name',
            'J1' => 'District Type',
            'K1' => 'City Name',
            'L1' => 'City Type',
            'M1' => 'Postal Code',
            'N1' => 'Postal Name',
            'O1' => 'Latitude',
            'P1' => 'Longitude',
            'Q1' => 'Status',
            'R1' => 'Created At',
            'S1' => 'Updated At'
        );

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Style Header Row
        $headerStyle = array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3C8DBC')
            ),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:S1')->applyFromArray($headerStyle);

        $records = $this->Master_model->get_all_geo_locations_for_export();
        $row_num = 2;
        foreach ($records as $r) {
            $sheet->setCellValueExplicit('A' . $row_num, $r['id'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('B' . $row_num, $r['country_code'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $row_num, $r['country_code3'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row_num, $r['country_name']);
            $sheet->setCellValueExplicit('E' . $row_num, $r['state_code'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('F' . $row_num, $r['state_name']);
            $sheet->setCellValue('G' . $row_num, $r['state_type']);
            $sheet->setCellValueExplicit('H' . $row_num, $r['district_code'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('I' . $row_num, $r['district_name']);
            $sheet->setCellValue('J' . $row_num, $r['district_type']);
            $sheet->setCellValue('K' . $row_num, $r['city_name']);
            $sheet->setCellValue('L' . $row_num, $r['city_type']);
            $sheet->setCellValueExplicit('M' . $row_num, $r['postal_code'], PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('N' . $row_num, $r['postal_name']);
            $sheet->setCellValue('O' . $row_num, $r['latitude']);
            $sheet->setCellValue('P' . $row_num, $r['longitude']);
            $sheet->setCellValue('Q' . $row_num, ($r['is_active'] == 1 ? 'Active' : 'Inactive'));
            $sheet->setCellValue('R' . $row_num, $r['created_at']);
            $sheet->setCellValue('S' . $row_num, $r['updated_at']);
            $row_num++;
        }

        // Auto width
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'geo_locations_' . date('Y-m-d_His') . '.xls';
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function download_geo_location_template() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

        require_once APPPATH . 'third_party/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Template');

        $headers = array(
            'A1' => 'Country Code',
            'B1' => 'Country Code 3',
            'C1' => 'Country Name',
            'D1' => 'State Code',
            'E1' => 'State Name',
            'F1' => 'State Type',
            'G1' => 'District Code',
            'H1' => 'District Name',
            'I1' => 'District Type',
            'J1' => 'City Name',
            'K1' => 'City Type',
            'L1' => 'Postal Code',
            'M1' => 'Postal Name',
            'N1' => 'Latitude',
            'O1' => 'Longitude',
            'P1' => 'Status'
        );

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerStyle = array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '00A65A')
            ),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:P1')->applyFromArray($headerStyle);

        // Sample rows
        $sample_data = array(
            array('IN', 'IND', 'India', 'TN', 'Tamil Nadu', 'State', 'CH', 'Chennai', 'District', 'Chennai', 'City', '600001', 'George Town', '13.0827000', '80.2707000', 'Active'),
            array('IN', 'IND', 'India', 'KA', 'Karnataka', 'State', 'BLR', 'Bengaluru Urban', 'District', 'Bengaluru', 'City', '560001', 'GPO', '12.9716000', '77.5946000', 'Active'),
            array('US', 'USA', 'United States', 'NY', 'New York', 'State', 'NY', 'New York', 'County', 'New York', 'City', '10001', 'Manhattan', '40.7501000', '-73.9967000', 'Active')
        );

        $r = 2;
        foreach ($sample_data as $row) {
            $col = 'A';
            foreach ($row as $val) {
                $sheet->setCellValueExplicit($col . $r, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                $col++;
            }
            $r++;
        }

        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'geo_location_import_template.xls';
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function import_geo_locations() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
        ini_set('memory_limit', '512M');
        set_time_limit(600);

        if (empty($_FILES['excel_file']['name'])) {
            $this->session->set_flashdata('error', 'Please choose an Excel or CSV file to import.');
            redirect('geo-locations');
        }

        $file_name = $_FILES['excel_file']['name'];
        $tmp_file = $_FILES['excel_file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $valid_extensions = array('xls', 'xlsx', 'csv');
        if (!in_array($file_ext, $valid_extensions)) {
            $this->session->set_flashdata('error', 'Invalid file type. Allowed formats: .xls, .xlsx, .csv');
            redirect('geo-locations');
        }

        require_once APPPATH . 'third_party/PHPExcel.php';

        try {
            if ($file_ext === 'csv') {
                $objReader = PHPExcel_IOFactory::createReader('CSV');
            } elseif ($file_ext === 'xlsx') {
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            } else {
                $objReader = PHPExcel_IOFactory::createReader('Excel5');
            }

            $objPHPExcel = $objReader->load($tmp_file);
            $sheet = $objPHPExcel->getActiveSheet();
            $sheetData = $sheet->toArray(null, true, true, false);

            if (empty($sheetData) || count($sheetData) < 2) {
                $this->session->set_flashdata('error', 'The uploaded file contains no data rows.');
                redirect('geo-locations');
            }

            // Map header row
            $header_row = $sheetData[0];
            $col_map = array();

            foreach ($header_row as $idx => $heading) {
                if ($heading === null) continue;
                $norm = strtolower(trim(str_replace(array(' ', '_', '-'), '', $heading)));
                switch ($norm) {
                    case 'countrycode':
                    case 'countrycode2':
                    case 'iso2':
                    case 'countryiso':
                        $col_map['country_code'] = $idx;
                        break;
                    case 'countrycode3':
                    case 'iso3':
                        $col_map['country_code3'] = $idx;
                        break;
                    case 'country':
                    case 'countryname':
                        $col_map['country_name'] = $idx;
                        break;
                    case 'statecode':
                        $col_map['state_code'] = $idx;
                        break;
                    case 'state':
                    case 'statename':
                        $col_map['state_name'] = $idx;
                        break;
                    case 'statetype':
                        $col_map['state_type'] = $idx;
                        break;
                    case 'districtcode':
                        $col_map['district_code'] = $idx;
                        break;
                    case 'district':
                    case 'districtname':
                        $col_map['district_name'] = $idx;
                        break;
                    case 'districttype':
                        $col_map['district_type'] = $idx;
                        break;
                    case 'city':
                    case 'cityname':
                        $col_map['city_name'] = $idx;
                        break;
                    case 'citytype':
                        $col_map['city_type'] = $idx;
                        break;
                    case 'postalcode':
                    case 'pincode':
                    case 'zipcode':
                    case 'pincodecode':
                    case 'pin':
                    case 'zip':
                        $col_map['postal_code'] = $idx;
                        break;
                    case 'postalname':
                    case 'area':
                    case 'areaname':
                    case 'locality':
                        $col_map['postal_name'] = $idx;
                        break;
                    case 'latitude':
                    case 'lat':
                        $col_map['latitude'] = $idx;
                        break;
                    case 'longitude':
                    case 'long':
                    case 'lng':
                        $col_map['longitude'] = $idx;
                        break;
                    case 'status':
                    case 'isactive':
                    case 'active':
                        $col_map['is_active'] = $idx;
                        break;
                }
            }

            if (!isset($col_map['country_name']) && !isset($col_map['postal_code'])) {
                $this->session->set_flashdata('error', 'Unable to find required columns (Country Name or Postal Code) in file header.');
                redirect('geo-locations');
            }

            $inserted = 0;
            $updated = 0;
            $skipped = 0;

            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];

                // Check if row is empty
                $non_empty = array_filter($row, function($v) { return $v !== null && trim($v) !== ''; });
                if (empty($non_empty)) {
                    continue;
                }

                $country_name = isset($col_map['country_name'], $row[$col_map['country_name']]) ? trim($row[$col_map['country_name']]) : '';
                $postal_code = isset($col_map['postal_code'], $row[$col_map['postal_code']]) ? trim($row[$col_map['postal_code']]) : '';

                if (empty($country_name) && empty($postal_code)) {
                    $skipped++;
                    continue;
                }

                $country_code = isset($col_map['country_code'], $row[$col_map['country_code']]) ? strtoupper(trim($row[$col_map['country_code']])) : NULL;
                $country_code3 = isset($col_map['country_code3'], $row[$col_map['country_code3']]) ? strtoupper(trim($row[$col_map['country_code3']])) : NULL;
                $state_code = isset($col_map['state_code'], $row[$col_map['state_code']]) ? trim($row[$col_map['state_code']]) : NULL;
                $state_name = isset($col_map['state_name'], $row[$col_map['state_name']]) ? trim($row[$col_map['state_name']]) : NULL;
                $state_type = isset($col_map['state_type'], $row[$col_map['state_type']]) ? trim($row[$col_map['state_type']]) : NULL;
                $district_code = isset($col_map['district_code'], $row[$col_map['district_code']]) ? trim($row[$col_map['district_code']]) : NULL;
                $district_name = isset($col_map['district_name'], $row[$col_map['district_name']]) ? trim($row[$col_map['district_name']]) : NULL;
                $district_type = isset($col_map['district_type'], $row[$col_map['district_type']]) ? trim($row[$col_map['district_type']]) : NULL;
                $city_name = isset($col_map['city_name'], $row[$col_map['city_name']]) ? trim($row[$col_map['city_name']]) : NULL;
                $city_type = isset($col_map['city_type'], $row[$col_map['city_type']]) ? trim($row[$col_map['city_type']]) : NULL;
                $postal_name = isset($col_map['postal_name'], $row[$col_map['postal_name']]) ? trim($row[$col_map['postal_name']]) : NULL;
                $latitude = isset($col_map['latitude'], $row[$col_map['latitude']]) && trim($row[$col_map['latitude']]) !== '' ? trim($row[$col_map['latitude']]) : NULL;
                $longitude = isset($col_map['longitude'], $row[$col_map['longitude']]) && trim($row[$col_map['longitude']]) !== '' ? trim($row[$col_map['longitude']]) : NULL;

                $is_active = 1;
                if (isset($col_map['is_active'], $row[$col_map['is_active']])) {
                    $st_val = strtolower(trim($row[$col_map['is_active']]));
                    if ($st_val === 'inactive' || $st_val === '0' || $st_val === 'false' || $st_val === 'no') {
                        $is_active = 0;
                    }
                }

                $record_data = array(
                    'country_code'   => $country_code ?: NULL,
                    'country_code3'  => $country_code3 ?: NULL,
                    'country_name'   => $country_name ?: 'Unknown',
                    'state_code'     => $state_code ?: NULL,
                    'state_name'     => $state_name ?: NULL,
                    'state_type'     => $state_type ?: NULL,
                    'district_code'  => $district_code ?: NULL,
                    'district_name'  => $district_name ?: NULL,
                    'district_type'  => $district_type ?: NULL,
                    'city_name'      => $city_name ?: NULL,
                    'city_type'      => $city_type ?: NULL,
                    'postal_code'    => $postal_code ?: NULL,
                    'postal_name'    => $postal_name ?: NULL,
                    'latitude'       => $latitude,
                    'longitude'      => $longitude,
                    'is_active'      => $is_active
                );

                $res = $this->Master_model->upsert_geo_location($record_data);
                if ($res === 'updated') {
                    $updated++;
                } else {
                    $inserted++;
                }
            }

            $this->Audit_model->log_activity('Import Geo Locations', "Imported: $inserted, Updated: $updated, Skipped: $skipped");
            $this->session->set_flashdata('success', "Import completed successfully! $inserted records inserted, $updated records updated" . ($skipped > 0 ? ", $skipped empty/invalid rows skipped." : "."));

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error reading Excel file: ' . $e->getMessage());
        }

        redirect('geo-locations');
    }

    // =========================================================================
    // --- ODA (OUT OF DELIVERY AREA) CHARGES MASTER ---
    // =========================================================================

    public function oda_charges() {
        $this->Master_model->ensure_oda_charges_schema();
        $filters = array(
            'destination_country_id' => $this->input->post_get('dest_country'),
            'courier_partner_id'     => $this->input->post_get('partner'),
            'service_type'           => $this->input->post_get('service_type'),
            'tier_zone'              => $this->input->post_get('tier_zone'),
            'courier_mode'           => $this->input->post_get('courier_mode'),
            'commercial_mode'        => $this->input->post_get('commercial_mode'),
            'is_non_deliverable'     => $this->input->post_get('is_non_deliverable'),
            'location_type'          => $this->input->post_get('location_type'),
            'calculation_type'       => $this->input->post_get('calculation_type'),
            'status'                 => $this->input->post_get('status')
        );

        $data['page_title']       = 'ODA (Out of Delivery Area) Charges Master';
        $data['countries']        = $this->Master_model->get_countries();
        $data['courier_partners'] = $this->Master_model->get_courier_partners();
        $data['service_types']    = $this->Master_model->get_service_types();
        $data['tier_zones']       = $this->Master_model->get_distinct_oda_tier_zones();
        $data['courier_modes']    = $this->Master_model->get_distinct_oda_courier_modes();
        $data['commercial_modes'] = $this->Master_model->get_distinct_oda_commercial_modes();
        $data['filters']          = $filters;

        // Statistics
        $data['total_count']    = $this->Master_model->count_all_oda_charges();
        $data['active_count']   = $this->Master_model->count_all_oda_charges(array('status' => 'Active'));
        $data['inactive_count'] = $this->Master_model->count_all_oda_charges(array('status' => 'Inactive'));

        $data['view_path'] = 'masters/oda_charges';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function oda_charges_ajax() {
        $this->Master_model->ensure_oda_charges_schema();
        $draw   = intval($this->input->post_get('draw'));
        $start  = intval($this->input->post_get('start'));
        $length = intval($this->input->post_get('length'));
        if ($length <= 0) $length = 10;

        $search_val = '';
        $search = $this->input->post_get('search');
        if (is_array($search) && isset($search['value'])) {
            $search_val = trim($search['value']);
        }

        $order_col_idx = 0;
        $order_dir = 'DESC';
        $order = $this->input->post_get('order');
        if (is_array($order) && isset($order[0])) {
            $order_col_idx = intval($order[0]['column']);
            $order_dir = strtoupper($order[0]['dir']) === 'ASC' ? 'ASC' : 'DESC';
        }

        $filters = array(
            'destination_country_id' => $this->input->post_get('dest_country'),
            'courier_partner_id'     => $this->input->post_get('partner'),
            'service_type'           => $this->input->post_get('service_type'),
            'tier_zone'              => $this->input->post_get('tier_zone'),
            'courier_mode'           => $this->input->post_get('courier_mode'),
            'commercial_mode'        => $this->input->post_get('commercial_mode'),
            'is_non_deliverable'     => $this->input->post_get('is_non_deliverable'),
            'location_type'          => $this->input->post_get('location_type'),
            'calculation_type'       => $this->input->post_get('calculation_type'),
            'status'                 => $this->input->post_get('status')
        );

        $recordsTotal = $this->Master_model->count_all_oda_charges($filters);
        $recordsFiltered = $this->Master_model->count_filtered_oda_charges($filters, $search_val);
        $list = $this->Master_model->get_oda_charges_datatable($filters, $length, $start, $search_val, $order_col_idx, $order_dir);

        $data = array();
        $sno = $start + 1;
        foreach ($list as $r) {
            $country_badge = '<strong>' . htmlspecialchars($r->destination_country) . '</strong>';
            if (!empty($r->destination_country_code)) {
                $country_badge .= ' <span class="label label-default" style="font-size:10px;">' . htmlspecialchars($r->destination_country_code) . '</span>';
            }

            $partner_badge = !empty($r->courier_partner_name) 
                ? '<span class="label label-primary" style="font-size:11px;"><i class="fa fa-truck"></i> ' . htmlspecialchars($r->courier_partner_name) . '</span>'
                : '<span class="label label-info" style="font-size:11px;"><i class="fa fa-globe"></i> All Partners</span>';

            $service_badge = (!empty($r->service_type) && strtolower($r->service_type) !== 'all')
                ? '<span class="label label-warning" style="font-size:11px;"><i class="fa fa-plane"></i> ' . htmlspecialchars($r->service_type) . '</span>'
                : '<span class="label label-default" style="font-size:11px;"><i class="fa fa-asterisk"></i> All Services</span>';

            $tier_zone_html = !empty($r->tier_zone)
                ? '<span class="label label-primary" style="font-size:11px;"><i class="fa fa-cubes"></i> ' . htmlspecialchars($r->tier_zone) . '</span>'
                : '<span class="text-muted" style="font-size:11px;">-</span>';

            $courier_mode_html = !empty($r->courier_mode)
                ? '<span class="label label-info" style="font-size:11px;"><i class="fa fa-paper-plane-o"></i> ' . htmlspecialchars($r->courier_mode) . '</span>'
                : '<span class="text-muted" style="font-size:11px;">-</span>';

            $commercial_mode_html = !empty($r->commercial_mode)
                ? '<span class="label label-default" style="font-size:11px;"><i class="fa fa-briefcase"></i> ' . htmlspecialchars($r->commercial_mode) . '</span>'
                : '<span class="text-muted" style="font-size:11px;">-</span>';

            $non_deliv_badge = (!empty($r->is_non_deliverable) && intval($r->is_non_deliverable) === 1)
                ? '<span class="label label-danger" style="font-size:11px;" title="Non-Deliverable Location (No Service)"><i class="fa fa-ban"></i> Yes</span>'
                : '<span class="label label-success" style="font-size:11px;" title="Deliverable Area (ODA Surcharge)"><i class="fa fa-check"></i> No</span>';

            // Location display
            $loc_type_badge = '';
            $loc_detail = '';
            if ($r->location_type === 'pincode') {
                $loc_type_badge = '<span class="label label-default">Postal Code</span>';
                $loc_detail = '<code style="font-size:12px; font-weight:600;">' . htmlspecialchars($r->postal_code) . '</code>';
            } elseif ($r->location_type === 'pincode_range') {
                $loc_type_badge = '<span class="label label-warning">Postal Range</span>';
                $loc_detail = '<code>' . htmlspecialchars($r->postal_code_from) . '</code> <i class="fa fa-arrow-right text-muted"></i> <code>' . htmlspecialchars($r->postal_code_to) . '</code>';
            } else {
                $loc_type_badge = '<span class="label label-success">City / Town</span>';
                $loc_detail = '<strong>' . htmlspecialchars($r->city_name) . '</strong>';
            }

            if (!empty($r->state_name)) {
                $loc_detail .= ' <small class="text-muted">(' . htmlspecialchars($r->state_name) . ')</small>';
            }

            if (!empty($r->geo_location_id)) {
                $geo_title = 'Linked to geo_location_info ID #' . $r->geo_location_id;
                if (!empty($r->geo_postal_name)) {
                    $geo_title .= ' - ' . $r->geo_postal_name;
                }
                $loc_detail .= '<br><span class="label label-info" style="font-size:10px; margin-top:2px; display:inline-block;" title="' . htmlspecialchars($geo_title) . '"><i class="fa fa-map-marker"></i> Geo #' . $r->geo_location_id . (!empty($r->geo_postal_name) ? ' (' . htmlspecialchars($r->geo_postal_name) . ')' : '') . '</span>';
            }

            // Calculation formula display
            $calc_badge = '';
            $charge_detail = '';
            if ($r->calculation_type === 'higher_of') {
                $calc_badge = '<span class="label label-primary">Higher Of</span>';
                $charge_detail = 'Min: <strong>₹' . number_format($r->min_charge, 2) . '</strong> <span class="text-muted">or</span> <strong>₹' . number_format($r->per_kg_rate, 2) . '/kg</strong>';
            } elseif ($r->calculation_type === 'flat') {
                $calc_badge = '<span class="label label-info">Flat Fee</span>';
                $charge_detail = 'Flat: <strong>₹' . number_format($r->flat_charge, 2) . '</strong>';
            } else {
                $calc_badge = '<span class="label label-warning">Per KG</span>';
                $charge_detail = 'Rate: <strong>₹' . number_format($r->per_kg_rate, 2) . '/kg</strong>';
            }

            // Status badge
            $status_class = ($r->status === 'Active') ? 'label-success' : 'label-danger';
            $status_html = '<a href="javascript:void(0);" onclick="toggleOdaStatus(' . $r->id . ')" class="label ' . $status_class . '" style="cursor:pointer;" title="Click to toggle status">' . $r->status . '</a>';

            // Action buttons
            $actions = '<div class="btn-group">' .
                '<button type="button" class="btn btn-xs btn-default" onclick="openEditOdaModal(' . $r->id . ')" title="Edit ODA Rule"><i class="fa fa-pencil text-blue"></i></button>' .
                '<button type="button" class="btn btn-xs btn-default" onclick="confirmDeleteOda(' . $r->id . ')" title="Soft Delete"><i class="fa fa-trash text-danger"></i></button>' .
            '</div>';

            $data[] = array(
                $sno++,
                $country_badge,
                $partner_badge,
                $service_badge,
                $tier_zone_html,
                $courier_mode_html,
                $commercial_mode_html,
                $non_deliv_badge,
                $loc_type_badge,
                $loc_detail,
                $calc_badge . '<br>' . $charge_detail,
                $status_html,
                $actions
            );
        }

        echo json_encode(array(
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ));
        exit;
    }

    /**
     * AJAX endpoint for searching verified locations from 01_geo_location_info
     */
    public function geo_search_ajax() {
        $q = trim($this->input->get('q'));
        $country_id = $this->input->get('country_id');
        $results = $this->Master_model->search_geo_locations($q, $country_id, 30);
        $formatted = array();
        foreach ($results as $item) {
            $label = $item->city_name;
            if (!empty($item->postal_code)) {
                $label .= ' - ' . $item->postal_code;
            }
            if (!empty($item->postal_name) && $item->postal_name !== $item->city_name) {
                $label .= ' (' . $item->postal_name . ')';
            }
            if (!empty($item->district_name)) {
                $label .= ' [' . $item->district_name . ']';
            }
            if (!empty($item->state_name)) {
                $label .= ', ' . $item->state_name;
            }
            $label .= ', ' . $item->country_name;

            $formatted[] = array(
                'id'            => $item->id,
                'text'          => $label,
                'postal_code'   => $item->postal_code,
                'city_name'     => $item->city_name,
                'state_name'    => $item->state_name,
                'country_name'  => $item->country_name,
                'country_code'  => $item->country_code,
                'postal_name'   => $item->postal_name,
                'district_name' => $item->district_name
            );
        }
        echo json_encode(array('items' => $formatted));
        exit;
    }

    public function get_oda_charge($id) {
        $record = $this->Master_model->get_oda_charge_by_id($id);
        if ($record) {
            echo json_encode(array('status' => 'success', 'data' => $record));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Record not found.'));
        }
        exit;
    }

    public function save_oda_charge() {
        $id = $this->input->post('id');
        $this->form_validation->set_rules('destination_country_id', 'Destination Country', 'required|numeric');
        $this->form_validation->set_rules('courier_partner_id', 'Courier Partner', 'required|numeric');
        $this->form_validation->set_rules('location_type', 'Location Type', 'required|in_list[pincode,pincode_range,city]');
        $this->form_validation->set_rules('calculation_type', 'Calculation Type', 'required|in_list[higher_of,flat,per_kg]');

        $location_type = $this->input->post('location_type');
        if ($location_type === 'pincode') {
            $this->form_validation->set_rules('postal_code', 'Postal Code', 'required|trim');
        } elseif ($location_type === 'pincode_range') {
            $this->form_validation->set_rules('postal_code_from', 'Postal Code From', 'required|trim');
            $this->form_validation->set_rules('postal_code_to', 'Postal Code To', 'required|trim');
        } else {
            $this->form_validation->set_rules('city_name', 'City / Town Name', 'required|trim');
        }

        if ($this->form_validation->run() === FALSE) {
            $err = validation_errors('', '');
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => $err));
                exit;
            }
            $this->session->set_flashdata('error', $err);
            redirect('oda-charges');
        }

        $post_data = array(
            'destination_country_id' => $this->input->post('destination_country_id'),
            'courier_partner_id'     => $this->input->post('courier_partner_id'),
            'service_type'           => $this->input->post('service_type') ?: 'All',
            'tier_zone'              => $this->input->post('tier_zone') ? trim($this->input->post('tier_zone')) : NULL,
            'courier_mode'           => $this->input->post('courier_mode') ? trim($this->input->post('courier_mode')) : NULL,
            'commercial_mode'        => $this->input->post('commercial_mode') ? trim($this->input->post('commercial_mode')) : NULL,
            'is_non_deliverable'     => intval($this->input->post('is_non_deliverable')) ? 1 : 0,
            'location_type'          => $this->input->post('location_type'),
            'geo_location_id'        => $this->input->post('geo_location_id') ? intval($this->input->post('geo_location_id')) : NULL,
            'postal_code'            => $this->input->post('postal_code'),
            'postal_code_from'       => $this->input->post('postal_code_from'),
            'postal_code_to'         => $this->input->post('postal_code_to'),
            'city_name'              => $this->input->post('city_name'),
            'state_name'             => $this->input->post('state_name'),
            'calculation_type'       => $this->input->post('calculation_type'),
            'min_charge'             => $this->input->post('min_charge') ?: 0.00,
            'per_kg_rate'            => $this->input->post('per_kg_rate') ?: 0.00,
            'flat_charge'            => $this->input->post('flat_charge') ?: 0.00,
            'status'                 => $this->input->post('status') ?: 'Active',
            'created_by'             => $this->session->userdata('user_id')
        );

        if (!empty($id)) {
            $res = $this->Master_model->update_oda_charge($id, $post_data);
            $msg = 'ODA charge rule updated successfully.';
        } else {
            $res = $this->Master_model->add_oda_charge($post_data);
            $msg = 'New ODA charge rule added successfully.';
        }

        if ($this->input->is_ajax_request()) {
            echo json_encode(array('status' => $res ? 'success' : 'error', 'message' => $res ? $msg : 'Failed to save ODA rule.'));
            exit;
        }

        $this->session->set_flashdata($res ? 'success' : 'error', $res ? $msg : 'Failed to save ODA rule.');
        redirect('oda-charges');
    }

    public function delete_oda_charge($id) {
        $res = $this->Master_model->delete_oda_charge($id);
        if ($this->input->is_ajax_request()) {
            echo json_encode(array('status' => $res ? 'success' : 'error', 'message' => $res ? 'ODA rule soft-deleted successfully.' : 'Failed to delete ODA rule.'));
            exit;
        }
        $this->session->set_flashdata($res ? 'success' : 'error', $res ? 'ODA rule soft-deleted successfully.' : 'Failed to delete ODA rule.');
        redirect('oda-charges');
    }

    public function toggle_oda_status($id) {
        $new_status = $this->Master_model->toggle_oda_charge_status($id);
        if ($new_status) {
            echo json_encode(array('status' => 'success', 'new_status' => $new_status));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Failed to update status.'));
        }
        exit;
    }

    public function export_oda_charges() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
        ini_set('memory_limit', '512M');

        $filters = array(
            'destination_country_id' => $this->input->post_get('dest_country'),
            'courier_partner_id'     => $this->input->post_get('partner'),
            'service_type'           => $this->input->post_get('service_type'),
            'tier_zone'              => $this->input->post_get('tier_zone'),
            'courier_mode'           => $this->input->post_get('courier_mode'),
            'commercial_mode'        => $this->input->post_get('commercial_mode'),
            'location_type'          => $this->input->post_get('location_type'),
            'calculation_type'       => $this->input->post_get('calculation_type'),
            'status'                 => $this->input->post_get('status')
        );

        $records = $this->Master_model->get_all_oda_charges_for_export($filters);

        require_once APPPATH . 'third_party/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('ODA Charges');

        $headers = array(
            'A1' => 'ID',
            'B1' => 'Destination Country',
            'C1' => 'Courier Partner',
            'D1' => 'Service Type',
            'E1' => 'Tier / Zone',
            'F1' => 'Courier Mode',
            'G1' => 'Commercial Mode',
            'H1' => 'Non Deliverable Location',
            'I1' => 'Location Type',
            'J1' => 'Geo Location ID',
            'K1' => 'Postal Code',
            'L1' => 'Postal Code From',
            'M1' => 'Postal Code To',
            'N1' => 'City Name',
            'O1' => 'State Name',
            'P1' => 'Calculation Type',
            'Q1' => 'Min Charge (INR)',
            'R1' => 'Per KG Rate (INR)',
            'S1' => 'Flat Charge (INR)',
            'T1' => 'Status'
        );

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerStyle = array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '3C8DBC')
            ),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:T1')->applyFromArray($headerStyle);

        $row_num = 2;
        foreach ($records as $r) {
            $sheet->setCellValue('A' . $row_num, $r['id']);
            $sheet->setCellValue('B' . $row_num, $r['destination_country']);
            $sheet->setCellValue('C' . $row_num, !empty($r['courier_partner_name']) ? $r['courier_partner_name'] : 'All Partners');
            $sheet->setCellValue('D' . $row_num, !empty($r['service_type']) ? $r['service_type'] : 'All');
            $sheet->setCellValue('E' . $row_num, !empty($r['tier_zone']) ? $r['tier_zone'] : '');
            $sheet->setCellValue('F' . $row_num, !empty($r['courier_mode']) ? $r['courier_mode'] : '');
            $sheet->setCellValue('G' . $row_num, !empty($r['commercial_mode']) ? $r['commercial_mode'] : '');
            $sheet->setCellValue('H' . $row_num, (!empty($r['is_non_deliverable']) && intval($r['is_non_deliverable']) === 1) ? 'Yes' : 'No');
            $sheet->setCellValue('I' . $row_num, $r['location_type']);
            $sheet->setCellValue('J' . $row_num, !empty($r['geo_location_id']) ? $r['geo_location_id'] : '');
            $sheet->setCellValueExplicit('K' . $row_num, $r['postal_code'] ?: '', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('L' . $row_num, $r['postal_code_from'] ?: '', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('M' . $row_num, $r['postal_code_to'] ?: '', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet->setCellValue('N' . $row_num, $r['city_name'] ?: '');
            $sheet->setCellValue('O' . $row_num, $r['state_name'] ?: '');
            $sheet->setCellValue('P' . $row_num, $r['calculation_type']);
            $sheet->setCellValue('Q' . $row_num, $r['min_charge']);
            $sheet->setCellValue('R' . $row_num, $r['per_kg_rate']);
            $sheet->setCellValue('S' . $row_num, $r['flat_charge']);
            $sheet->setCellValue('T' . $row_num, $r['status']);
            $row_num++;
        }

        foreach (range('A', 'T') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'oda_charges_' . date('Y-m-d_His') . '.xls';
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function download_oda_template() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

        require_once APPPATH . 'third_party/PHPExcel.php';
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $sheet->setTitle('Template');

        $headers = array(
            'A1' => 'Destination Country',
            'B1' => 'Courier Partner',
            'C1' => 'Service Type',
            'D1' => 'Tier / Zone',
            'E1' => 'Courier Mode',
            'F1' => 'Commercial Mode',
            'G1' => 'Non Deliverable Location',
            'H1' => 'Location Type',
            'I1' => 'Postal Code',
            'J1' => 'Postal Code From',
            'K1' => 'Postal Code To',
            'L1' => 'City Name',
            'M1' => 'State Name',
            'N1' => 'Calculation Type',
            'O1' => 'Min Charge (INR)',
            'P1' => 'Per KG Rate (INR)',
            'Q1' => 'Flat Charge (INR)',
            'R1' => 'Status'
        );

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerStyle = array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '00A65A')
            ),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('A1:R1')->applyFromArray($headerStyle);

        // Sample rows demonstrating each location type and calculation model
        $sample_data = array(
            array('United Arab Emirates', 'Aramex', 'Express', 'Tier 1', 'Air', 'All', 'No', 'city', '', '', '', 'Hatta', 'Dubai', 'flat', '0.00', '0.00', '1500.00', 'Active'),
            array('United Arab Emirates', 'DHL Express', 'Economy', 'Zone A', 'Express', 'Commercial', 'No', 'city', '', '', '', 'Al Madam', 'Sharjah', 'higher_of', '1800.00', '35.00', '0.00', 'Active'),
            array('United States', 'DHL Express', 'Express', 'Zone 4', 'Express', 'Non-Commercial', 'Yes', 'pincode', '99501', '', '', 'Anchorage', 'Alaska', 'higher_of', '2100.00', '42.00', '0.00', 'Active'),
            array('United States', 'FedEx', 'All', 'Remote Zone', 'Surface', 'Commercial', 'No', 'pincode_range', '', '99502', '99950', 'Remote Alaska', 'Alaska', 'higher_of', '2150.00', '45.00', '0.00', 'Active'),
            array('United Kingdom', 'DHL Express', 'Saver', 'Zone 2', 'Air', 'All', 'No', 'pincode_range', '', 'IV1', 'IV63', 'Highlands & Islands', 'Inverness', 'higher_of', '1850.00', '35.00', '0.00', 'Active'),
            array('Australia', 'All Partners', 'All', 'Outback Tier 3', 'Economy', 'Commercial', 'Yes', 'pincode', '0870', '', '', 'Alice Springs', 'Northern Territory', 'higher_of', '2200.00', '48.00', '0.00', 'Active')
        );

        $row_num = 2;
        foreach ($sample_data as $row) {
            $col_idx = 'A';
            foreach ($row as $val) {
                $sheet->setCellValueExplicit($col_idx . $row_num, $val, PHPExcel_Cell_DataType::TYPE_STRING);
                $col_idx++;
            }
            $row_num++;
        }

        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'oda_charges_template.xls';
        if (ob_get_length()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function import_oda_charges() {
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
        ini_set('memory_limit', '512M');
        set_time_limit(600);

        // Support both 'oda_file' and 'excel_file' input field names
        $file_key = null;
        if (!empty($_FILES['oda_file']['name'])) {
            $file_key = 'oda_file';
        } elseif (!empty($_FILES['excel_file']['name'])) {
            $file_key = 'excel_file';
        }

        if (!$file_key) {
            $this->session->set_flashdata('error', 'Please choose an Excel or CSV file to import.');
            redirect('oda-charges');
        }

        // Validate upload error code
        if ($_FILES[$file_key]['error'] !== UPLOAD_ERR_OK) {
            $upload_errors = array(
                UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize limit on the server.',
                UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the maximum file size specified in the form.',
                UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
                UPLOAD_ERR_NO_FILE    => 'Please choose an Excel or CSV file to import.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on the server.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.'
            );
            $err_msg = isset($upload_errors[$_FILES[$file_key]['error']]) 
                ? $upload_errors[$_FILES[$file_key]['error']] 
                : 'File upload error code: ' . $_FILES[$file_key]['error'];
            $this->session->set_flashdata('error', $err_msg);
            redirect('oda-charges');
        }

        $file_name = $_FILES[$file_key]['name'];
        $tmp_file  = $_FILES[$file_key]['tmp_name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $valid_extensions = array('xls', 'xlsx', 'csv');
        if (!in_array($file_ext, $valid_extensions)) {
            $this->session->set_flashdata('error', 'Invalid file type. Allowed formats: .xls, .xlsx, .csv');
            redirect('oda-charges');
        }

        require_once APPPATH . 'third_party/PHPExcel.php';

        try {
            if ($file_ext === 'csv') {
                $objReader = PHPExcel_IOFactory::createReader('CSV');
            } elseif ($file_ext === 'xlsx') {
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            } else {
                $objReader = PHPExcel_IOFactory::createReader('Excel5');
            }

            $objPHPExcel = $objReader->load($tmp_file);
            $sheet = $objPHPExcel->getActiveSheet();
            $sheetData = $sheet->toArray(null, true, true, false);

            if (empty($sheetData) || count($sheetData) < 2) {
                $this->session->set_flashdata('error', 'The uploaded file contains no data rows.');
                redirect('oda-charges');
            }

            $header_row = $sheetData[0];
            $col_map = array();

            foreach ($header_row as $idx => $heading) {
                if ($heading === null) continue;
                $norm = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string)$heading)));
                switch ($norm) {
                    case 'destinationcountry':
                    case 'country':
                    case 'countryname':
                    case 'destcountry':
                        $col_map['destination_country'] = $idx;
                        break;
                    case 'courierpartner':
                    case 'partner':
                    case 'partnername':
                    case 'courier':
                        $col_map['courier_partner'] = $idx;
                        break;
                    case 'servicetype':
                    case 'service':
                    case 'servicename':
                        $col_map['service_type'] = $idx;
                        break;
                    case 'tierzone':
                    case 'tier':
                    case 'zone':
                    case 'tierorzone':
                        $col_map['tier_zone'] = $idx;
                        break;
                    case 'couriermode':
                    case 'mode':
                    case 'couriermodes':
                        $col_map['courier_mode'] = $idx;
                        break;
                    case 'commercialmode':
                    case 'commercial':
                    case 'commercialmodes':
                        $col_map['commercial_mode'] = $idx;
                        break;
                    case 'nondeliverablelocation':
                    case 'nondeliverable':
                    case 'isnondeliverable':
                    case 'nondeliverablearea':
                    case 'deliverystatus':
                        $col_map['is_non_deliverable'] = $idx;
                        break;
                    case 'locationtype':
                    case 'type':
                        $col_map['location_type'] = $idx;
                        break;
                    case 'geolocationid':
                    case 'geoid':
                        $col_map['geo_location_id'] = $idx;
                        break;
                    case 'postalcode':
                    case 'pincode':
                    case 'zip':
                    case 'zipcode':
                        $col_map['postal_code'] = $idx;
                        break;
                    case 'postalcodefrom':
                    case 'pincodefrom':
                    case 'zipfrom':
                    case 'rangefrom':
                    case 'from':
                        $col_map['postal_code_from'] = $idx;
                        break;
                    case 'postalcodeto':
                    case 'pincodeto':
                    case 'zipto':
                    case 'rangeto':
                    case 'to':
                        $col_map['postal_code_to'] = $idx;
                        break;
                    case 'cityname':
                    case 'city':
                    case 'town':
                        $col_map['city_name'] = $idx;
                        break;
                    case 'statename':
                    case 'state':
                    case 'province':
                        $col_map['state_name'] = $idx;
                        break;
                    case 'calculationtype':
                    case 'calctype':
                    case 'method':
                        $col_map['calculation_type'] = $idx;
                        break;
                    case 'mincharge':
                    case 'minchargeinr':
                    case 'minimumcharge':
                    case 'minimumchargeinr':
                        $col_map['min_charge'] = $idx;
                        break;
                    case 'perkgrate':
                    case 'perkgrateinr':
                    case 'perkg':
                    case 'perkginr':
                    case 'kgrate':
                        $col_map['per_kg_rate'] = $idx;
                        break;
                    case 'flatcharge':
                    case 'flatchargeinr':
                    case 'flatfee':
                    case 'flatfeeinr':
                    case 'flatrate':
                    case 'flatrateinr':
                        $col_map['flat_charge'] = $idx;
                        break;
                    case 'status':
                        $col_map['status'] = $idx;
                        break;
                }
            }

            if (!isset($col_map['destination_country'])) {
                $this->session->set_flashdata('error', 'Missing required column: Destination Country.');
                redirect('oda-charges');
            }

            $records = array();
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                if (empty(array_filter($row, function($v) { return $v !== null && trim($v) !== ''; }))) {
                    continue;
                }

                $record = array();
                foreach ($col_map as $key => $idx) {
                    $record[$key] = isset($row[$idx]) ? trim($row[$idx]) : '';
                }
                $records[] = $record;
            }

            $strategy = $this->input->post('strategy') ?: 'update';
            $result = $this->Master_model->bulk_import_oda_charges($records, $strategy);

            $ins = $result['inserted'];
            $upd = $result['updated'];
            $skp = $result['skipped'];
            $msg = "Import completed! {$ins} rules inserted, {$upd} rules updated" . ($skp > 0 ? ", {$skp} rows skipped." : ".");
            if (!empty($result['errors'])) {
                $msg .= " Issues: " . implode(" ", array_slice($result['errors'], 0, 5));
            }

            $this->Audit_model->log_activity('Import ODA Charges', "Inserted: $ins, Updated: $upd, Skipped: $skp");
            $this->session->set_flashdata('success', $msg);

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Error reading Excel file: ' . $e->getMessage());
        }

        redirect('oda-charges');
    }
}

