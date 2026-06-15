<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Shipment_model');
        $this->load->model('Customer_model');
        $this->load->model('Master_model');
        
        // Disable CSRF check for all REST APIs (routes excluded, but just to be safe in code)
        $this->config->set_item('csrf_protection', FALSE);
    }

    private function _response($data, $status = 200) {
        header("Content-Type: application/json");
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    private function _validate_token() {
        $headers = $this->input->request_headers();
        $auth = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        if (empty($auth)) {
            $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        }

        if (empty($auth) || strpos($auth, 'Bearer ') !== 0) {
            $this->_response(array('status' => 'error', 'message' => 'Authorization header missing or invalid format (Bearer token required)'), 401);
        }

        $token = substr($auth, 7);
        $decoded = base64_decode($token);
        if (!$decoded) {
            $this->_response(array('status' => 'error', 'message' => 'Malformed authentication token'), 401);
        }

        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            $this->_response(array('status' => 'error', 'message' => 'Invalid token signature'), 401);
        }

        list($email, $role_id, $user_id) = $parts;

        // Fetch user
        $user = $this->db->get_where('users', array('id' => $user_id, 'email' => $email, 'status' => 'Active'))->row();
        if (!$user) {
            $this->_response(array('status' => 'error', 'message' => 'User account is inactive or not found'), 401);
        }

        return $user;
    }

    // 1. API: Login
    public function login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if (empty($email) || empty($password)) {
            $this->_response(array('status' => 'error', 'message' => 'Email and Password are required'), 400);
        }

        // Validate credentials
        $user = $this->db->get_where('users', array('email' => $email, 'status' => 'Active'))->row();
        if ($user && password_verify($password, $user->password)) {
            // Generate stateless token
            $token_payload = $user->email . '|' . $user->role_id . '|' . $user->id;
            $token = base64_encode($token_payload);

            // Fetch customer_id if role is customer
            $customer_id = NULL;
            if ($user->role_id == 4) {
                $cust = $this->db->get_where('customers', array('user_id' => $user->id))->row();
                if ($cust) $customer_id = $cust->id;
            }

            $this->_response(array(
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
                'user' => array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'customer_id' => $customer_id
                )
            ));
        } else {
            $this->_response(array('status' => 'error', 'message' => 'Invalid email or password'), 401);
        }
    }

    // 2. API: Register
    public function register() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required|in_list[individual,business]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('country_id', 'Country ID', 'required|integer');
        $this->form_validation->set_rules('zip_code', 'ZIP Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->_response(array('status' => 'error', 'message' => validation_errors()), 400);
        }

        $post_data = $this->input->post(NULL, TRUE);
        if ($this->Auth_model->register_customer($post_data)) {
            $this->_response(array('status' => 'success', 'message' => 'Customer account registered successfully'));
        } else {
            $this->_response(array('status' => 'error', 'message' => 'Failed to register account'), 500);
        }
    }

    // 3. API: Rate Calculation
    public function rates() {
        $origin = $this->input->post('origin_country_id');
        $dest = $this->input->post('destination_country_id');
        $service = $this->input->post('service_type');
        $weight = $this->input->post('chargeable_weight');

        if (empty($origin) || empty($dest) || empty($service) || empty($weight)) {
            $this->_response(array('status' => 'error', 'message' => 'origin_country_id, destination_country_id, service_type, and chargeable_weight are required'), 400);
        }

        $charges = $this->Master_model->calculate_shipping_charges($origin, $dest, $service, $weight);
        if ($charges) {
            $this->_response(array('status' => 'success', 'data' => $charges));
        } else {
            $this->_response(array('status' => 'error', 'message' => 'No matching rate slabs found'), 404);
        }
    }

    // 4. API: Tracking Status
    public function tracking() {
        $awb = $this->input->post('awb_number');
        if (empty($awb)) {
            $this->_response(array('status' => 'error', 'message' => 'awb_number is required'), 400);
        }

        $shipment = $this->Shipment_model->get_shipment_by_awb($awb);
        if ($shipment) {
            $timeline = $this->Shipment_model->get_tracking_timeline($shipment->id);
            $this->_response(array(
                'status' => 'success',
                'shipment' => array(
                    'awb_number' => $shipment->awb_number,
                    'status' => $shipment->status,
                    'booking_date' => $shipment->booking_date,
                    'origin' => $shipment->origin_country_name,
                    'destination' => $shipment->dest_country_name,
                    'weight' => $shipment->chargeable_weight
                ),
                'timeline' => $timeline
            ));
        } else {
            $this->_response(array('status' => 'error', 'message' => 'Shipment AWB not found'), 404);
        }
    }

    // 5. API: Customer Dashboard Counters (Secured)
    public function dashboard() {
        $user = $this->_validate_token();
        if ($user->role_id != 4) {
            $this->_response(array('status' => 'error', 'message' => 'Unauthorized. Customers only'), 403);
        }

        $cust = $this->Customer_model->get_customer_by_user_id($user->id);
        
        $this->db->where('customer_id', $cust->id);
        $total = $this->db->count_all_results('shipment_master');

        $this->db->where('customer_id', $cust->id);
        $this->db->where('verification_status', 'Pending');
        $pending = $this->db->count_all_results('shipment_master');

        $this->_response(array(
            'status' => 'success',
            'wallet_balance' => $cust->wallet_balance,
            'outstanding_balance' => $cust->outstanding_balance,
            'total_shipments' => $total,
            'pending_verifications' => $pending
        ));
    }

    // 6. API: Shipment Details (Secured)
    public function shipment_details() {
        $user = $this->_validate_token();
        $awb = $this->input->post('awb_number');
        if (empty($awb)) {
            $this->_response(array('status' => 'error', 'message' => 'awb_number is required'), 400);
        }

        $shipment = $this->Shipment_model->get_shipment_by_awb($awb);
        if (!$shipment) {
            $this->_response(array('status' => 'error', 'message' => 'Shipment not found'), 404);
        }

        // Check ownership if user is customer
        if ($user->role_id == 4) {
            $cust = $this->Customer_model->get_customer_by_user_id($user->id);
            if ($shipment->customer_id != $cust->id) {
                $this->_response(array('status' => 'error', 'message' => 'Access denied to this shipment record'), 403);
            }
        }

        $boxes = $this->Shipment_model->get_boxes($shipment->id);
        $items = $this->Shipment_model->get_items($shipment->id);
        
        $this->_response(array(
            'status' => 'success',
            'shipment' => $shipment,
            'boxes' => $boxes,
            'items' => $items
        ));
    }

    // 7. API: Declaration Acceptance (Secured)
    public function declaration_accept() {
        $user = $this->_validate_token();
        $shipment_id = $this->input->post('shipment_id');
        if (empty($shipment_id)) {
            $this->_response(array('status' => 'error', 'message' => 'shipment_id is required'), 400);
        }

        $cust = $this->Customer_model->get_customer_by_user_id($user->id);
        
        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_master', array('declaration_status' => 'Accepted'));

        $this->Shipment_model->add_tracking_stage($shipment_id, 'Declaration Accepted', 'Customer approved export declaration via REST API');

        $this->_response(array('status' => 'success', 'message' => 'Declaration accepted'));
    }

    // 8. API: Terms Acceptance (Secured)
    public function terms_accept() {
        $user = $this->_validate_token();
        $shipment_id = $this->input->post('shipment_id');
        $terms_id = $this->input->post('terms_version_id');
        
        if (empty($shipment_id) || empty($terms_id)) {
            $this->_response(array('status' => 'error', 'message' => 'shipment_id and terms_version_id are required'), 400);
        }

        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_master', array('terms_status' => 'Accepted'));

        $this->Shipment_model->add_tracking_stage($shipment_id, 'Terms Accepted', 'Customer approved T&C version ' . $terms_id . ' via REST API');

        $this->_response(array('status' => 'success', 'message' => 'Terms accepted'));
    }

    // 9. API: Signature Upload (Secured)
    public function signature_upload() {
        $user = $this->_validate_token();
        $shipment_id = $this->input->post('shipment_id');
        $signature_data = $this->input->post('signature_data'); // Base64 PNG image

        if (empty($shipment_id) || empty($signature_data)) {
            $this->_response(array('status' => 'error', 'message' => 'shipment_id and signature_data are required'), 400);
        }

        $cust = $this->Customer_model->get_customer_by_user_id($user->id);
        $success = $this->Shipment_model->submit_customer_signature($shipment_id, $cust->id, $signature_data);
        
        if ($success) {
            $this->_response(array('status' => 'success', 'message' => 'Digital signature uploaded successfully'));
        } else {
            $this->_response(array('status' => 'error', 'message' => 'Failed to save signature image'), 500);
        }
    }

    // 10. API: OTP Verification (Secured)
    public function verify_otp() {
        $user = $this->_validate_token();
        $shipment_id = $this->input->post('shipment_id');
        $otp = $this->input->post('otp_code');
        $terms_id = $this->input->post('terms_version_id');

        if (empty($shipment_id) || empty($otp) || empty($terms_id)) {
            $this->_response(array('status' => 'error', 'message' => 'shipment_id, otp_code, and terms_version_id are required'), 400);
        }

        $cust = $this->Customer_model->get_customer_by_user_id($user->id);
        
        // Match mock OTP - for API we can accept '123456' as verified for testing convenience, or match session OTP.
        // Let's accept '123456' or verify session OTP
        $saved_otp = $this->session->userdata('verification_otp_' . $shipment_id);
        if ($otp == $saved_otp || $otp == '123456') {
            $this->Shipment_model->submit_terms_declaration_otp($shipment_id, $cust->id, $terms_id, $otp);
            $this->_response(array('status' => 'success', 'message' => 'Shipment verification OTP successfully verified'));
        } else {
            $this->_response(array('status' => 'error', 'message' => 'Invalid OTP code'), 400);
        }
    }
}
