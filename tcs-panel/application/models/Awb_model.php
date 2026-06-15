<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Awb_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Save AWB Master record
     */
    public function save_awb($post) {
        $awb_number = $this->generate_awb_number();
        $data = [
            'awb_number'           => $awb_number,
            'company_name'         => $post['company_name'],
            'origin_country_code'  => $post['origin_country_code'],
            'origin_country_name'  => $post['origin_country_name'],
            'origin_zone'          => $post['origin_zone'],
            'destination_country_code' => $post['destination_country_code'],
            'destination_country_name' => $post['destination_country_name'],
            'product_type'         => $post['product_type'],
            'booking_date'         => $post['booking_date'],
            'forwarding_date'      => isset($post['forwarding_date']) ? $post['forwarding_date'] : NULL,
            'service_name'         => $post['service_name'],
            'forwarding_number'    => isset($post['forwarding_number']) ? $post['forwarding_number'] : NULL,
            'shipment_value'       => $post['shipment_value'],
            'shipment_currency'    => $post['shipment_currency'],
            'invoice_date'         => $post['invoice_date'],
            'invoice_number'       => $post['invoice_number'],
            'content'              => $post['content'],
            'payment_mode'         => $post['payment_mode'],
            'created_by'           => $this->session->userdata(SESS_HD . 'user_id')
        ];

        $this->db->insert('awb_master', $data);
        return $this->db->insert_id(); // return the AWB ID
    }

    /**
     * Save Party (shipper/receiver)
     */
    public function save_party($awb_id, $type, $post) {
        $data = [
            'awb_id'       => $awb_id,
            'party_type'   => $type,
            'code'         => $post['code'],
            'company'      => $post['company'],
            'person_name'  => $post['person_name'],
            'address1'     => $post['address1'],
            'address2'     => $post['address2'],
            'address3'     => $post['address3'],
            'address4'     => $post['address4'],
            'pin_code'     => $post['pin'],
            'city'         => $post['city'],
            'state'        => $post['state'],
            'country'      => $post['country'],
            'mobile'       => $post['mobile'],
            'alt_mobile'   => isset($post['alt_mobile']) ? $post['alt_mobile'] : '',
            'email'        => $post['email'],
            'kyc_type'     => isset($post['kyc_type']) ? $post['kyc_type'] : NULL,
            'kyc_number'   => isset($post['kyc_number']) ? $post['kyc_number'] : NULL,
            'kyc_doc_front'=> isset($post['kyc_doc_front']) ? $post['kyc_doc_front'] : NULL,
            'kyc_doc_back' => isset($post['kyc_doc_back']) ? $post['kyc_doc_back'] : NULL
        ];

        $this->db->insert('awb_party', $data);
    }

    /**
     * Save Box Dimensions
     */
    public function save_box($awb_id, $boxes) {
        foreach ($boxes as $box) {
            $vol_weight = ($box['length'] * $box['breadth'] * $box['height']) / 5000;
            $chargeable = max($box['actual_weight'], $vol_weight);

            $data = [
                'awb_id'           => $awb_id,
                'box_no'           => $box['box_no'],
                'actual_weight'    => $box['actual_weight'],
                'length_cm'        => $box['length'],
                'breadth_cm'       => $box['breadth'],
                'height_cm'        => $box['height'],
                'volumetric_weight'=> $vol_weight,
                'chargeable_weight'=> $chargeable
            ];
            $this->db->insert('awb_boxes', $data);
        }
    }

    /**
     * Save Invoice Header
     */
    public function save_invoice($awb_id, $post) {
        $data = [
            'awb_id'       => $awb_id,
            'invoice_type' => $post['invoice_type'],
            'currency'     => $post['currency'],
            'inco_terms'   => $post['inco_terms'],
            'note'         => $post['note'],
            'gift_note'    => $post['gift_note']
        ];
        $this->db->insert('awb_invoice', $data);
        return $this->db->insert_id();
    }

    /**
     * Save Invoice Items
     */
    public function save_invoice_items($invoice_id, $items) {
        foreach ($items as $item) {
            $data = [
                'invoice_id'   => $invoice_id,
                'box_no'       => $item['box_no'],
                'sl_no'        => $item['sl_no'],
                'description'  => $item['description'],
                'hs_code'      => $item['hs_code'],
                'unit_type'    => $item['unit_type'],
                'quantity'     => $item['quantity'],
                'unit_rate'    => $item['unit_rate'],
                'amount'       => $item['amount']
            ];
            $this->db->insert('awb_invoice_items', $data);
        }
    }

    /**
     * Generate AWB Number
     */
    public function generate_awb_number() {
        $prefix = 'AWB' . date('Ymd');
        $last = $this->db->select('awb_number')
                         ->like('awb_number', $prefix)
                         ->order_by('awb_number', 'DESC')
                         ->limit(1)
                         ->get('awb_master')->row();

        $last_id = ($last) ? (int)substr($last->awb_number, -4) : 0;
        $new_id = str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $new_id;
    }

    /**
     * Fetch AWB Details (For PDF/Excel)
     */
    public function get_awb_details($awb_id) {
        return $this->db->get_where('awb_master', ['awb_id' => $awb_id])->row_array();
    }

    public function get_awb_excel_data($awb_id) {
        $this->db->select('a.*, b.*, c.*');
        $this->db->from('awb_master a');
        $this->db->join('awb_invoice b', 'a.awb_id = b.awb_id', 'left');
        $this->db->join('awb_invoice_items c', 'b.id = c.invoice_id', 'left');
        $this->db->where('a.awb_id', $awb_id);
        return $this->db->get()->result_array();
    }
}
