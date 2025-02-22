<?php
include "../includes/functions.php";
include "../includes/db.php";

global $db_conn;

header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

file_put_contents("debug_log.txt", "Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON input."]);
    exit;
}

if (!isset($data['booking_ids']) || !isset($data['status'])) {
    echo json_encode(["success" => false, "message" => "Missing parameters."]);
    exit;
}

$booking_ids = array_map('intval', $data['booking_ids']);
$status = mysqli_real_escape_string($db_conn, $data['status']);

$booking_ids_str = implode(',', $booking_ids);

file_put_contents("debug_log.txt", "SQL Query: UPDATE `test_requests` SET `status` = '$status' WHERE `id` IN ($booking_ids_str)\n", FILE_APPEND);

// Execute the update query
$updateQuery = "UPDATE `test_requests` SET `status` = '$status' WHERE `id` IN ($booking_ids_str)";
$result = mysqli_query($db_conn, $updateQuery);

if ($result) {
    echo json_encode(["success" => true, "message" => "Booking status updated successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Database update failed: " . mysqli_error($db_conn)]);
}
exit;
