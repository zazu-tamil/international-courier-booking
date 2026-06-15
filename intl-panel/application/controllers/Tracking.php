<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Shipment_model');
    }

    public function index() {
        $data['page_title'] = 'Public AWB Tracking Gateway';
        $data['awb'] = $this->input->get('awb');
        $data['shipment'] = NULL;
        $data['timeline'] = array();

        if (!empty($data['awb'])) {
            $awb = trim($data['awb']);
            // Look up by AWB number or by Customer Mobile
            $this->db->select('id, awb_number');
            $this->db->from('shipment_master');
            $this->db->where('awb_number', $awb);
            $this->db->where('deleted_at IS NULL');
            $q = $this->db->get()->row();

            if (!$q) {
                // Try searching by mobile
                $this->db->select('shipment_master.id, shipment_master.awb_number');
                $this->db->from('shipment_master');
                $this->db->join('shipment_sender', 'shipment_sender.shipment_id = shipment_master.id');
                $this->db->where('shipment_sender.mobile', $awb);
                $this->db->where('shipment_master.deleted_at IS NULL');
                $q = $this->db->get()->row();
            }

            if ($q) {
                $data['shipment'] = $this->Shipment_model->get_shipments($q->id);
                $data['timeline'] = $this->Shipment_model->get_tracking_timeline($q->id);
            } else {
                $data['error'] = 'No shipment record matches that AWB Number or Mobile number.';
            }
        }

        $this->load->view('tracking/public_view', $data);
    }
}
