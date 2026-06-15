<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Master_model');
        $this->load->model('Shipment_model');
    }

    /**
     * Send general automated notifications (Email, SMS, WhatsApp)
     */
    private function _send_notification($user_id, $contact, $subject, $message, $type, $attachment_path = NULL) {
        // If SMTP/SMS/WhatsApp is disabled, we do NOT log/send.
        // Let's fetch settings
        $settings = $this->Master_model->get_app_settings();
        
        $enabled = FALSE;
        if ($type == 'Email' && isset($settings['smtp_enabled']) && $settings['smtp_enabled'] == '1') {
            $enabled = TRUE;
        } elseif ($type == 'SMS' && isset($settings['sms_enabled']) && $settings['sms_enabled'] == '1') {
            $enabled = TRUE;
        } elseif ($type == 'WhatsApp' && isset($settings['whatsapp_enabled']) && $settings['whatsapp_enabled'] == '1') {
            $enabled = TRUE;
        }

        if (!$enabled) {
            return FALSE;
        }

        $status = 'Sent';
        $error_log = '';

        if ($type == 'Email') {
            // Actual email dispatch via CodeIgniter email library
            $this->load->library('email');
            
            // Setup dynamic configuration
            $config = array();
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = isset($settings['smtp_host']) ? trim($settings['smtp_host']) : '';
            $config['smtp_port'] = isset($settings['smtp_port']) ? intval(trim($settings['smtp_port'])) : 25;
            $config['smtp_user'] = isset($settings['smtp_user']) ? trim($settings['smtp_user']) : '';
            $config['smtp_pass'] = isset($settings['smtp_pass']) ? trim($settings['smtp_pass']) : '';
            
            // Set encryption
            $crypto = isset($settings['smtp_crypto']) ? trim($settings['smtp_crypto']) : '';
            if ($crypto == 'ssl' || $crypto == 'tls') {
                $config['smtp_crypto'] = $crypto;
            } else {
                $config['smtp_crypto'] = '';
            }
            
            $config['mailtype'] = 'text'; // plain text
            $config['charset'] = 'utf-8';
            $config['wordwrap'] = TRUE;
            $config['newline'] = "\r\n";
            $config['crlf'] = "\r\n";
            
            // Re-initialize email library to ensure configuration is active
            $this->email->clear(TRUE);
            $this->email->initialize($config);
            
            $reply_email = !empty($settings['company_email']) ? trim($settings['company_email']) : 'no-reply@couriersyndicate.com';
            $reply_name = !empty($settings['company_name']) ? trim($settings['company_name']) : 'CourierSyndicate';
            
            $from_email =   $config['smtp_user'];
            $from_name = 'No Reply | Courier Syndicate';

            $this->email->from($from_email, $from_name);
            $this->email->to($contact);
            $this->email->reply_to($reply_email, $reply_name);
            $this->email->subject($subject);
            
            // Compose the final message content
            $final_message = $message;
            if ($attachment_path) {
                $final_message .= "\n\n[Attached Document: " . basename($attachment_path) . "]";
            }
            $this->email->message($final_message);
            
            if ($attachment_path && file_exists('./' . $attachment_path)) {
                $this->email->attach('./' . $attachment_path);
            }
            
            if (!$this->email->send()) {
                $status = 'Failed';
                $error_log = $this->email->print_debugger(array('headers', 'subject', 'body'));
            }
        } elseif ($type == 'SMS') {
            $api_url = isset($settings['sms_api_url']) ? trim($settings['sms_api_url']) : '';
            $api_key = isset($settings['sms_api_key']) ? trim($settings['sms_api_key']) : '';
            $sender_id = isset($settings['sms_sender_id']) ? trim($settings['sms_sender_id']) : '';
            
            if (!empty($api_url)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                
                $post_fields = array(
                    'api_key' => $api_key,
                    'to' => $contact,
                    'message' => $message,
                    'sender_id' => $sender_id
                );
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_err = curl_error($ch);
                curl_close($ch);
                
                if ($curl_err || ($http_code != 200 && $http_code != 201)) {
                    $status = 'Failed';
                    $error_log = "cURL Error: " . $curl_err . " | HTTP Code: " . $http_code . " | Response: " . $response;
                }
            } else {
                // If API URL is empty, we perform mock send (treated as Sent)
                $status = 'Sent';
            }
        } elseif ($type == 'WhatsApp') {
            $api_url = isset($settings['whatsapp_api_url']) ? trim($settings['whatsapp_api_url']) : '';
            $api_key = isset($settings['whatsapp_api_key']) ? trim($settings['whatsapp_api_key']) : '';
            
            if (!empty($api_url)) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                
                $post_fields = array(
                    'api_key' => $api_key,
                    'to' => $contact,
                    'message' => $message
                );
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_err = curl_error($ch);
                curl_close($ch);
                
                if ($curl_err || ($http_code != 200 && $http_code != 201)) {
                    $status = 'Failed';
                    $error_log = "cURL Error: " . $curl_err . " | HTTP Code: " . $http_code . " | Response: " . $response;
                }
            } else {
                // If API URL is empty, we perform mock send (treated as Sent)
                $status = 'Sent';
            }
        }

        // If there's an error, we append it to the message in the logs for transparency
        $logged_message = $message;
        if ($attachment_path) {
            $logged_message .= "\n\n[Attached Document: " . basename($attachment_path) . "]";
        }
        if (!empty($error_log)) {
            $logged_message .= "\n\n--- Debug/Error Logs ---\n" . $error_log;
        }

        $notify_data = array(
            'user_id' => $user_id,
            'recipient_contact' => $contact,
            'type' => $type,
            'subject' => $subject,
            'message' => $logged_message,
            'status' => $status,
            'sent_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('notifications', $notify_data);
        return $this->db->insert_id();
    }

    /**
     * Helper to dispatch across all active channels
     */
    private function _dispatch_all($user_id, $email, $mobile, $subject, $message, $attachment_path = NULL) {
        $this->_send_notification($user_id, $email, $subject, $message, 'Email', $attachment_path);
        $this->_send_notification($user_id, $mobile, $subject, $message, 'SMS');
        $this->_send_notification($user_id, $mobile, $subject, $message, 'WhatsApp');
    }

    /**
     * A. Send Shipment Created Notifications (With Login & Consignment PDF)
     */
    public function send_shipment_created_notifications($shipment_id, $password = NULL) {
        // Fetch shipment details
        $shipment = $this->Shipment_model->get_shipments($shipment_id);
        if (!$shipment) return FALSE;

        // Fetch customer profile
        $customer = $this->db->get_where('customers', array('id' => $shipment->customer_id))->row();
        if (!$customer) return FALSE;

        // 1. Generate Shipment Details PDF
        $pdf_path = $this->generate_shipment_details_pdf($shipment_id);

        // 2. Register PDF in shipment_documents
        if ($pdf_path) {
            $doc_data = array(
                'shipment_id' => $shipment_id,
                'doc_type' => 'Consignment Details',
                'file_path' => $pdf_path,
                'uploaded_by' => 1, // System gateway
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('shipment_documents', $doc_data);
        }

        // 3. Compose message
        $subject = 'Shipment Booking Confirmation - AWB: ' . $shipment->awb_number;
        
        $msg = "Dear " . $customer->name . ",\n\n";
        $msg .= "Your international shipment booking has been created successfully.\n";
        $msg .= "AWB Number: " . $shipment->awb_number . "\n";
        $msg .= "Booking Date: " . $shipment->booking_date . "\n";
        $msg .= "Service: " . $shipment->service_type . " via " . $shipment->courier_partner_name . "\n";
        $msg .= "Destination: " . $shipment->dest_country_name . "\n\n";

        if ($password) {
            $msg .= "Customer Portal Login Credentials:\n";
            $msg .= "Login URL: " . site_url('login') . "\n";
            $msg .= "Username / Email: " . $customer->email . "\n";
            $msg .= "Password: " . $password . "\n\n";
            $msg .= "Please use these credentials to log in, review, and complete the digital verification process.\n\n";
        } else {
            $msg .= "Please log in to your customer portal profile at " . site_url('login') . " to sign and authorize this shipment.\n\n";
        }

        $msg .= "Thank you,\nCourierSyndicate International Support";

        // Dispatch notifications
        $this->_dispatch_all($customer->user_id, $customer->email, $customer->mobile, $subject, $msg, $pdf_path);
        return TRUE;
    }

    /**
     * B. Send OTP Verification Notifications
     */
    public function send_otp_notifications($shipment_id, $otp) {
        $shipment = $this->Shipment_model->get_shipments($shipment_id);
        if (!$shipment) return FALSE;

        $customer = $this->db->get_where('customers', array('id' => $shipment->customer_id))->row();
        if (!$customer) return FALSE;

        $subject = 'Verification OTP code - ' . $shipment->awb_number;
        $msg = "Dear " . $shipment->sender_name . ",\n\n";
        $msg .= "Your OTP code for verifying shipment " . $shipment->awb_number . " is " . $otp . ".\n";
        $msg .= "Please enter this code in the customer portal release wizard to authorize the transit release process.\n\n";
        $msg .= "Thank you,\nCourierSyndicate International";

        $this->_dispatch_all($customer->user_id, $customer->email, $customer->mobile, $subject, $msg);
        return TRUE;
    }

    /**
     * C. Send Final Verified Shipment Release Notifications (With PDF)
     */
    public function send_verified_shipment_details($shipment_id) {
        $shipment = $this->Shipment_model->get_shipments($shipment_id);
        if (!$shipment) return FALSE;

        $customer = $this->db->get_where('customers', array('id' => $shipment->customer_id))->row();
        if (!$customer) return FALSE;

        // 1. Generate final release PDF
        $pdf_path = $this->generate_shipment_details_pdf($shipment_id, TRUE);

        // 2. Register PDF in shipment_documents
        if ($pdf_path) {
            $doc_data = array(
                'shipment_id' => $shipment_id,
                'doc_type' => 'Verified Transit release details',
                'file_path' => $pdf_path,
                'uploaded_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('shipment_documents', $doc_data);
        }

        $subject = 'Shipment Verified & Released - AWB: ' . $shipment->awb_number;
        $msg = "Dear " . $customer->name . ",\n\n";
        $msg .= "Thank you. Your shipment " . $shipment->awb_number . " has been successfully signed, OTP verified, and cleared for transit release.\n";
        $msg .= "Verification Date: " . date('Y-m-d H:i:s') . "\n";
        $msg .= "The copy of your signed terms, conditions, declarations, and consignment receipt is attached.\n\n";
        $msg .= "Thank you,\nCourierSyndicate International Support";

        $this->_dispatch_all($customer->user_id, $customer->email, $customer->mobile, $subject, $msg, $pdf_path);
        return TRUE;
    }

    /**
     * D. Send Shipment Tracking Status Updates
     */
    public function send_tracking_update_notifications($shipment_id, $status, $remarks) {
        $shipment = $this->Shipment_model->get_shipments($shipment_id);
        if (!$shipment) return FALSE;

        $customer = $this->db->get_where('customers', array('id' => $shipment->customer_id))->row();
        if (!$customer) return FALSE;

        $subject = 'Shipment Tracking Status Update - AWB: ' . $shipment->awb_number;
        $msg = "Dear " . $customer->name . ",\n\n";
        $msg .= "Your shipment tracking status has been updated.\n";
        $msg .= "AWB Number: " . $shipment->awb_number . "\n";
        $msg .= "New Status: " . $status . "\n";
        $msg .= "Remarks: " . $remarks . "\n";
        $msg .= "Update Time: " . date('Y-m-d H:i:s') . "\n\n";
        $msg .= "You can view detailed tracking history at: " . site_url('tracking?awb=' . $shipment->awb_number) . "\n\n";
        $msg .= "Thank you,\nCourierSyndicate International";

        $this->_dispatch_all($customer->user_id, $customer->email, $customer->mobile, $subject, $msg);
        return TRUE;
    }

    /**
     * Core PDF Builder Utility
     */
    public function generate_shipment_details_pdf($shipment_id, $is_verified = FALSE) {
        $shipment = $this->Shipment_model->get_shipments($shipment_id);
        if (!$shipment) return FALSE;

        $boxes = $this->Shipment_model->get_boxes($shipment_id);
        $items = $this->Shipment_model->get_items($shipment_id);
        $terms = $this->Master_model->get_active_terms();

        // Construct PDF structure data
        $title = $is_verified ? "VERIFIED TRANSIT RELEASE RECEIPT" : "CONSIGNMENT SUMMARY REPORT";
        
        $sections = array(
            'consignment_info' => array(
                "AWB Number: " . $shipment->awb_number,
                "Booking Date: " . $shipment->booking_date,
                "Service Type: " . $shipment->service_type,
                "Courier Partner: " . $shipment->courier_partner_name,
                "Chargeable Weight: " . $shipment->chargeable_weight . " kg",
                "Total Value: INR " . number_format($shipment->total_declared_value, 2),
                "Estimated Shipping Charges: INR " . number_format($shipment->estimated_charges, 2)
            ),
            'sender_info' => array(
                "Name: " . $shipment->sender_name,
                "Company: " . $shipment->sender_company,
                "Mobile: " . $shipment->sender_mobile,
                "Email: " . $shipment->sender_email,
                "Address: " . $shipment->sender_address . ", " . $shipment->sender_city . ", " . $shipment->sender_state . " - " . $shipment->sender_zip
            ),
            'receiver_info' => array(
                "Name: " . $shipment->receiver_name,
                "Company: " . $shipment->receiver_company,
                "Mobile: " . $shipment->receiver_mobile,
                "Email: " . $shipment->receiver_email,
                "Address: " . $shipment->receiver_address . ", " . $shipment->receiver_city . ", " . $shipment->receiver_state . " - " . $shipment->receiver_zip
            ),
            'items_declaration' => array(),
            'terms_conditions' => array()
        );

        // Add items
        foreach ($items as $index => $item) {
            $sections['items_declaration'][] = ($index + 1) . ". Description: " . $item->item_description . " | HS: " . $item->hs_code . " | Qty: " . $item->quantity . " | Unit Value: INR " . number_format($item->unit_value, 2);
        }

        // Add verification details
        if ($is_verified) {
            $sections['verification_details'] = array(
                "Declaration Acceptance: Accepted",
                "Terms and Conditions: Accepted",
                "MFA OTP Status: Verified",
                "Verification Timestamp: " . ($shipment->verification_completed_at ? $shipment->verification_completed_at : date('Y-m-d H:i:s'))
            );
        }

        // Add terms
        if ($terms) {
            $sections['terms_conditions'][] = "Title: " . $terms->title;
            $sections['terms_conditions'][] = "Version: " . $terms->version_number;
            $sections['terms_conditions'][] = "Effective Date: " . $terms->effective_date;
            
            // Strip HTML elements for plaintext representation inside the PDF
            $cleaned = strip_tags($terms->terms_content);
            $cleaned = str_replace(array('</h4>', '</p>', '<br>'), "\n", $cleaned);
            $cleaned_lines = explode("\n", $cleaned);
            foreach ($cleaned_lines as $line) {
                if (trim($line)) {
                    $sections['terms_conditions'][] = trim($line);
                }
            }
        }

        // Build PDF text-stream content
        $pdf_content = SimplePDF::generate($title, $sections);

        // Ensure target documents folder exists
        $dir = './assets/shipment_documents/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, TRUE);
        }

        $filename = ($is_verified ? 'release_receipt_' : 'details_') . $shipment->awb_number . '.pdf';
        $full_path = 'assets/shipment_documents/' . $filename;
        
        if (file_put_contents('./' . $full_path, $pdf_content)) {
            return $full_path;
        }

        return FALSE;
    }
}

/**
 * Self-contained simple PDF generator class
 */
class SimplePDF {
    public static function generate($title, $sections) {
        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";
        $pdf .= "2 0 obj\n<</Type /Pages /Kids [3 0 R] /Count 1>>\nendobj\n";
        $pdf .= "3 0 obj\n<</Type /Page /Parent 2 0 R /Resources <</Font <</F1 4 0 R>>>> /MediaBox [0 0 595 842] /Contents 5 0 R>>\nendobj\n";
        $pdf .= "4 0 obj\n<</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>\nendobj\n";
        
        // Construct page stream content
        $stream = "BT\n/F1 10 Tf\n50 800 Td\n14 TL\n";
        $stream .= "/F1 14 Tf\n(" . self::escape($title) . ") Tj T*\n/F1 10 Tf\n() Tj T*\n";
        
        foreach ($sections as $section_title => $lines) {
            $stream .= "/F1 11 Tf\n(" . self::escape("=== " . strtoupper(str_replace('_', ' ', $section_title)) . " ===") . ") Tj T*\n/F1 10 Tf\n";
            foreach ($lines as $line) {
                // simple wrapping: limit line length to 80 chars
                $chunks = str_split($line, 80);
                foreach ($chunks as $chunk) {
                    $stream .= "(" . self::escape($chunk) . ") Tj T*\n";
                }
            }
            $stream .= "() Tj T*\n";
        }
        $stream .= "ET";
        
        $len = strlen($stream);
        $pdf .= "5 0 obj\n<</Length $len>>\nstream\n" . $stream . "\nendstream\nendobj\n";
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        $pdf .= "trailer\n<</Size 6 /Root 1 0 R>>\n";
        $pdf .= "startxref\n120\n%%EOF";
        
        return $pdf;
    }
    
    private static function escape($str) {
        $str = html_entity_decode(strip_tags($str), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return str_replace(array('(', ')', '\\'), array('\\(', '\\)', '\\\\'), $str);
    }
}
