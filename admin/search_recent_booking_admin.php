<?php
include "../includes/db.php";

$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$dispatch_option = isset($_GET['dispatch_option']) ? trim($_GET['dispatch_option']) : '';
$lab_name = isset($_GET['lab_name']) ? trim($_GET['lab_name']) : '';
$selected_date = isset($_GET['date']) ? trim($_GET['date']) : '';

// Base query
$searchQuery = "SELECT * FROM test_requests WHERE 1";
$params = [];
$param_types = "";

// Dynamically add conditions & parameters
if (!empty($query)) {
    $searchQuery .= " AND patient_name LIKE ?";
    $params[] = "%$query%";
    $param_types .= "s";
}
if (!empty($status)) {
    $searchQuery .= " AND status = ?";
    $params[] = $status;
    $param_types .= "s";
}
if (!empty($dispatch_option)) {
    $searchQuery .= " AND dispatch_option = ?";
    $params[] = $dispatch_option;
    $param_types .= "s";
}
if (!empty($lab_name)) {
    $searchQuery .= " AND lab_name = ?";
    $params[] = $lab_name;
    $param_types .= "s";
}
if (!empty($selected_date)) {
    $searchQuery .= " AND DATE(created_at) = ?";
    $params[] = $selected_date;
    $param_types .= "s";
}

$searchQuery .= " ORDER BY created_at DESC";

// Prepare and Bind Parameters
$stmt = mysqli_prepare($db_conn, $searchQuery);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $param_types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Output Data
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td><input type='checkbox'></td>
            <td>{$row['id']}</td>
            <td>{$row['franchise_name']}</td>
            <td>{$row['patient_name']}</td>
            <td>{$row['gender']}</td>
            <td>{$row['age']}</td>
            <td>{$row['lab_name']}</td>
            <td>{$row['dispatch_option']}</td>
            <td>₹{$row['order_amount']}</td>
            <td>{$row['selected_test']}</td>
            <td>" . date("d M Y, h:i A", strtotime($row['created_at'])) . "</td>
            <td>{$row['status']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='13' style='text-align:center;'>No results found</td></tr>";
}
