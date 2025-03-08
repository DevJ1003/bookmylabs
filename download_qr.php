<?php include "includes/db.php";
include "includes/functions.php";

$franchise_name = $_SESSION['agency_name'];

$qrQuery = "SELECT booking_qr FROM franchises WHERE `agency_name` = '$franchise_name'";
$result = query($qrQuery);
confirm($result);
$row = mysqli_fetch_assoc($result);


if ($row && !empty($row['booking_qr'])) {
    $filename = $row['booking_qr'];
    $qr_code_path = __DIR__ . "/src/images/booking_qr/" . $filename;

    if (file_exists($qr_code_path)) {
        header("Content-Type: image/png");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Length: " . filesize($qr_code_path));
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");

        readfile($qr_code_path);
        exit;
    } else {
        setMessage("QR Code file not found. Checked path: " . $qr_code_path, "danger");
    }
} else {
    setMessage("No QR Code found for this franchise.", "danger");
}
