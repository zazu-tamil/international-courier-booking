<?php
define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');
define('ENVIRONMENT', 'development');

require_once APPPATH . 'config/database.php';

$conn = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SHOW COLUMNS FROM customer_wallet");
echo "CUSTOMER_WALLET TABLE:\n";
while($row = $res->fetch_assoc()){
    print_r($row);
}

$conn->close();
