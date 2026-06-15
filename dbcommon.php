<?php  
$host = "localhost";
$user = "u273562490_crit_user_qaz";
$pwd = "qazwsx@CS2023";
$sys_dbname = "u273562490_tracking_db";  

$host = "localhost";
$user = "root";
$pwd = "";
$sys_dbname = "couriersyndicate_db";  
 

$link = mysqli_connect($host, $user, $pwd, $sys_dbname);

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
 
?>