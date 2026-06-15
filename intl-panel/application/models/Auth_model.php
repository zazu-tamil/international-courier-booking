<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    public function login($email, $password) {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.email', $email);
        $this->db->where('users.status', 'Active');
        $this->db->where('users.deleted_at IS NULL');

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                // Update last login
                $this->db->where('id', $user->id);
                $this->db->update('users', array('last_login' => date('Y-m-d H:i:s')));
                
                // If role is Customer, get customer_id
                $customer_id = NULL;
                if ($user->role_id == 4) {
                    $cust_q = $this->db->get_where('customers', array('user_id' => $user->id));
                    if ($cust_q->num_rows() == 1) {
                        $customer_id = $cust_q->row()->id;
                    }
                }

                $session_data = array(
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role_id' => $user->role_id,
                    'role_name' => $user->role_name,
                    'branch_id' => $user->branch_id,
                    'franchise_id' => $user->franchise_id,
                    'customer_id' => $customer_id,
                    'logged_in' => TRUE
                );
                
                $this->session->set_userdata($session_data);
                $this->Audit_model->log_activity('User Login', 'Successfully logged in: ' . $user->email);
                return TRUE;
            }
        }
        $this->Audit_model->log_activity('Failed Login Attempt', 'Email: ' . $email);
        return FALSE;
    }

    public function register_customer($data) {
        $this->db->trans_start();

        // 1. Insert into users table
        $user_data = array(
            'username' => $data['email'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role_id' => 4, // Customer role
            'branch_id' => NULL,
            'franchise_id' => NULL,
            'status' => 'Active',
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('users', $user_data);
        $user_id = $this->db->insert_id();

        // 2. Insert into customers table
        $customer_data = array(
            'user_id' => $user_id,
            'customer_type' => $data['customer_type'],
            'name' => $data['name'],
            'company_name' => (isset($data['company_name']) && $data['company_name']) ? $data['company_name'] : NULL,
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'country_id' => $data['country_id'],
            'zip_code' => $data['zip_code'],
            'credit_limit' => 0.00,
            'credit_days' => 0,
            'outstanding_balance' => 0.00,
            'status' => 'Active',
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('customers', $customer_data);
        $customer_id = $this->db->insert_id();

        // 3. Create wallet
        $this->db->insert('customer_wallet', array(
            'customer_id' => $customer_id,
            'balance' => 0.00,
            'created_at' => date('Y-m-d H:i:s')
        ));

        // 4. Create KYC pending placeholder
        $kyc_data = array(
            'customer_id' => $customer_id,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('customer_kyc', $kyc_data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        $this->Audit_model->log_activity('Customer Registered', 'Customer email: ' . $data['email']);
        return $customer_id;
    }

    public function change_password($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $this->db->where('id', $user_id);
        $result = $this->db->update('users', array('password' => $hashed_password));
        
        if ($result) {
            $this->Audit_model->log_activity('Change Password', 'User changed their password');
            return TRUE;
        }
        return FALSE;
    }
}
