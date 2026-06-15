<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        $this->load->model('Customer_model');
        $this->load->model('Shipment_model');
        $this->load->model('Master_model');
        $this->load->model('Audit_model');
        $this->load->model('Notification_model');
    }

    // --- KYC UPLOADS (Customer portal) ---
    public function kyc_upload() {
        if ($this->session->userdata('role_id') != 4) {
            redirect('dashboard');
        }

        $customer_id = $this->session->userdata('customer_id');

        $this->form_validation->set_rules('passport_number', 'Passport Number', 'trim');
        $this->form_validation->set_rules('aadhaar_number', 'Aadhaar Number', 'numeric|exact_length[12]');
        $this->form_validation->set_rules('gst_number', 'GST Number', 'trim');
        $this->form_validation->set_rules('pan_number', 'PAN Number', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'KYC Document Submission';
            $data['kyc'] = $this->Customer_model->get_kyc_details($customer_id);
            $data['view_path'] = 'customer/kyc_upload';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            // File Uploads
            $config['upload_path'] = './assets/kyc_documents/';
            $config['allowed_types'] = 'gif|jpg|png|pdf|jpeg';
            $config['max_size'] = 5120; // 5MB
            
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->load->library('upload', $config);

            $kyc = $this->Customer_model->get_kyc_details($customer_id);
            
            $id_proof_file = $kyc ? $kyc->id_proof_file : NULL;
            $address_proof_file = $kyc ? $kyc->address_proof_file : NULL;

            // Upload ID Proof
            if (!empty($_FILES['id_proof']['name'])) {
                if ($this->upload->do_upload('id_proof')) {
                    $fileData = $this->upload->data();
                    $id_proof_file = 'assets/kyc_documents/' . $fileData['file_name'];
                }
            }

            // Upload Address Proof
            if (!empty($_FILES['address_proof']['name'])) {
                if ($this->upload->do_upload('address_proof')) {
                    $fileData = $this->upload->data();
                    $address_proof_file = 'assets/kyc_documents/' . $fileData['file_name'];
                }
            }

            $post = $this->input->post(NULL, TRUE);
            $kyc_data = array(
                'passport_number' => $post['passport_number'] ? $post['passport_number'] : NULL,
                'aadhaar_number' => $post['aadhaar_number'] ? $post['aadhaar_number'] : NULL,
                'gst_number' => $post['gst_number'] ? $post['gst_number'] : NULL,
                'pan_number' => $post['pan_number'] ? $post['pan_number'] : NULL,
                'trade_license' => $post['trade_license'] ? $post['trade_license'] : NULL,
                'company_registration_certificate' => $post['company_registration_certificate'] ? $post['company_registration_certificate'] : NULL,
                'authorized_person' => $post['authorized_person'] ? $post['authorized_person'] : NULL,
                'id_proof_file' => $id_proof_file,
                'address_proof_file' => $address_proof_file,
                'status' => 'pending' // Revert to pending on re-upload
            );

            $this->Customer_model->submit_kyc($customer_id, $kyc_data);
            $this->session->set_flashdata('success', 'KYC Documents uploaded successfully. Awaiting Admin verification.');
            redirect('customer/kyc');
        }
    }

    // --- KYC APPROVALS (Staff portal) ---
    public function kyc_requests() {
        if ($this->session->userdata('role_id') == 4) {
            redirect('dashboard');
        }

        $data['page_title'] = 'KYC Request Approvals';
        $data['kyc_list'] = $this->Customer_model->get_all_kyc();
        $data['view_path'] = 'customer/kyc_requests_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function kyc_review($id) {
        if ($this->session->userdata('role_id') == 4) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('status', 'Action Status', 'required|in_list[approved,rejected]');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Review KYC Documents';
            
            $this->db->select('customer_kyc.*, customers.name as customer_name, customers.company_name, customers.customer_type, customers.email, customers.mobile');
            $this->db->from('customer_kyc');
            $this->db->join('customers', 'customers.id = customer_kyc.customer_id');
            $this->db->where('customer_kyc.id', $id);
            $data['kyc'] = $this->db->get()->row();
            
            $data['view_path'] = 'customer/kyc_review';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $status = $this->input->post('status');
            $reason = $this->input->post('reject_reason');
            $this->Customer_model->approve_reject_kyc($id, $status, $reason);
            $this->session->set_flashdata('success', 'KYC review status updated to: ' . ucfirst($status));
            redirect('kyc-requests');
        }
    }

    // --- WALLET (Customer portal & staff view) ---
    public function wallet() {
        $customer_id = NULL;
        if ($this->session->userdata('role_id') == 4) {
            $customer_id = $this->session->userdata('customer_id');
        } else {
            $customer_id = $this->input->get('customer_id');
            if (!$customer_id) {
                // If staff tries to load without param, redirect
                $this->session->set_flashdata('error', 'Please select a customer profile.');
                redirect('shipments');
            }
        }

        $data['customer'] = $this->Customer_model->get_customers($customer_id);
        $data['balance'] = $this->Customer_model->get_wallet_balance($customer_id);
        
        $this->db->order_by('id', 'DESC');
        $data['transactions'] = $this->db->get_where('customer_wallet_transactions', array('customer_id' => $customer_id))->result();

        $data['page_title'] = 'Wallet Ledger Balance';
        $data['view_path'] = 'customer/wallet_dashboard';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function add_funds() {
        $customer_id = NULL;
        if ($this->session->userdata('role_id') == 4) {
            $customer_id = $this->session->userdata('customer_id');
        } else {
            $customer_id = $this->input->post('customer_id');
        }

        $amount = $this->input->post('amount');
        $mode = $this->input->post('payment_mode');
        $txn = $this->input->post('transaction_id');

        if ($amount > 0 && $customer_id) {
            $this->Customer_model->add_wallet_funds($customer_id, $amount, 'Credit addition via ' . $mode, $txn);
            $this->session->set_flashdata('success', 'Funds added to customer wallet balance successfully.');
        } else {
            $this->session->set_flashdata('error', 'Please enter a valid amount.');
        }

        redirect('customer/wallet' . ($this->session->userdata('role_id') != 4 ? '?customer_id=' . $customer_id : ''));
    }

    // --- STATEMENTS (Ledger entries) ---
    public function statement() {
        $customer_id = NULL;
        if ($this->session->userdata('role_id') == 4) {
            $customer_id = $this->session->userdata('customer_id');
        } else {
            $customer_id = $this->input->get('customer_id');
            if (!$customer_id) {
                // Get first active customer
                $cust = $this->Customer_model->get_customers();
                if (!empty($cust)) {
                    $customer_id = $cust[0]->id;
                }
            }
        }

        $data['customer'] = $this->Customer_model->get_customers($customer_id);
        $data['customers'] = $this->Customer_model->get_customers(); // List for dropdown selection
        $data['statement'] = $this->Customer_model->get_ledger_statement($customer_id);

        $data['page_title'] = 'Customer Statement of Accounts';
        $data['view_path'] = 'customer/statement_view';
        $this->load->view('templates/dashboard_layout', $data);
    }

    // --- RECEIVE PAYMENTS (Staff Only) ---
    public function receive_payment($invoice_id) {
        if ($this->session->userdata('role_id') == 4) {
            redirect('dashboard');
        }

        $invoice = $this->db->get_where('invoices', array('id' => $invoice_id))->row();
        if (!$invoice) {
            $this->session->set_flashdata('error', 'Invoice record not found.');
            redirect('shipments');
        }

        $mode = $this->input->post('payment_mode');
        $txn_id = $this->input->post('transaction_id');
        $remarks = $this->input->post('remarks');
        $amount = $invoice->final_amount; // Standard full amount receipt

        if ($this->Customer_model->process_payment($invoice_id, $mode, $amount, $txn_id, $remarks)) {
            $this->session->set_flashdata('success', 'Invoice payment processed successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to process payment.');
        }

        redirect('shipments/view/' . $invoice->shipment_id);
    }

    // --- CUSTOMER SHIPMENT VERIFICATION MODULE ---
    public function verify_shipment($id) {
        if ($this->session->userdata('role_id') != 4) {
            $this->session->set_flashdata('error', 'Only customers can verify shipments.');
            redirect('dashboard');
        }

        $customer_id = $this->session->userdata('customer_id');
        $data['shipment'] = $this->Shipment_model->get_shipments($id, $customer_id);

        if (!$data['shipment']) {
            $this->session->set_flashdata('error', 'Shipment not found or unauthorized.');
            redirect('dashboard');
        }

        $data['boxes'] = $this->Shipment_model->get_boxes($id);
        $data['items'] = $this->Shipment_model->get_items($id);
        $data['active_terms'] = $this->Master_model->get_active_terms();
        
        // Generate OTP and save to session if not done already
        $otp = rand(100000, 999999);
        $this->session->set_userdata('verification_otp_' . $id, $otp);

        // Send OTP Notification Mock across all enabled channels
        $this->Notification_model->send_otp_notifications($id, $otp);
        
        // Output flash message detailing the mocked SMS/WhatsApp code so the user can easily copy it for verification
        $this->session->set_flashdata('otp_info', 'Verification OTP code dispatched (Mocked SMS/WA/Email): <strong>' . $otp . '</strong>');

        $data['page_title'] = 'Customer Shipment Verification Wizard';
        $data['view_path'] = 'customer/verify_wizard';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function submit_signature() {
        $shipment_id = $this->input->post('shipment_id');
        $signature_data = $this->input->post('signature_data');
        $customer_id = $this->session->userdata('customer_id');

        if ($shipment_id && $signature_data && $customer_id) {
            $success = $this->Shipment_model->submit_customer_signature($shipment_id, $customer_id, $signature_data);
            if ($success) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to save signature image.'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid inputs.'));
        }
        exit;
    }

    public function submit_verification() {
        $shipment_id = $this->input->post('shipment_id');
        $otp_entered = $this->input->post('otp_code');
        $terms_id = $this->input->post('terms_id');
        $customer_id = $this->session->userdata('customer_id');

        $saved_otp = $this->session->userdata('verification_otp_' . $shipment_id);

        if ($otp_entered == $saved_otp) {
            $this->Shipment_model->submit_terms_declaration_otp($shipment_id, $customer_id, $terms_id, $otp_entered);
            $this->session->unset_userdata('verification_otp_' . $shipment_id);
            $this->session->set_flashdata('success', 'Thank you! Shipment has been successfully verified, signed, and authorized for transit release.');
            redirect('shipments/view/' . $shipment_id);
        } else {
            $this->session->set_flashdata('error', 'The OTP code you entered is invalid. Please try again.');
            redirect('customer/verify/' . $shipment_id);
        }
    }
}
