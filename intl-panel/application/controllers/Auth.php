<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Master_model');
        $this->load->model('Audit_model');
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('auth/login');
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            if ($this->Auth_model->login($email, $password)) {
                $this->session->set_flashdata('success', 'Welcome back, ' . $this->session->userdata('username') . '!');
                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Invalid email address or password.');
                redirect('login');
            }
        }
    }

    public function register() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required|in_list[individual,business]');
        $this->form_validation->set_rules('company_name', 'Company Name', 'callback_check_company_name');
        $this->form_validation->set_rules('mobile', 'Mobile Number', 'required|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('zip_code', 'ZIP Code', 'required');

        if ($this->form_validation->run() === FALSE) {
            $data['countries'] = $this->Master_model->get_countries();
            $this->load->view('auth/register', $data);
        } else {
            $post_data = $this->input->post(NULL, TRUE); // secure all inputs (xss filtered)
            if ($this->Auth_model->register_customer($post_data)) {
                $this->session->set_flashdata('success', 'Registration completed successfully! Please login with your credentials.');
                redirect('login');
            } else {
                $this->session->set_flashdata('error', 'An error occurred during registration. Please try again.');
                redirect('register');
            }
        }
    }

    public function check_company_name($val) {
        $type = $this->input->post('customer_type');
        if ($type == 'business' && empty($val)) {
            $this->form_validation->set_message('check_company_name', 'Company Name is required for business accounts.');
            return FALSE;
        }
        return TRUE;
    }

    public function change_password() {
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }

        $this->form_validation->set_rules('old_password', 'Current Password', 'required');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            // Render template dashboard page with password view
            $data['page_title'] = 'Change Password';
            $data['view_path'] = 'auth/change_password';
            $this->load->view('templates/dashboard_layout', $data);
        } else {
            $user_id = $this->session->userdata('user_id');
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');

            // Verify old password
            $user = $this->db->get_where('users', array('id' => $user_id))->row();
            if ($user && password_verify($old_password, $user->password)) {
                if ($this->Auth_model->change_password($user_id, $new_password)) {
                    $this->session->set_flashdata('success', 'Password updated successfully!');
                    redirect('change-password');
                }
            }
            
            $this->session->set_flashdata('error', 'Your current password was incorrect.');
            redirect('change-password');
        }
    }

    public function logout() {
        $this->Audit_model->log_activity('User Logout', 'User logged out: ' . $this->session->userdata('email'));
        $this->session->sess_destroy();
        redirect('login');
    }
}
