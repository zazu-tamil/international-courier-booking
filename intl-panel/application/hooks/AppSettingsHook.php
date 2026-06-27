<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppSettingsHook {
    public function load_settings() {
        $CI =& get_instance();
        
        // 1. Fetch settings from database and define them
        if (isset($CI->db)) {
            if ($CI->db->table_exists('app_settings')) {
                $query = $CI->db->get('app_settings');
                if ($query) {
                    foreach ($query->result() as $row) {
                        $constant_name = strtoupper($row->key);
                        if (!defined($constant_name)) {
                            define($constant_name, $row->value);
                        }
                    }
                }
            }
        }

        // 2. Define fallbacks for missing keys so references don't crash
        $defaults = array(
            'company_name' => 'Courier Syndicate',
            'company_address' => '',
            'company_email' => 'no-reply@couriersyndicate.com',
            'company_gst' => '',
            'company_mobile' => '',
            'company_logo' => '',
            'smtp_enabled' => '0',
            'smtp_host' => '',
            'smtp_port' => '25',
            'smtp_user' => '',
            'smtp_pass' => '',
            'smtp_crypto' => '',
            'sms_enabled' => '0',
            'sms_api_url' => '',
            'sms_api_key' => '',
            'sms_sender_id' => '',
            'whatsapp_enabled' => '0',
            'whatsapp_api_url' => '',
            'whatsapp_api_key' => ''
        );

        foreach ($defaults as $key => $default_value) {
            $constant_name = strtoupper($key);
            if (!defined($constant_name)) {
                define($constant_name, $default_value);
            }
        }
    }
}
