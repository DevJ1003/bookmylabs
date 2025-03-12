<?php
// Include DB connection
include('../includes/db.php');

if (!$db_conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

ob_start(); // Start output buffering
ob_clean(); // Clean output buffer to avoid whitespace issues

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=membership_data.xls");
header("Pragma: no-cache");
header("Expires: 0");

$sql = "SELECT * FROM `membership`";

$result = mysqli_query($db_conn, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($db_conn));
}

// Print column headers
echo "SR NO\tFranchise Name\tFull Name\tEmail\tPhone Number\tAddress\tUPI Reference\tCreated at\n";

$sr_no = 1;
while ($row = mysqli_fetch_array($result)) {
    echo $sr_no . "\t" .
        $row['franchise_name'] . "\t" .
        $row['name'] . "\t" .
        $row['email'] . "\t" .
        $row['phone'] . "\t" .
        $row['address'] . "\t" .
        $row['upi_reference'] . "\t" .
        $row['created_at'] . "\n";
    $sr_no++;
}

exit;
