<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Audit_model');
    }

    public function get_customers($id = NULL) {
        $this->db->select('customers.*, users.status as user_status, countries.country_name, customer_wallet.balance as wallet_balance, customer_kyc.status as kyc_status');
        $this->db->from('customers');
        $this->db->join('users', 'users.id = customers.user_id');
        $this->db->join('countries', 'countries.id = customers.country_id');
        $this->db->join('customer_wallet', 'customer_wallet.customer_id = customers.id', 'left');
        $this->db->join('customer_kyc', 'customer_kyc.customer_id = customers.id', 'left');
        $this->db->where('customers.deleted_at IS NULL');

        if ($id) {
            $this->db->where('customers.id', $id);
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_franchise_customers($franchise_user_id) {
        $this->db->select('customers.*');
        $this->db->from('customers');
        $this->db->join('shipment_master', 'shipment_master.customer_id = customers.id');
        $this->db->where('shipment_master.created_by', $franchise_user_id);
        $this->db->where('customers.deleted_at IS NULL');
        $this->db->group_by('customers.id');
        return $this->db->get()->result();
    }

    public function get_customer_by_user_id($user_id) {
        $this->db->select('customers.*, customer_wallet.balance as wallet_balance, customer_kyc.status as kyc_status');
        $this->db->from('customers');
        $this->db->join('customer_wallet', 'customer_wallet.customer_id = customers.id', 'left');
        $this->db->join('customer_kyc', 'customer_kyc.customer_id = customers.id', 'left');
        $this->db->where('customers.user_id', $user_id);
        return $this->db->get()->row();
    }

    public function update_customer($id, $data) {
        $this->db->where('id', $id);
        $result = $this->db->update('customers', $data);
        $this->Audit_model->log_activity('Update Customer', 'Customer ID: ' . $id);
        return $result;
    }

    // --- KYC MODULE ---
    public function get_kyc_details($customer_id) {
        return $this->db->get_where('customer_kyc', array('customer_id' => $customer_id))->row();
    }

    public function get_all_kyc() {
        $this->db->select('customer_kyc.*, customer_kyc.id as id, customers.id as customer_id, customers.name as customer_name, customers.company_name, customers.customer_type');
        $this->db->from('customers');
        $this->db->join('customer_kyc', 'customer_kyc.customer_id = customers.id', 'left');
        $this->db->where('customers.deleted_at IS NULL');
        $this->db->order_by('customers.id', 'DESC');
        return $this->db->get()->result();
    }

    public function submit_kyc($customer_id, $data) {
        $exists = $this->get_kyc_details($customer_id);
        if ($exists) {
            $this->db->where('customer_id', $customer_id);
            $result = $this->db->update('customer_kyc', $data);
        } else {
            $data['customer_id'] = $customer_id;
            $result = $this->db->insert('customer_kyc', $data);
        }
        $this->Audit_model->log_activity('Submit KYC', 'Customer ID: ' . $customer_id);
        return $result;
    }

    public function approve_reject_kyc($kyc_id, $status, $reason = NULL) {
        $user_id = $this->session->userdata('user_id');
        $update = array(
            'status' => $status,
            'reject_reason' => $reason,
            'approved_by' => $user_id,
            'approved_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $kyc_id);
        $result = $this->db->update('customer_kyc', $update);
        
        $kyc = $this->db->get_where('customer_kyc', array('id' => $kyc_id))->row();
        
        if ($status == 'approved' && $kyc) {
            // Retrieve shipments of this customer that have verification completed but ready_for_dispatch is pending
            $this->db->where('customer_id', $kyc->customer_id);
            $this->db->where('verification_status', 'Completed');
            $this->db->where('ready_for_dispatch_status', 'Pending');
            $this->db->where('deleted_at IS NULL');
            $shipments = $this->db->get('shipment_master')->result();

            foreach ($shipments as $shipment) {
                // Update shipment to Ready For Dispatch
                $this->db->where('id', $shipment->id);
                $this->db->update('shipment_master', array(
                    'ready_for_dispatch_status' => 'Ready',
                    'status' => 'Ready For Dispatch'
                ));

                // Log tracking stage
                $tracking_data = array(
                    'shipment_id' => $shipment->id,
                    'date_time' => date('Y-m-d H:i:s'),
                    'location' => 'Headquarters Origin Branch',
                    'remarks' => 'KYC approved by Admin. Shipment cleared for transit release.',
                    'updated_by' => $user_id ? $user_id : 1,
                    'branch_id' => 1,
                    'courier_partner_id' => $shipment->courier_partner_id,
                    'status' => 'Ready For Dispatch',
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('shipment_tracking', $tracking_data);
            }
        }

        $this->Audit_model->log_activity('KYC Status Changed', 'KYC ID: ' . $kyc_id . ' Status: ' . $status . ' Customer ID: ' . $kyc->customer_id);
        return $result;
    }

    // --- WALLET & CREDIT SYSTEM ---
    public function get_wallet_balance($customer_id) {
        $wallet = $this->db->get_where('customer_wallet', array('customer_id' => $customer_id))->row();
        return $wallet ? $wallet->balance : 0.00;
    }

    public function add_wallet_funds($customer_id, $amount, $description = 'Added funds via portal', $ref_id = NULL) {
        $this->db->trans_start();

        // Check if wallet exists
        $wallet = $this->db->get_where('customer_wallet', array('customer_id' => $customer_id))->row();
        if (!$wallet) {
            $this->db->insert('customer_wallet', array('customer_id' => $customer_id, 'balance' => 0.00));
        }

        // Increment wallet balance
        $this->db->where('customer_id', $customer_id);
        $this->db->set('balance', 'balance + ' . (float)$amount, FALSE);
        $this->db->update('customer_wallet');

        // Insert wallet transaction
        $trans_data = array(
            'customer_id' => $customer_id,
            'transaction_type' => 'Credit',
            'amount' => $amount,
            'description' => $description,
            'reference_id' => $ref_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('customer_wallet_transactions', $trans_data);

        // Update Ledger statement
        $this->add_ledger_entry($customer_id, 'Payment', $amount, 0, $amount, $ref_id, 'Wallet Credit: ' . $description);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    // --- PREPAID WALLET LOAD REQUESTS ---
    public function add_wallet_request($data) {
        return $this->db->insert('wallet_load_requests', $data);
    }
    
    public function get_wallet_requests($status = NULL, $customer_id = NULL) {
        $this->db->select('wallet_load_requests.*, customers.name as customer_name, customers.company_name');
        $this->db->from('wallet_load_requests');
        $this->db->join('customers', 'customers.id = wallet_load_requests.customer_id');
        if ($status) {
            $this->db->where('wallet_load_requests.status', $status);
        }
        if ($customer_id) {
            $this->db->where('wallet_load_requests.customer_id', $customer_id);
        }
        $this->db->order_by('wallet_load_requests.id', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_wallet_request($id) {
        $this->db->select('wallet_load_requests.*, customers.name as customer_name, customers.company_name');
        $this->db->from('wallet_load_requests');
        $this->db->join('customers', 'customers.id = wallet_load_requests.customer_id');
        $this->db->where('wallet_load_requests.id', $id);
        return $this->db->get()->row();
    }
    
    public function update_wallet_request($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('wallet_load_requests', $data);
    }

    public function charge_wallet($customer_id, $amount, $description, $ref_id = NULL) {
        $this->db->trans_start();

        // Decrement wallet balance
        $this->db->where('customer_id', $customer_id);
        $this->db->set('balance', 'balance - ' . (float)$amount, FALSE);
        $this->db->update('customer_wallet');

        // Insert wallet transaction
        $trans_data = array(
            'customer_id' => $customer_id,
            'transaction_type' => 'Debit',
            'amount' => $amount,
            'description' => $description,
            'reference_id' => $ref_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('customer_wallet_transactions', $trans_data);

        // Update ledger entry (as Payment using wallet)
        $this->add_ledger_entry($customer_id, 'Payment', $amount, 0, $amount, $ref_id, 'Charged Wallet: ' . $description);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function add_ledger_entry($customer_id, $type, $amount, $debit = 0, $credit = 0, $ref_id = NULL, $desc = '') {
        // Calculate new outstanding balance
        // Debit increases outstanding balance; Credit decreases outstanding balance
        $customer = $this->db->get_where('customers', array('id' => $customer_id))->row();
        $current_outstanding = $customer ? $customer->outstanding_balance : 0.00;

        $debit_val = $debit ? $amount : 0.00;
        $credit_val = $credit ? $amount : 0.00;

        $new_outstanding = $current_outstanding + $debit_val - $credit_val;

        // Update customer outstanding
        $this->db->where('id', $customer_id);
        $this->db->update('customers', array('outstanding_balance' => $new_outstanding));

        // Insert ledger row
        $ledger_data = array(
            'customer_id' => $customer_id,
            'entry_date' => date('Y-m-d'),
            'entry_type' => $type,
            'reference_id' => $ref_id,
            'debit_amount' => $debit_val,
            'credit_amount' => $credit_val,
            'running_balance' => $new_outstanding,
            'created_at' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('customer_ledger', $ledger_data);
    }

    public function get_ledger_statement($customer_id) {
        $this->db->select('*');
        $this->db->from('customer_ledger');
        $this->db->where('customer_id', $customer_id);
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    // --- INVOICING & PAYMENTS ---
    public function generate_invoice($shipment_id, $amount) {
        $this->db->trans_start();

        // Get shipment details
        $this->db->select('shipment_master.*, customers.id as customer_id');
        $this->db->from('shipment_master');
        $this->db->join('customers', 'customers.id = shipment_master.customer_id');
        $this->db->where('shipment_master.id', $shipment_id);
        $shipment = $this->db->get()->row();

        if (!$shipment) {
            $this->db->trans_complete();
            return FALSE;
        }

        // Generate Invoice Number
        $invoice_no = 'INV-' . strtoupper(bin2hex(random_bytes(4)));

        $tax_percentage = 18.00; // Standard shipping tax
        $tax_amount = ($amount * $tax_percentage) / 100;
        $final_amount = $amount + $tax_amount;

        $invoice_data = array(
            'shipment_id' => $shipment_id,
            'invoice_number' => $invoice_no,
            'invoice_date' => date('Y-m-d'),
            'total_amount' => $amount,
            'tax_amount' => $tax_amount,
            'discount_amount' => 0.00,
            'final_amount' => $final_amount,
            'status' => 'Unpaid',
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('invoices', $invoice_data);
        $invoice_id = $this->db->insert_id();

        // Add to customer ledger as Debit
        $this->add_ledger_entry($shipment->customer_id, 'Invoice', $final_amount, 1, 0, $invoice_no, 'Shipment AWB Invoice: ' . $shipment->awb_number);

        $this->db->trans_complete();
        return $invoice_id;
    }

    public function process_payment($invoice_id, $mode, $amount, $txn_id = NULL, $remarks = NULL) {
        $this->db->trans_start();

        $invoice = $this->db->get_where('invoices', array('id' => $invoice_id))->row();
        if (!$invoice) return FALSE;

        // Update Invoice status
        $this->db->where('id', $invoice_id);
        $this->db->update('invoices', array('status' => 'Paid'));

        // Generate payment number
        $payment_no = 'PAY-' . strtoupper(bin2hex(random_bytes(4)));

        $payment_data = array(
            'invoice_id' => $invoice_id,
            'payment_number' => $payment_no,
            'payment_date' => date('Y-m-d'),
            'payment_mode' => $mode,
            'amount' => $amount,
            'transaction_id' => $txn_id,
            'remarks' => $remarks,
            'created_by' => $this->session->userdata('user_id') ? $this->session->userdata('user_id') : 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('payments', $payment_data);

        // Get Customer ID
        $this->db->select('customer_id');
        $this->db->from('shipment_master');
        $this->db->where('id', $invoice->shipment_id);
        $customer_id = $this->db->get()->row()->customer_id;

        // Update ledger as Credit (Payment Received)
        $this->add_ledger_entry($customer_id, 'Payment', $amount, 0, 1, $payment_no, 'Invoice Payment: ' . $invoice->invoice_number);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_customer_full($id, $customer_data) {
        $customer = $this->get_customers($id);
        if (!$customer) return FALSE;

        $this->db->trans_start();
        
        // Update customer
        $this->db->where('id', $id);
        $this->db->update('customers', $customer_data);

        // Update associated user if email changes
        if ($customer->user_id && isset($customer_data['email'])) {
            $this->db->where('id', $customer->user_id);
            $this->db->update('users', array(
                'email' => $customer_data['email'],
                'username' => $customer_data['email'],
                'status' => $customer_data['status']
            ));
        }

        $this->db->trans_complete();
        $this->Audit_model->log_activity('Update Customer', 'Customer ID: ' . $id);
        return $this->db->trans_status();
    }

    public function delete_customer($id) {
        $customer = $this->get_customers($id);
        if ($customer) {
            $this->db->trans_start();
            // Soft delete customer
            $this->db->where('id', $id);
            $this->db->update('customers', array('deleted_at' => date('Y-m-d H:i:s'), 'status' => 'Inactive'));
            
            // Soft delete associated user
            if ($customer->user_id) {
                $this->db->where('id', $customer->user_id);
                $this->db->update('users', array('deleted_at' => date('Y-m-d H:i:s'), 'status' => 'Inactive'));
            }
            $this->db->trans_complete();
            $this->Audit_model->log_activity('Delete Customer', 'Customer ID: ' . $id);
            return $this->db->trans_status();
        }
        return FALSE;
    }

    public function delete_kyc($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete('customer_kyc');
        $this->Audit_model->log_activity('Delete KYC Record', 'KYC ID: ' . $id);
        return $result;
    }
}
