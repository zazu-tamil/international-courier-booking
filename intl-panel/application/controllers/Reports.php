<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        
        // Restrict to Super Admin (1) and Branch Admin (2)
        $role_id = $this->session->userdata('role_id');
        if (!in_array($role_id, array(1, 2))) {
            $this->session->set_flashdata('error', 'You do not have permission to access reports.');
            redirect('dashboard');
        }
        
        $this->load->model('Report_model');
    }

    public function tracking_idle() {
        $data['page_title'] = 'Tracking Idle Report';
        $data['view_path'] = 'reports/tracking_idle';
        
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
        if (empty($from_date)) {
            $from_date = date('Y-m-d', strtotime('-30 days')); // Default to last 30 days
        }
        if (empty($to_date)) {
            $to_date = date('Y-m-d'); 
        }
        
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['results'] = $this->Report_model->get_tracking_idle_report($from_date, $to_date);

        $this->load->view('templates/dashboard_layout', $data);
    }
}
