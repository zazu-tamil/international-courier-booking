<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->router->fetch_method() !== 'barcode') {
            if (!$this->session->userdata('logged_in')) {
                redirect('login');
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Customer_model');
        $this->load->model('Master_model');
        $this->load->model('Audit_model');
        $this->load->model('Auth_model');
    }

    public function list() {
        $customer_id = NULL;
        if ($this->session->userdata('role_id') == 4) {
            $customer_id = $this->session->userdata('customer_id');
        } else if ($this->session->userdata('role_id') == 3) {
            $data['customers'] = $this->Customer_model->get_franchise_customers($this->session->userdata('user_id'));
        } else {
            $data['customers'] = $this->Customer_model->get_customers();
        }

        $data['page_title'] = 'Shipment Records';
        $data['shipments'] = $this->Shipment_model->get_shipments(NULL, $customer_id);
        $data['view_path'] = 'shipment/shipment_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function view($id) {
        $customer_id = NULL;
        if ($this->session->userdata('role_id') == 4) {
            $customer_id = $this->session->userdata('customer_id');
        }

        $data['shipment'] = $this->Shipment_model->get_shipments($id, $customer_id);
        
        if (!$data['shipment']) {
            $this->session->set_flashdata('error', 'Shipment not found or unauthorized access.');
            redirect('shipments');
        }

        $data['boxes'] = $this->Shipment_model->get_boxes($id);
        $data['items'] = $this->Shipment_model->get_items($id);
        $data['documents'] = $this->Shipment_model->get_documents($id);
        $data['timeline'] = $this->Shipment_model->get_tracking_timeline($id);
        
        // Fetch invoice
        $this->db->select('*');
        $this->db->from('invoices');
        $this->db->where('shipment_id', $id);
        $data['invoice'] = $this->db->get()->row();

        // Fetch dynamic movement stages for dropdown
        $data['movement_stages'] = $this->Master_model->get_movement_stages();

        // Fetch dynamic document types
        $data['document_types'] = $this->Master_model->get_document_types();

        // Fetch signature
        $this->db->select('*');
        $this->db->from('customer_signatures');
        $this->db->where('shipment_id', $id);
        $data['signature'] = $this->db->get()->row();

        // Check active T&C version for display
        $data['active_terms'] = $this->Master_model->get_active_terms();

        $data['page_title'] = 'Shipment Summary - ' . $data['shipment']->awb_number;
        $data['view_path'] = 'shipment/shipment_view';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function send_login($id) {
        // Access Control (Only Super Admin, Branch Admin, and Branch Staff can trigger this)
        if ($this->session->userdata('role_id') == 4 || $this->session->userdata('role_id') == 3) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect('dashboard');
        }

        $shipment = $this->Shipment_model->get_shipments($id);
        if (!$shipment) {
            $this->session->set_flashdata('error', 'Shipment not found.');
            redirect('shipments');
        }

        $customer = $this->db->get_where('customers', array('id' => $shipment->customer_id))->row();
        if (!$customer) {
            $this->session->set_flashdata('error', 'Customer account details not found.');
            redirect('shipments/view/' . $id);
        }

        // Generate a new random password
        $password = 'cust_' . rand(100000, 999999);
        $new_hash = password_hash($password, PASSWORD_BCRYPT);
        
        $this->db->where('id', $customer->user_id);
        $this->db->update('users', array('password' => $new_hash));

        $this->load->model('Notification_model');
        $sent = $this->Notification_model->send_shipment_created_notifications($id, $password);

        if ($sent) {
            $this->session->set_flashdata('success', 'Customer credentials regenerated and sent to customer email successfully.');
        } else {
            $this->session->set_flashdata('error', 'Credentials updated in DB, but email dispatch failed (check Notification Settings / logs).');
        }

        redirect('shipments/view/' . $id);
    }

    public function book() {
        // Customers are BLOCKED from booking shipments directly
        if ($this->session->userdata('role_id') == 4) {
            $this->session->set_flashdata('error', 'Customers are not allowed to create shipment bookings directly.');
            redirect('dashboard');
        }

        // Validate Sender
        $this->form_validation->set_rules('sender_name', 'Sender Name', 'required');
        $this->form_validation->set_rules('sender_mobile', 'Sender Mobile', 'required');
        $this->form_validation->set_rules('sender_address', 'Sender Address', 'required');
        $this->form_validation->set_rules('sender_city', 'Sender City', 'required');
        $this->form_validation->set_rules('sender_state', 'Sender State', 'required');
        $this->form_validation->set_rules('sender_country_id', 'Sender Country', 'required');
        $this->form_validation->set_rules('sender_zip', 'Sender ZIP Code', 'required');

        // Validate Receiver
        $this->form_validation->set_rules('receiver_name', 'Receiver Name', 'required');
        $this->form_validation->set_rules('receiver_mobile', 'Receiver Mobile', 'required');
        $this->form_validation->set_rules('receiver_address', 'Receiver Address', 'required');
        $this->form_validation->set_rules('receiver_city', 'Receiver City', 'required');
        $this->form_validation->set_rules('receiver_state', 'Receiver State', 'required');
        $this->form_validation->set_rules('receiver_country_id', 'Receiver Country', 'required');
        $this->form_validation->set_rules('receiver_zip', 'Receiver ZIP Code', 'required');

        // Validate Consignment
        $this->form_validation->set_rules('booking_date', 'Booking Date', 'required');
        $this->form_validation->set_rules('service_type', 'Service Type', 'required');
        $this->form_validation->set_rules('shipment_type', 'Shipment Type', 'required');
        $this->form_validation->set_rules('origin_country_id', 'Origin Country', 'required');
        $this->form_validation->set_rules('destination_country_id', 'Destination Country', 'required');
        $this->form_validation->set_rules('courier_partner_id', 'Courier Partner', 'required');

        if ($this->input->post('create_customer_account') == '1') {
            $this->form_validation->set_rules('sender_email', 'Sender Email Address', 'required|valid_email|is_unique_active[users.email]');
        } else {
            $this->form_validation->set_rules('customer_id', 'Customer Account', 'required');
        }

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Book New Shipment';
            $data['countries'] = $this->Master_model->get_countries();
            $data['courier_partners'] = $this->Master_model->get_courier_partners();
            $data['service_types'] = $this->Master_model->get_service_types();
            
            if ($this->session->userdata('role_id') == 3) {
                $data['customers'] = $this->Customer_model->get_franchise_customers($this->session->userdata('user_id'));
            } else {
                $data['customers'] = $this->Customer_model->get_customers();
            }

            $data['terms'] = $this->Master_model->get_active_terms();
            $data['view_path'] = 'shipment/shipment_book';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $customer_id = isset($post['customer_id']) ? $post['customer_id'] : NULL;
            $created_msg = '';

            if (isset($post['create_customer_account']) && $post['create_customer_account'] == '1') {
                // Generate a random password
                $password = 'cust_' . rand(100000, 999999);
                
                // Formulate the customer type
                $customer_type = !empty($post['sender_company']) ? 'business' : 'individual';

                // Setup customer data for registration
                $cust_data = array(
                    'email' => $post['sender_email'],
                    'password' => $password,
                    'customer_type' => $customer_type,
                    'name' => $post['sender_name'],
                    'company_name' => $post['sender_company'],
                    'mobile' => $post['sender_mobile'],
                    'address' => $post['sender_address'],
                    'city' => $post['sender_city'],
                    'state' => $post['sender_state'],
                    'country_id' => $post['sender_country_id'],
                    'zip_code' => $post['sender_zip']
                );

                // Call registration
                $new_customer_id = $this->Auth_model->register_customer($cust_data);
                if ($new_customer_id) {
                    $customer_id = $new_customer_id;
                    $created_msg = ' Automatically created customer login account with Username/Email: <strong>' . htmlspecialchars($post['sender_email']) . '</strong> and Password: <strong>' . htmlspecialchars($password) . '</strong>.';
                } else {
                    $this->session->set_flashdata('error', 'Failed to automatically create customer account. Please try again.');
                    redirect('shipments/book');
                }
            }

            // Structure data for model
            $shipment_data = array(
                'awb_number' => isset($post['awb_type']) && $post['awb_type'] == 'manual' ? trim($post['awb_number']) : '',
                'booking_date' => $post['booking_date'],
                'service_type' => $post['service_type'],
                'shipment_type' => $post['shipment_type'],
                'origin_country_id' => $post['origin_country_id'],
                'destination_country_id' => $post['destination_country_id'],
                'courier_partner_id' => $post['courier_partner_id'],
                'customer_id' => $customer_id,
                'total_weight' => $post['total_weight_val'],
                'total_volumetric_weight' => $post['total_volumetric_val'],
                'chargeable_weight' => $post['chargeable_weight_val'],
                'total_declared_value' => $post['total_declared_val'],
                'estimated_charges' => $post['estimated_charges_val'],
                'sender' => array(
                    'name' => $post['sender_name'],
                    'company_name' => $post['sender_company'],
                    'mobile' => $post['sender_mobile'],
                    'alternate_mobile' => $post['sender_alt_mobile'],
                    'email' => $post['sender_email'],
                    'address' => $post['sender_address'],
                    'city' => $post['sender_city'],
                    'state' => $post['sender_state'],
                    'country_id' => $post['sender_country_id'],
                    'zip_code' => $post['sender_zip']
                ),
                'receiver' => array(
                    'name' => $post['receiver_name'],
                    'company_name' => $post['receiver_company'],
                    'mobile' => $post['receiver_mobile'],
                    'alternate_mobile' => $post['receiver_alt_mobile'],
                    'email' => $post['receiver_email'],
                    'address' => $post['receiver_address'],
                    'city' => $post['receiver_city'],
                    'state' => $post['receiver_state'],
                    'country_id' => $post['receiver_country_id'],
                    'zip_code' => $post['receiver_zip']
                ),
                'boxes' => array(),
                'items' => array()
            );

            // Populate boxes
            if (isset($post['box_number']) && is_array($post['box_number'])) {
                for ($i = 0; $i < count($post['box_number']); $i++) {
                    $shipment_data['boxes'][] = array(
                        'box_number' => $post['box_number'][$i],
                        'length' => $post['length'][$i],
                        'width' => $post['width'][$i],
                        'height' => $post['height'][$i],
                        'actual_weight' => $post['actual_weight'][$i],
                        'volumetric_weight' => $post['volumetric_weight'][$i]
                    );
                }
            }

            // Populate items
            if (isset($post['item_desc']) && is_array($post['item_desc'])) {
                for ($i = 0; $i < count($post['item_desc']); $i++) {
                    $shipment_data['items'][] = array(
                        'item_description' => $post['item_desc'][$i],
                        'hs_code' => $post['item_hscode'][$i],
                        'quantity' => $post['item_qty'][$i],
                        'unit_value' => $post['item_value'][$i],
                        'total_value' => $post['item_total'][$i],
                        'country_of_origin_id' => $post['item_origin'][$i],
                        'box_no' => isset($post['item_box_no'][$i]) ? $post['item_box_no'][$i] : 1
                    );
                }
            }

            // Pickup options
            if (isset($post['pickup_required']) && $post['pickup_required'] == '1') {
                $shipment_data['pickup'] = array(
                    'requested' => TRUE,
                    'pickup_date' => $post['pickup_date'],
                    'pickup_time' => $post['pickup_time']
                );
            }

            $shipment_id = $this->Shipment_model->book_shipment($shipment_data);

            if ($shipment_id === 'DUPLICATE_AWB') {
                $this->session->set_flashdata('error', 'The manual AWB number provided already exists in the system. Please try another one.');
                redirect('shipments/book');
            } elseif ($shipment_id) {
                $this->load->model('Notification_model');
                $this->Notification_model->send_shipment_created_notifications($shipment_id, (isset($password) ? $password : NULL));

                $this->session->set_flashdata('success', 'Shipment Booking created successfully!' . $created_msg . ' Customer has been notified for digital verification.');
                redirect('shipments/view/' . $shipment_id);
            } else {
                $this->session->set_flashdata('error', 'Failed to save shipment booking. Please review your details.');
                redirect('shipments/book');
            }
        }
    }

    public function edit($id) {
        // Customers and Franchise Users are BLOCKED from editing shipment bookings
        if ($this->session->userdata('role_id') == 4 || $this->session->userdata('role_id') == 3) {
            $this->session->set_flashdata('error', 'You are not allowed to edit shipment bookings.');
            redirect('dashboard');
        }

        $shipment = $this->Shipment_model->get_shipments($id);
        if (!$shipment) {
            $this->session->set_flashdata('error', 'Shipment not found.');
            redirect('shipments');
        }

        // Validate Sender
        $this->form_validation->set_rules('sender_name', 'Sender Name', 'required');
        $this->form_validation->set_rules('sender_mobile', 'Sender Mobile', 'required');
        $this->form_validation->set_rules('sender_address', 'Sender Address', 'required');
        $this->form_validation->set_rules('sender_city', 'Sender City', 'required');
        $this->form_validation->set_rules('sender_state', 'Sender State', 'required');
        $this->form_validation->set_rules('sender_country_id', 'Sender Country', 'required');
        $this->form_validation->set_rules('sender_zip', 'Sender ZIP Code', 'required');

        // Validate Receiver
        $this->form_validation->set_rules('receiver_name', 'Receiver Name', 'required');
        $this->form_validation->set_rules('receiver_mobile', 'Receiver Mobile', 'required');
        $this->form_validation->set_rules('receiver_address', 'Receiver Address', 'required');
        $this->form_validation->set_rules('receiver_city', 'Receiver City', 'required');
        $this->form_validation->set_rules('receiver_state', 'Receiver State', 'required');
        $this->form_validation->set_rules('receiver_country_id', 'Receiver Country', 'required');
        $this->form_validation->set_rules('receiver_zip', 'Receiver ZIP Code', 'required');

        // Validate Consignment
        $this->form_validation->set_rules('booking_date', 'Booking Date', 'required');
        $this->form_validation->set_rules('service_type', 'Service Type', 'required');
        $this->form_validation->set_rules('shipment_type', 'Shipment Type', 'required');
        $this->form_validation->set_rules('origin_country_id', 'Origin Country', 'required');
        $this->form_validation->set_rules('destination_country_id', 'Destination Country', 'required');
        $this->form_validation->set_rules('courier_partner_id', 'Courier Partner', 'required');
        $this->form_validation->set_rules('customer_id', 'Customer Account', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Edit Shipment Booking - ' . $shipment->awb_number;
            $data['shipment'] = $shipment;
            $data['boxes'] = $this->Shipment_model->get_boxes($id);
            $data['items'] = $this->Shipment_model->get_items($id);
            $data['countries'] = $this->Master_model->get_countries();
            $data['partners'] = $this->Master_model->get_courier_partners();
            $data['service_types'] = $this->Master_model->get_service_types();
            $data['customers'] = $this->Customer_model->get_customers();
            $data['view_path'] = 'shipment/shipment_edit';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);

            // Structure data for model
            $shipment_data = array(
                'booking_date' => $post['booking_date'],
                'service_type' => $post['service_type'],
                'shipment_type' => $post['shipment_type'],
                'origin_country_id' => $post['origin_country_id'],
                'destination_country_id' => $post['destination_country_id'],
                'courier_partner_id' => $post['courier_partner_id'],
                'customer_id' => $post['customer_id'],
                'total_weight' => $post['total_weight_val'],
                'total_volumetric_weight' => $post['total_volumetric_val'],
                'chargeable_weight' => $post['chargeable_weight_val'],
                'total_declared_value' => $post['total_declared_val'],
                'estimated_charges' => $post['estimated_charges_val'],
                'sender' => array(
                    'name' => $post['sender_name'],
                    'company_name' => $post['sender_company'],
                    'mobile' => $post['sender_mobile'],
                    'alternate_mobile' => $post['sender_alt_mobile'],
                    'email' => $post['sender_email'],
                    'address' => $post['sender_address'],
                    'city' => $post['sender_city'],
                    'state' => $post['sender_state'],
                    'country_id' => $post['sender_country_id'],
                    'zip_code' => $post['sender_zip']
                ),
                'receiver' => array(
                    'name' => $post['receiver_name'],
                    'company_name' => $post['receiver_company'],
                    'mobile' => $post['receiver_mobile'],
                    'alternate_mobile' => $post['receiver_alt_mobile'],
                    'email' => $post['receiver_email'],
                    'address' => $post['receiver_address'],
                    'city' => $post['receiver_city'],
                    'state' => $post['receiver_state'],
                    'country_id' => $post['receiver_country_id'],
                    'zip_code' => $post['receiver_zip']
                ),
                'boxes' => array(),
                'items' => array()
            );

            // Populate boxes
            if (isset($post['box_number']) && is_array($post['box_number'])) {
                for ($i = 0; $i < count($post['box_number']); $i++) {
                    $shipment_data['boxes'][] = array(
                        'box_number' => $post['box_number'][$i],
                        'length' => $post['length'][$i],
                        'width' => $post['width'][$i],
                        'height' => $post['height'][$i],
                        'actual_weight' => $post['actual_weight'][$i],
                        'volumetric_weight' => $post['volumetric_weight'][$i]
                    );
                }
            }

            // Populate items
            if (isset($post['item_desc']) && is_array($post['item_desc'])) {
                for ($i = 0; $i < count($post['item_desc']); $i++) {
                    $shipment_data['items'][] = array(
                        'item_description' => $post['item_desc'][$i],
                        'hs_code' => $post['item_hscode'][$i],
                        'quantity' => $post['item_qty'][$i],
                        'unit_value' => $post['item_value'][$i],
                        'total_value' => $post['item_total'][$i],
                        'country_of_origin_id' => $post['item_origin'][$i],
                        'box_no' => isset($post['item_box_no'][$i]) ? $post['item_box_no'][$i] : 1
                    );
                }
            }

            if ($this->Shipment_model->update_shipment($id, $shipment_data)) {
                $this->session->set_flashdata('success', 'Shipment Booking updated successfully!');
                redirect('shipments');
            } else {
                $this->session->set_flashdata('error', 'Failed to update shipment booking. Please review your details.');
                redirect('shipments/edit/' . $id);
            }
        }
    }

    // --- AJAX RATE LOOKUP ---
    public function get_estimated_charges() {
        $origin = $this->input->post('origin');
        $dest = $this->input->post('dest');
        $service = $this->input->post('service');
        $weight = $this->input->post('weight');

        $charges = $this->Master_model->calculate_shipping_charges($origin, $dest, $service, $weight);
        if ($charges) {
            echo json_encode(array('status' => 'success', 'data' => $charges));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'No rates found for the selected route/weight slab.'));
        }
        exit;
    }

    // --- MANUAL TRACKING UPDATE (Staff Only) ---
    public function add_tracking_stage() {
        if ($this->session->userdata('role_id') == 4 || $this->session->userdata('role_id') == 3) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect('dashboard');
        }

        $id = $this->input->post('shipment_id');
        $status = $this->input->post('status');
        $remarks = $this->input->post('remarks');
        $date_time = $this->input->post('date_time');
        
        $formatted_date_time = $date_time ? date('Y-m-d H:i:s', strtotime($date_time)) : date('Y-m-d H:i:s');

        // Check if shipment is customer verified
        $shipment = $this->Shipment_model->get_shipments($id);
        if ($shipment->verification_status == 'Pending') {
            $this->session->set_flashdata('error', 'Shipment movement is BLOCKED until the Customer completes the digital signature & OTP verification.');
            redirect('shipments/view/' . $id);
        }

        if ($this->Shipment_model->add_tracking_stage($id, $status, $remarks, $formatted_date_time)) {
            $this->session->set_flashdata('success', 'Tracking stage updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update tracking stage.');
        }
        redirect('shipments/view/' . $id);
    }

    // --- DOCUMENT ATTACHMENTS ---
    public function upload_document($shipment_id) {
        if ($this->session->userdata('role_id') == 4 || $this->session->userdata('role_id') == 3) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect('dashboard');
        }
        $config['upload_path'] = './assets/shipment_documents/';
        $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg';
        $config['max_size'] = 10240; // 10MB
        
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('doc_file')) {
            $fileData = $this->upload->data();
            
            $doc_data = array(
                'shipment_id' => $shipment_id,
                'doc_type' => $this->input->post('doc_type'),
                'file_path' => 'assets/shipment_documents/' . $fileData['file_name'],
                'uploaded_by' => $this->session->userdata('user_id'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('shipment_documents', $doc_data);
            
            $this->Audit_model->log_activity('Upload Document', 'Shipment ID: ' . $shipment_id . ' Doc Type: ' . $doc_data['doc_type']);
            $this->session->set_flashdata('success', 'Document uploaded and attached successfully.');
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
        }
        redirect('shipments/view/' . $shipment_id);
    }

    // --- BARCODE GENERATION (Code128 PNG) ---
    public function barcode($code) {
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        
        if (ob_get_level()) ob_end_clean();
        
        header("Content-Type: image/png");
        Zend_Barcode::render('code128', 'image', array('text' => $code, 'drawText' => TRUE), array());
        exit;
    }

    // --- PRINTING VIEW HANDLERS ---
    public function print_label($id) {
        $data['shipment'] = $this->Shipment_model->get_shipments($id);
        if (!$data['shipment']) show_404();
        
        $data['boxes'] = $this->Shipment_model->get_boxes($id);
        $this->load->view('shipment/print_label', $data);
    }

    public function print_invoice($id) {
        $data['shipment'] = $this->Shipment_model->get_shipments($id);
        if (!$data['shipment']) show_404();
        
        $data['items'] = $this->Shipment_model->get_items($id);
        
        $this->db->select('*');
        $this->db->from('invoices');
        $this->db->where('shipment_id', $id);
        $data['invoice'] = $this->db->get()->row();

        $this->load->view('shipment/print_invoice', $data);
    }

    public function print_customs($id) {
        $data['shipment'] = $this->Shipment_model->get_shipments($id);
        if (!$data['shipment']) show_404();
        
        $data['items'] = $this->Shipment_model->get_items($id);
        $this->load->view('shipment/print_customs', $data);
    }

    public function print_awb($id) {
        $data['shipment'] = $this->Shipment_model->get_shipments($id);
        if (!$data['shipment']) show_404();
        
        $data['boxes'] = $this->Shipment_model->get_boxes($id);
        $data['items'] = $this->Shipment_model->get_items($id);
        
        // Fetch accepted terms and conditions or current active terms
        $this->db->select('t.*');
        $this->db->from('terms_acceptance_log l');
        $this->db->join('terms_conditions_master t', 't.id = l.terms_version_id');
        $this->db->where('l.shipment_id', $id);
        $data['accepted_terms'] = $this->db->get()->row();
        
        if (!$data['accepted_terms']) {
            $data['accepted_terms'] = $this->Master_model->get_active_terms();
        }

        // Fetch signature
        $this->db->select('*');
        $this->db->from('customer_signatures');
        $this->db->where('shipment_id', $id);
        $data['signature'] = $this->db->get()->row();

        $this->load->view('shipment/print_awb', $data);
    }

    public function delete($id) {
        // Access Control (Only Super Admin can delete records)
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Access Denied. Only Super Admin can delete records.');
            redirect('dashboard');
        }

        $shipment = $this->Shipment_model->get_shipments($id);
        if (!$shipment) {
            $this->session->set_flashdata('error', 'Shipment not found.');
            redirect('shipments');
        }

        if ($this->Shipment_model->delete_shipment($id)) {
            $this->session->set_flashdata('success', 'Shipment soft deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete shipment.');
        }
        redirect('shipments');
    }
}
