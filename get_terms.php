<?php
header('Content-Type: application/json');

$host = "localhost";
// $user = "root"; // Using default root for XAMPP
// $pwd = "";
$user = "u273562490_intl_usr_786";
$pwd = "G~NdX8;9i";
$db = "u273562490_intl_db";
// $db = "intl_courier_db";

$conn = new mysqli($host, $user, $pwd, $db);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch the most recent active terms and conditions
$sql = "SELECT terms_content as terms_text, version_number as version_name FROM terms_conditions_master WHERE status = 'Published' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No active terms found.']);
}

$conn->close();
?>
