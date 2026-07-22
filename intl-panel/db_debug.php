<?php
define('BASEPATH', dirname(__FILE__) . '/system/');
define('APPPATH', dirname(__FILE__) . '/application/');
define('ENVIRONMENT', 'development');

require_once APPPATH . 'config/database.php';

$conn = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SHOW COLUMNS FROM shipment_master");
echo "SHIPMENT MASTER:\n";
while($row = $res->fetch_array()){
    echo $row[0] . "\n";
}

$res = $conn->query("SHOW COLUMNS FROM shipment_tracking");
echo "SHIPMENT TRACKING:\n";
while($row = $res->fetch_array()){
    echo $row[0] . "\n";
}

$conn->close();
