<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        // Access Control (Franchise Users cannot manage customers, KYC, wallets, or statements)
        if ($this->session->userdata('role_id') == 3) {
            $this->session->set_flashdata('error', 'Access Denied.');
            redirect('dashboard');
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

        // Get pending requests
        $data['pending_requests'] = $this->Customer_model->get_wallet_requests('Pending', $customer_id);

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
            $proof_path = NULL;
            if (!empty($_FILES['proof_file']['name'])) {
                $config['upload_path'] = './uploads/wallet_proofs/';
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 5120; // 5MB
                $config['file_name'] = 'proof_' . $customer_id . '_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('proof_file')) {
                    $uploadData = $this->upload->data();
                    $proof_path = 'uploads/wallet_proofs/' . $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                    redirect('customer/wallet' . ($this->session->userdata('role_id') != 4 ? '?customer_id=' . $customer_id : ''));
                    return;
                }
            }

            $req_data = array(
                'customer_id' => $customer_id,
                'amount' => $amount,
                'payment_mode' => $mode,
                'transaction_id' => $txn,
                'proof_file_path' => $proof_path,
                'status' => 'Pending'
            );

            $this->Customer_model->add_wallet_request($req_data);
            $this->session->set_flashdata('success', 'Wallet load request submitted successfully and is pending approval.');
        } else {
            $this->session->set_flashdata('error', 'Please enter a valid amount.');
        }

        redirect('customer/wallet' . ($this->session->userdata('role_id') != 4 ? '?customer_id=' . $customer_id : ''));
    }
    
    public function wallet_requests() {
        if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('dashboard');
        }
        
        $data['page_title'] = 'Wallet Load Requests';
        $data['requests'] = $this->Customer_model->get_wallet_requests();
        $data['view_path'] = 'customer/wallet_requests_list';
        $this->load->view('templates/dashboard_layout', $data);
    }
    
    public function approve_wallet_request($id) {
        if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('dashboard');
        }
        
        $req = $this->Customer_model->get_wallet_request($id);
        if ($req && $req->status == 'Pending') {
            $this->Customer_model->add_wallet_funds($req->customer_id, $req->amount, 'Approved Credit addition via ' . $req->payment_mode, $req->transaction_id);
            
            $update_data = array(
                'status' => 'Approved',
                'approved_by' => $this->session->userdata('user_id')
            );
            $this->Customer_model->update_wallet_request($id, $update_data);
            $this->session->set_flashdata('success', 'Wallet load request approved and funds credited.');
        } else {
            $this->session->set_flashdata('error', 'Request not found or already processed.');
        }
        redirect('customer/wallet-requests');
    }
    
    public function reject_wallet_request($id) {
        if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('dashboard');
        }
        
        $req = $this->Customer_model->get_wallet_request($id);
        if ($req && $req->status == 'Pending') {
            $update_data = array(
                'status' => 'Rejected',
                'approved_by' => $this->session->userdata('user_id')
            );
            $this->Customer_model->update_wallet_request($id, $update_data);
            $this->session->set_flashdata('success', 'Wallet load request rejected.');
        }
        redirect('customer/wallet-requests');
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

        if ($customer_id) {
            $data['customer'] = $this->Customer_model->get_customers($customer_id);
            $data['statement'] = $this->Customer_model->get_ledger_statement($customer_id);
        } else {
            $data['customer'] = NULL;
            $data['statement'] = [];
        }
        $data['customers'] = $this->Customer_model->get_customers(); // List for dropdown selection

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

    public function list() {
        if ($this->session->userdata('role_id') == 4) {
            redirect('dashboard');
        }
        $data['page_title'] = 'Manage Customers';
        $data['customers'] = $this->Customer_model->get_customers();
        $data['countries'] = $this->Master_model->get_countries();
        $data['view_path'] = 'customer/customer_list';
        $this->load->view('templates/dashboard_layout', $data);
    }

    public function edit($id) {
        if ($this->session->userdata('role_id') == 4) {
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules('name', 'Customer Name', 'required');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required|in_list[individual,business]');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required|numeric');
        $this->form_validation->set_rules('zip_code', 'ZIP Code', 'required');
        $this->form_validation->set_rules('credit_limit', 'Credit Limit', 'required|numeric');
        $this->form_validation->set_rules('credit_days', 'Credit Days', 'required|integer');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[Active,Inactive]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customers');
        } else {
            $post = $this->input->post(NULL, TRUE);
            $customer_data = array(
                'name' => $post['name'],
                'company_name' => $post['company_name'] ? $post['company_name'] : NULL,
                'customer_type' => $post['customer_type'],
                'mobile' => $post['mobile'],
                'email' => $post['email'],
                'address' => $post['address'],
                'city' => $post['city'],
                'state' => $post['state'],
                'country_id' => $post['country_id'],
                'zip_code' => $post['zip_code'],
                'credit_limit' => $post['credit_limit'],
                'credit_days' => $post['credit_days'],
                'status' => $post['status']
            );
            
            if ($this->Customer_model->update_customer_full($id, $customer_data)) {
                $this->session->set_flashdata('success', 'Customer profile updated successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update customer profile.');
            }
            redirect('customers');
        }
    }

    public function delete($id) {
        // Access Control (Only Super Admin can delete records)
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Access Denied. Only Super Admin can delete records.');
            redirect('dashboard');
        }

        if ($this->Customer_model->delete_customer($id)) {
            $this->session->set_flashdata('success', 'Customer profile soft deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete customer.');
        }
        redirect('customers');
    }

    public function manage_kyc_staff($customer_id) {
        if ($this->session->userdata('role_id') == 4) {
            redirect('dashboard');
        }

        $customer = $this->Customer_model->get_customers($customer_id);
        if (!$customer) {
            $this->session->set_flashdata('error', 'Customer profile not found.');
            redirect('kyc-requests');
        }

        $this->form_validation->set_rules('passport_number', 'Passport Number', 'trim');
        $this->form_validation->set_rules('aadhaar_number', 'Aadhaar Number', 'trim|numeric|exact_length[12]');
        $this->form_validation->set_rules('gst_number', 'GST Number', 'trim');
        $this->form_validation->set_rules('pan_number', 'PAN Number', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Manage KYC Documents for ' . $customer->name;
            $data['customer'] = $customer;
            $data['kyc'] = $this->Customer_model->get_kyc_details($customer_id);
            $data['view_path'] = 'customer/kyc_manage_staff';
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
            $this->upload->initialize($config);

            $kyc = $this->Customer_model->get_kyc_details($customer_id);
            
            $id_proof_file = $kyc ? $kyc->id_proof_file : NULL;
            $address_proof_file = $kyc ? $kyc->address_proof_file : NULL;

            // Upload ID Proof
            if (!empty($_FILES['id_proof']['name'])) {
                if ($this->upload->do_upload('id_proof')) {
                    $fileData = $this->upload->data();
                    $id_proof_file = 'assets/kyc_documents/' . $fileData['file_name'];
                } else {
                    $this->session->set_flashdata('error', 'ID Proof Upload Error: ' . $this->upload->display_errors());
                    redirect('kyc-requests/manage/' . $customer_id);
                }
            }

            // Upload Address Proof
            if (!empty($_FILES['address_proof']['name'])) {
                if ($this->upload->do_upload('address_proof')) {
                    $fileData = $this->upload->data();
                    $address_proof_file = 'assets/kyc_documents/' . $fileData['file_name'];
                } else {
                    $this->session->set_flashdata('error', 'Address Proof Upload Error: ' . $this->upload->display_errors());
                    redirect('kyc-requests/manage/' . $customer_id);
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
                'status' => 'pending' // Revert to pending on upload
            );

            if ($this->Customer_model->submit_kyc($customer_id, $kyc_data)) {
                $this->session->set_flashdata('success', 'KYC Documents uploaded successfully.');
            } else {
                $this->session->set_flashdata('error', 'Failed to update KYC documents.');
            }
            redirect('kyc-requests');
        }
    }

    public function delete_kyc_staff($id) {
        // Access Control (Only Super Admin can delete records)
        if ($this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Access Denied. Only Super Admin can delete records.');
            redirect('dashboard');
        }

        $kyc = $this->db->get_where('customer_kyc', array('id' => $id))->row();
        if (!$kyc) {
            $this->session->set_flashdata('error', 'KYC record not found.');
            redirect('kyc-requests');
        }

        // Unlink files if they exist
        if ($kyc->id_proof_file && file_exists('./' . $kyc->id_proof_file)) {
            unlink('./' . $kyc->id_proof_file);
        }
        if ($kyc->address_proof_file && file_exists('./' . $kyc->address_proof_file)) {
            unlink('./' . $kyc->address_proof_file);
        }

        if ($this->Customer_model->delete_kyc($id)) {
            $this->session->set_flashdata('success', 'Customer KYC record cleared/deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete KYC record.');
        }
        redirect('kyc-requests');
    }
}
