<?php
// Bootstrap CI3
$_SERVER['REQUEST_METHOD'] = 'GET';
define('ENVIRONMENT', 'development');
define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');
define('FCPATH', dirname(__FILE__) . '/');
define('VIEWPATH', APPPATH . 'views/');

require_once BASEPATH . 'core/CodeIgniter.php';

// Now we can use the CI instance
$CI =& get_instance();
$CI->load->model('Auth_model');

$data = array(
    'email' => 'test_'.time().'@example.com',
    'password' => 'password123',
    'customer_type' => 'business',
    'name' => 'Test User',
    'company_name' => 'Test Company',
    'mobile' => '1234567890',
    'address' => '123 Test St',
    'city' => 'Test City',
    'state' => 'Test State',
    'country_id' => 1,
    'zip_code' => '12345'
);

$result = $CI->Auth_model->register_customer($data);

if (!$result) {
    echo "FAILED.\n";
    print_r($CI->db->error());
} else {
    echo "SUCCESS: " . $result . "\n";
}
