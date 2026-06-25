<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    public function __construct($rules = array()) {
        parent::__construct($rules);
    }

    public function is_unique_active($str, $field) {
        sscanf($field, '%[^.].%[^.]', $table, $field);
        
        $query = $this->CI->db->limit(1)->get_where($table, array($field => $str, 'deleted_at' => NULL));
        
        $is_unique = ($query->num_rows() === 0);
        if (!$is_unique) {
            $this->set_message('is_unique_active', 'The {field} is already registered to an active account.');
        }
        return $is_unique;
    }
}
