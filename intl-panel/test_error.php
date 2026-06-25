<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', __DIR__ . '/system/');
define('APPPATH', __DIR__ . '/application/');
require_once BASEPATH . 'core/CodeIgniter.php';

$ci =& get_instance();
$ci->load->database();

$user_data = array(
    'username' => 'test_branch_100',
    'email' => 'test100@test.com',
    'password' => password_hash('password123', PASSWORD_BCRYPT),
    'role_id' => 2,
    'branch_id' => 1,
    'status' => 'Active',
    'created_at' => date('Y-m-d H:i:s')
);

if (!$ci->db->insert('users', $user_data)) {
    echo "DB Error: " . print_r($ci->db->error(), true);
} else {
    echo "Inserted successfully. ID: " . $ci->db->insert_id();
}
