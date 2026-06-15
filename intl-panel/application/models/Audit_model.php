<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model {

    public function log_activity($action, $details = NULL) {
        $user_id = $this->session->userdata('user_id');
        $ip_address = $this->input->ip_address();
        
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' - ' . $this->agent->browser() . ' ' . $this->agent->version();

        $data = array(
            'user_id' => $user_id ? $user_id : NULL,
            'action' => $action,
            'ip_address' => $ip_address,
            'browser_details' => $browser,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert('audit_logs', $data);
    }
}
