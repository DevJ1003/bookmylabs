<?php
include "includes/db.php";

// Set headers for .xls file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=recent_bookings.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Get filter values from URL parameters
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$dispatch_option = isset($_GET['dispatch_option']) ? trim($_GET['dispatch_option']) : '';
$lab_name = isset($_GET['lab_name']) ? trim($_GET['lab_name']) : '';
$selected_date = isset($_GET['date']) ? trim($_GET['date']) : '';

// Start HTML table output
echo "<html><meta charset='UTF-8'><body>";
echo "<table border='1'>";
echo "<tr>
        <th>SR NO</th>
        <th>Patient ID</th>
        <th>Patient Name</th>
        <th>Gender</th>
        <th>Age</th>
        <th>Lab Name</th>
        <th>Dispatch Type</th>
        <th>Order Amount(B2C)</th>
        <th>B2B Amount</th>
        <th>Test Name</th>
        <th>Booking Date</th>
        <th>Status</th>
      </tr>";

// Base query
$searchQuery = "SELECT * FROM test_requests WHERE 1";
$bindTypes = "";
$bindParams = [];

if (!empty($query)) {
    $searchQuery .= " AND patient_name LIKE ?";
    $bindTypes .= "s";
    $bindParams[] = "%$query%";
}
if (!empty($status)) {
    $searchQuery .= " AND status = ?";
    $bindTypes .= "s";
    $bindParams[] = $status;
}
if (!empty($dispatch_option)) {
    $searchQuery .= " AND dispatch_option = ?";
    $bindTypes .= "s";
    $bindParams[] = $dispatch_option;
}
if (!empty($lab_name)) {
    $searchQuery .= " AND lab_name = ?";
    $bindTypes .= "s";
    $bindParams[] = $lab_name;
}
if (!empty($selected_date)) {
    $searchQuery .= " AND DATE(created_at) = ?";
    $bindTypes .= "s";
    $bindParams[] = $selected_date;
}

$searchQuery .= " ORDER BY created_at DESC";

$stmt = mysqli_prepare($db_conn, $searchQuery);
if (!empty($bindTypes)) {
    mysqli_stmt_bind_param($stmt, $bindTypes, ...$bindParams);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Output table rows
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['patient_id']}</td>
            <td>{$row['patient_name']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['age']}</td>
            <td>{$row['lab_name']}</td>
            <td>{$row['dispatch_option']}</td>
            <td>₹{$row['order_amount']}</td>
            <td>₹{$row['b2b_amount']}</td>
            <td>{$row['selected_test']}</td>
            <td>" . date("d M Y, h:i A", strtotime($row['created_at'])) . "</td>
            <td>{$row['status']}</td>
          </tr>";
}

echo "</table></body></html>";
