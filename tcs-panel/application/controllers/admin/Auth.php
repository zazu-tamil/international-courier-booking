<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', 'user');
        $this->load->library(['form_validation','session']);
        $this->load->helper(['url','form']);
    }

    public function login()
    {
        // If already logged in
        if ($this->session->userdata('logged_in')) {
            return $this->_redirect_by_role($this->session->userdata('role'));
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run()) {
                $username = $this->input->post('username', true);
                $password = $this->input->post('password');

                $user = $this->user->get_by_username($username);

                //if ($user && (password_verify($password, $user->password))) {
                if ($user && ($password === $user->password)) {
                    if ((int)$user->status !== 1) {
                        $this->session->set_flashdata('error', 'Account disabled.');
                        redirect('admin/login');
                    }

                    $this->session->set_userdata([
                        'user_id'   => $user->id,
                        'username'  => $user->username,
                        'email'     => $user->email,
                        'role'      => $user->role,
                        'logged_in' => true
                    ]);

                    return $this->_redirect_by_role($user->role);
                } else {
                    $this->session->set_flashdata('error', 'Invalid username or password.');
                    redirect('admin/login');
                }
            }
        }

        $this->load->view('page/admin/login');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }

    private function _redirect_by_role($role)
    {
        // Change these to your actual dashboards
        if ($role === 'admin') {
            redirect('admin/dashboard');
        }
        redirect('admin/booking-dashboard');
    }
}
