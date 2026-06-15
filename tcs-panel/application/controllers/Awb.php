<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Awb extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Awb_model');
        $this->load->helper(['form', 'url']);
        $this->load->library(['form_validation', 'session']);
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function create() {
        $data['title'] = "Create International AWB Booking";
        $this->load->view('page/awb/create', $data);
    }

    public function store() {
        // Save AWB + Party + Box + Invoice details
        $awb_id = $this->Awb_model->save_awb($this->input->post());
        // Redirect or return JSON
    }
}
?>
