<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
        $this->load->model('Customer_model');
        $this->load->model('Master_model');
    }

    public function generate_awb_number() {
        //$year = date('Y');
        //$prefix = "CSYN-INT-" . $year . "-";
        $prefix = "INT41";
        
        $this->db->select('awb_number');
        $this->db->from('shipment_master');
        $this->db->like('awb_number', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_awb = $query->row()->awb_number;
            $parts = explode('-', $last_awb);
            $seq = intval(end($parts)) + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 6, '0', STR_PAD_LEFT);
    }

    public function get_shipments($id = NULL, $customer_id = NULL) {
        $this->db->select('shipment_master.*, 
            s.name as sender_name, s.company_name as sender_company, s.mobile as sender_mobile, s.alternate_mobile as sender_alt_mobile, s.whatsapp_number as sender_whatsapp, s.email as sender_email, s.address as sender_address, s.city as sender_city, s.state as sender_state, s.zip_code as sender_zip,
            r.name as receiver_name, r.company_name as receiver_company, r.mobile as receiver_mobile, r.alternate_mobile as receiver_alt_mobile, r.whatsapp_number as receiver_whatsapp, r.email as receiver_email, r.address as receiver_address, r.city as receiver_city, r.state as receiver_state, r.zip_code as receiver_zip,
            co.country_name as origin_country_name, cd.country_name as dest_country_name,
            cp.partner_name as courier_partner_name,
            cust.name as customer_profile_name, cust.customer_type as customer_profile_type,
            ck.status as kyc_status');
        $this->db->from('shipment_master');
        $this->db->join('shipment_sender s', 's.shipment_id = shipment_master.id');
        $this->db->join('shipment_receiver r', 'r.shipment_id = shipment_master.id');
        $this->db->join('countries co', 'co.id = shipment_master.origin_country_id');
        $this->db->join('countries cd', 'cd.id = shipment_master.destination_country_id');
        $this->db->join('courier_partners cp', 'cp.id = shipment_master.courier_partner_id');
        $this->db->join('customers cust', 'cust.id = shipment_master.customer_id');
        $this->db->join('customer_kyc ck', 'ck.customer_id = shipment_master.customer_id', 'left');
        $this->db->where('shipment_master.deleted_at IS NULL');

        if ($this->session->userdata('role_id') == 3) {
            $this->db->where('shipment_master.created_by', $this->session->userdata('user_id'));
        }

        if ($customer_id) {
            $this->db->where('shipment_master.customer_id', $customer_id);
        }

        if ($id) {
            $this->db->where('shipment_master.id', $id);
            return $this->db->get()->row();
        }
        $this->db->order_by('shipment_master.booking_date', 'DESC');
        $this->db->order_by('shipment_master.id', 'DESC');
        return $this->db->get()->result();
    }

    public function get_shipment_by_awb($awb_number) {
        $this->db->select('shipment_master.*, 
            s.name as sender_name, s.company_name as sender_company, s.mobile as sender_mobile, s.alternate_mobile as sender_alt_mobile, s.email as sender_email, s.address as sender_address, s.city as sender_city, s.state as sender_state, s.zip_code as sender_zip,
            r.name as receiver_name, r.company_name as receiver_company, r.mobile as receiver_mobile, r.alternate_mobile as receiver_alt_mobile, r.email as receiver_email, r.address as receiver_address, r.city as receiver_city, r.state as receiver_state, r.zip_code as receiver_zip,
            co.country_name as origin_country_name, cd.country_name as dest_country_name,
            cp.partner_name as courier_partner_name,
            cust.name as customer_profile_name, cust.customer_type as customer_profile_type,
            ck.status as kyc_status');
        $this->db->from('shipment_master');
        $this->db->join('shipment_sender s', 's.shipment_id = shipment_master.id');
        $this->db->join('shipment_receiver r', 'r.shipment_id = shipment_master.id');
        $this->db->join('countries co', 'co.id = shipment_master.origin_country_id');
        $this->db->join('countries cd', 'cd.id = shipment_master.destination_country_id');
        $this->db->join('courier_partners cp', 'cp.id = shipment_master.courier_partner_id');
        $this->db->join('customers cust', 'cust.id = shipment_master.customer_id');
        $this->db->join('customer_kyc ck', 'ck.customer_id = shipment_master.customer_id', 'left');
        $this->db->where('shipment_master.deleted_at IS NULL');
        if ($this->session->userdata('role_id') == 3) {
            $this->db->where('shipment_master.created_by', $this->session->userdata('user_id'));
        }
        $this->db->where('shipment_master.awb_number', $awb_number);
        return $this->db->get()->row();
    }

    public function get_boxes($shipment_id) {
        return $this->db->get_where('shipment_boxes', array('shipment_id' => $shipment_id))->result();
    }

    public function get_items($shipment_id) {
        return $this->db->get_where('shipment_items', array('shipment_id' => $shipment_id))->result();
    }

    public function get_documents($shipment_id) {
        return $this->db->get_where('shipment_documents', array('shipment_id' => $shipment_id))->result();
    }

    public function get_additional_charges($shipment_id) {
        $this->db->select('shipment_additional_charges.*, additional_charge_types.charge_name');
        $this->db->from('shipment_additional_charges');
        $this->db->join('additional_charge_types', 'additional_charge_types.id = shipment_additional_charges.charge_type_id');
        $this->db->where('shipment_additional_charges.shipment_id', $shipment_id);
        return $this->db->get()->result();
    }

    public function get_tracking_timeline($shipment_id) {
        $this->db->select('shipment_tracking.*, users.username as updater_name, branches.name as branch_name, courier_partners.partner_name');
        $this->db->from('shipment_tracking');
        $this->db->join('users', 'users.id = shipment_tracking.updated_by');
        $this->db->join('branches', 'branches.id = shipment_tracking.branch_id', 'left');
        $this->db->join('courier_partners', 'courier_partners.id = shipment_tracking.courier_partner_id', 'left');
        $this->db->where('shipment_tracking.shipment_id', $shipment_id);
        $this->db->order_by('shipment_tracking.date_time', 'ASC');
        return $this->db->get()->result();
    }

    public function get_pickup_request($shipment_id) {
        return $this->db->get_where('pickup_requests', array('shipment_id' => $shipment_id))->row();
    }

    public function book_shipment($data) {
        $this->db->trans_start();

        // 1. Determine AWB
        if (!empty($data['awb_number'])) {
            $awb = $data['awb_number'];
            $exists = $this->db->get_where('shipment_master', array('awb_number' => $awb))->num_rows();
            if ($exists > 0) {
                $this->db->trans_rollback();
                return 'DUPLICATE_AWB';
            }
        } else {
            $awb = $this->generate_awb_number();
        }

        // 2. Insert into shipment_master
        $master_data = array(
            'awb_number' => $awb,
            'booking_date' => $data['booking_date'],
            'service_type' => $data['service_type'],
            'shipment_type' => $data['shipment_type'],
            'origin_country_id' => $data['origin_country_id'],
            'destination_country_id' => $data['destination_country_id'],
            'courier_partner_id' => $data['courier_partner_id'],
            'customer_id' => $data['customer_id'],
            'total_weight' => $data['total_weight'],
            'total_volumetric_weight' => $data['total_volumetric_weight'],
            'chargeable_weight' => $data['chargeable_weight'],
            'total_declared_value' => $data['total_declared_value'],
            'estimated_charges' => $data['estimated_charges'],
            'status' => 'Booking Created',
            'verification_status' => 'Pending',
            'declaration_status' => 'Pending',
            'terms_status' => 'Pending',
            'signature_status' => 'Pending',
            'otp_verification_status' => 'Pending',
            'ready_for_dispatch_status' => 'Pending',
            'created_by' => $this->session->userdata('user_id'),
            'branch_id' => $this->session->userdata('branch_id'),
            'franchise_id' => $this->session->userdata('franchise_id'),
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('shipment_master', $master_data);
        $shipment_id = $this->db->insert_id();

        // 2. Insert into shipment_sender
        $sender_data = $data['sender'];
        $sender_data['shipment_id'] = $shipment_id;
        $this->db->insert('shipment_sender', $sender_data);

        // 3. Insert into shipment_receiver
        $receiver_data = $data['receiver'];
        $receiver_data['shipment_id'] = $shipment_id;
        $this->db->insert('shipment_receiver', $receiver_data);

        // 4. Insert dynamic boxes
        foreach ($data['boxes'] as $box) {
            $box_data = array(
                'shipment_id' => $shipment_id,
                'box_number' => $box['box_number'],
                'length' => $box['length'],
                'width' => $box['width'],
                'height' => $box['height'],
                'actual_weight' => $box['actual_weight'],
                'volumetric_weight' => $box['volumetric_weight']
            );
            $this->db->insert('shipment_boxes', $box_data);
        }

        // 5. Insert dynamic items
        foreach ($data['items'] as $item) {
            $item_data = array(
                'shipment_id' => $shipment_id,
                'item_description' => $item['item_description'],
                'hs_code' => $item['hs_code'],
                'quantity' => $item['quantity'],
                'unit_value' => $item['unit_value'],
                'total_value' => $item['total_value'],
                'country_of_origin_id' => $item['country_of_origin_id'],
                'box_no' => $item['box_no']
            );
            $this->db->insert('shipment_items', $item_data);
        }

        // 5.5 Insert additional charges
        if (isset($data['additional_charges']) && !empty($data['additional_charges'])) {
            foreach ($data['additional_charges'] as $charge) {
                if (!empty($charge['charge_type_id']) && $charge['charge_amount'] > 0) {
                    $charge_data = array(
                        'shipment_id' => $shipment_id,
                        'charge_type_id' => $charge['charge_type_id'],
                        'charge_amount' => $charge['charge_amount']
                    );
                    $this->db->insert('shipment_additional_charges', $charge_data);
                }
            }
        }

        // 6. Create initial tracking status
        $this->add_tracking_stage($shipment_id, 'Booking Created', 'Shipment booking created in system');
        $this->add_tracking_stage($shipment_id, 'Verification Pending', 'Customer verification checklist is required before transit release');

        // 7. Generate invoice
        $this->Customer_model->generate_invoice($shipment_id, $data['estimated_charges']);

        // 8. If pickup requested, create pickup record
        if (isset($data['pickup']) && $data['pickup']['requested'] == TRUE) {
            $pickup_data = array(
                'shipment_id' => $shipment_id,
                'pickup_date' => $data['pickup']['pickup_date'],
                'pickup_time' => $data['pickup']['pickup_time'],
                'address' => $data['sender']['address'] . ', ' . $data['sender']['city'] . ', ' . $data['sender']['state'] . ', ' . $data['sender']['zip_code'],
                'contact_person' => $data['sender']['name'],
                'mobile' => $data['sender']['mobile'],
                'remarks' => 'Auto-generated pickup from booking creation.',
                'status' => 'Requested',
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('pickup_requests', $pickup_data);
            $this->add_tracking_stage($shipment_id, 'Pickup Requested', 'Courier pickup request dispatched for customer location');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        $this->Audit_model->log_activity('Shipment Booked', 'AWB: ' . $awb);
        return $shipment_id;
    }

    public function add_tracking_stage($shipment_id, $status, $remarks, $date_time = NULL) {
        $user_id = $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 1;
        $branch_id = $this->session->userdata('branch_id') ? $this->session->userdata('branch_id') : 1;
        
        $shipment = $this->db->get_where('shipment_master', array('id' => $shipment_id))->row();

        $tracking_data = array(
            'shipment_id' => $shipment_id,
            'date_time' => $date_time ? $date_time : date('Y-m-d H:i:s'),
            'location' => $branch_id ? $this->db->get_where('branches', array('id' => $branch_id))->row()->name : 'System Gateway',
            'remarks' => $remarks,
            'updated_by' => $user_id,
            'branch_id' => $branch_id,
            'courier_partner_id' => $shipment ? $shipment->courier_partner_id : NULL,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Update main shipment status
        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_master', array('status' => $status));

        $this->Audit_model->log_activity('Tracking Updated', 'Shipment ID: ' . $shipment_id . ' Status: ' . $status);
        
        $inserted = $this->db->insert('shipment_tracking', $tracking_data);
        if ($inserted) {
            $this->load->model('Notification_model');
            $this->Notification_model->send_tracking_update_notifications($shipment_id, $status, $remarks);
        }
        return $inserted;
    }

    public function submit_customer_signature($shipment_id, $customer_id, $image_data) {
        $ip_address = $this->input->ip_address();
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' - ' . $this->agent->browser() . ' ' . $this->agent->version();

        // Save signature file
        $filteredData = explode(',', $image_data);
        if (count($filteredData) < 2) return FALSE;
        
        $unencodedData = base64_decode($filteredData[1]);
        $folder = './assets/signatures/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, TRUE);
        }
        $filename = 'sig_' . $shipment_id . '_' . time() . '.png';
        $filepath = $folder . $filename;
        file_put_contents($filepath, $unencodedData);

        $db_path = 'assets/signatures/' . $filename;

        // Insert log
        $sig_data = array(
            'customer_id' => $customer_id,
            'shipment_id' => $shipment_id,
            'signature_image_path' => $db_path,
            'ip_address' => $ip_address,
            'browser_details' => $browser,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('customer_signatures', $sig_data);

        // Update shipment status
        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_master', array('signature_status' => 'Completed'));

        $this->Audit_model->log_activity('Digital Signature Submitted', 'Shipment ID: ' . $shipment_id);
        return TRUE;
    }

    public function submit_terms_declaration_otp($shipment_id, $customer_id, $terms_id, $otp) {
        $ip_address = $this->input->ip_address();
        $this->load->library('user_agent');
        $browser = $this->agent->platform() . ' - ' . $this->agent->browser() . ' ' . $this->agent->version();
        $datetime = date('Y-m-d H:i:s');

        $this->db->trans_start();

        // 1. Log Terms Acceptance
        $this->db->insert('terms_acceptance_log', array(
            'customer_id' => $customer_id,
            'shipment_id' => $shipment_id,
            'terms_version_id' => $terms_id,
            'acceptance_status' => 'Accepted',
            'acceptance_time' => $datetime,
            'ip_address' => $ip_address,
            'browser_details' => $browser,
            'created_at' => $datetime
        ));

        // 2. Log Declaration Acceptance
        $this->db->insert('declaration_acceptance_log', array(
            'customer_id' => $customer_id,
            'shipment_id' => $shipment_id,
            'declaration_status' => 'Accepted',
            'acceptance_time' => $datetime,
            'ip_address' => $ip_address,
            'browser_details' => $browser,
            'created_at' => $datetime
        ));

        // 3. Log OTP Verification
        $this->db->insert('otp_verification_log', array(
            'customer_id' => $customer_id,
            'shipment_id' => $shipment_id,
            'otp_code' => $otp,
            'otp_channel' => 'Email',
            'verification_status' => 'Verified',
            'sent_at' => date('Y-m-d H:i:s', time() - 60),
            'verified_at' => $datetime,
            'ip_address' => $ip_address,
            'browser_details' => $browser,
            'created_at' => $datetime
        ));

        // 4. Update shipment verification statuses
        $update_master = array(
            'declaration_status' => 'Accepted',
            'terms_status' => 'Accepted',
            'otp_verification_status' => 'Verified',
            'verification_status' => 'Completed',
            'verification_completed_at' => $datetime
        );

        // 5. Check if Customer KYC is approved
        $kyc = $this->Customer_model->get_kyc_details($customer_id);
        if ($kyc && $kyc->status == 'approved') {
            $update_master['ready_for_dispatch_status'] = 'Ready';
            $update_master['status'] = 'Ready For Dispatch';
        }

        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_master', $update_master);

        // Add tracking updates
        $this->add_tracking_stage($shipment_id, 'Declaration Accepted', 'Customer approved export item declarations');
        $this->add_tracking_stage($shipment_id, 'Terms Accepted', 'Customer agreed to T&C version: ' . $terms_id);
        $this->add_tracking_stage($shipment_id, 'OTP Verified', 'Multi-factor authentication (OTP) completed successfully');

        if ($kyc && $kyc->status == 'approved') {
            $this->add_tracking_stage($shipment_id, 'Ready For Dispatch', 'Mandatory KYC & digital signatures completed. Shipment cleared for transit release.');
        } else {
            $this->add_tracking_stage($shipment_id, 'Verification Pending', 'Digital signatures accepted. Awaiting KYC approval before transit release.');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() !== FALSE) {
            $this->load->model('Notification_model');
            $this->Notification_model->send_verified_shipment_details($shipment_id);
        }

        $this->Audit_model->log_activity('Shipment Verification Completed', 'Shipment ID: ' . $shipment_id);
        return $this->db->trans_status();
    }

    public function update_shipment($shipment_id, $data) {
        $this->db->trans_start();

        // 1. Update shipment_master
        $master_data = array(
            'booking_date' => $data['booking_date'],
            'service_type' => $data['service_type'],
            'shipment_type' => $data['shipment_type'],
            'origin_country_id' => $data['origin_country_id'],
            'destination_country_id' => $data['destination_country_id'],
            'courier_partner_id' => $data['courier_partner_id'],
            'customer_id' => $data['customer_id'],
            'total_weight' => $data['total_weight'],
            'total_volumetric_weight' => $data['total_volumetric_weight'],
            'chargeable_weight' => $data['chargeable_weight'],
            'total_declared_value' => $data['total_declared_value'],
            'estimated_charges' => $data['estimated_charges'],
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $shipment_id);
        $this->db->update('shipment_master', $master_data);

        // 2. Update shipment_sender
        $sender_data = $data['sender'];
        $this->db->where('shipment_id', $shipment_id);
        $this->db->update('shipment_sender', $sender_data);

        // 3. Update shipment_receiver
        $receiver_data = $data['receiver'];
        $this->db->where('shipment_id', $shipment_id);
        $this->db->update('shipment_receiver', $receiver_data);

        // 4. Delete and re-insert boxes
        $this->db->where('shipment_id', $shipment_id);
        $this->db->delete('shipment_boxes');
        foreach ($data['boxes'] as $box) {
            $box_data = array(
                'shipment_id' => $shipment_id,
                'box_number' => $box['box_number'],
                'length' => $box['length'],
                'width' => $box['width'],
                'height' => $box['height'],
                'actual_weight' => $box['actual_weight'],
                'volumetric_weight' => $box['volumetric_weight']
            );
            $this->db->insert('shipment_boxes', $box_data);
        }

        // 5. Delete and re-insert items
        $this->db->where('shipment_id', $shipment_id);
        $this->db->delete('shipment_items');
        foreach ($data['items'] as $item) {
            $item_data = array(
                'shipment_id' => $shipment_id,
                'item_description' => $item['item_description'],
                'hs_code' => $item['hs_code'],
                'quantity' => $item['quantity'],
                'unit_value' => $item['unit_value'],
                'total_value' => $item['total_value'],
                'country_of_origin_id' => $item['country_of_origin_id'],
                'box_no' => $item['box_no']
            );
            $this->db->insert('shipment_items', $item_data);
        }

        // 5.5 Delete and re-insert additional charges
        $this->db->where('shipment_id', $shipment_id);
        $this->db->delete('shipment_additional_charges');
        if (isset($data['additional_charges']) && !empty($data['additional_charges'])) {
            foreach ($data['additional_charges'] as $charge) {
                if (!empty($charge['charge_type_id']) && $charge['charge_amount'] > 0) {
                    $charge_data = array(
                        'shipment_id' => $shipment_id,
                        'charge_type_id' => $charge['charge_type_id'],
                        'charge_amount' => $charge['charge_amount']
                    );
                    $this->db->insert('shipment_additional_charges', $charge_data);
                }
            }
        }

        // 6. Update invoice if exists
        $invoice = $this->db->get_where('invoices', array('shipment_id' => $shipment_id))->row();
        if ($invoice) {
            $amount = $data['estimated_charges'];
            $tax_percentage = 18.00;
            $tax_amount = ($amount * $tax_percentage) / 100;
            $final_amount = $amount + $tax_amount;

            $this->db->where('id', $invoice->id);
            $this->db->update('invoices', array(
                'total_amount' => $amount,
                'tax_amount' => $tax_amount,
                'final_amount' => $final_amount,
                'updated_at' => date('Y-m-d H:i:s')
            ));
            
            // Update customer ledger
            $this->db->where('reference_id', $invoice->invoice_number);
            $this->db->where('entry_type', 'Invoice');
            $this->db->update('customer_ledger', array(
                'debit_amount' => $final_amount,
                'running_balance' => $final_amount
            ));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        }

        $this->Audit_model->log_activity('Shipment Updated', 'ID: ' . $shipment_id);
        return TRUE;
    }

    public function delete_shipment($id) {
        $this->db->where('id', $id);
        $result = $this->db->update('shipment_master', array('deleted_at' => date('Y-m-d H:i:s')));
        $this->Audit_model->log_activity('Delete Shipment', 'Shipment ID: ' . $id);
        return $result;
    }
}
