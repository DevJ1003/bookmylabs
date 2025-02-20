<?php

include "includes/functions.php";
require_once(__DIR__ . '/includes/tcpdf/tcpdf.php');

if (isset($_GET['sr_no']) && isset($_GET['patient_name'])) {
    $sr_no = $_GET['sr_no'];
    $patient_name = $_GET['patient_name'];
} else {
    die("Invalid invoice request.");
}

$franchise_id = $_SESSION['id'];
$franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

$invoiceDataQuery = "SELECT * FROM `test_requests` WHERE id = '$sr_no' AND franchise_id = '$franchise_id' AND patient_name = '$patient_name'";
$query = query($invoiceDataQuery);
confirm($query);

$row = mysqli_fetch_array($query);
if (!$row) {
    die("Invoice not found.");
}

$patient_id = $row['patient_id'];
$patient_age = $row['age'];
$patient_gender = $row['gender'];
$patient_mobile = $row['mobile'];
$franchise_name = $row['franchise_name'];
$lab_name = strtoupper($row['lab_name']);
$test_names = explode(",", $row['selected_test']);
$formattedDate = date('jS F Y, h:i A', strtotime($row['created_at']));

$test_details = [];
$total_price = 0;

foreach ($test_names as $printed_test) {
    $printed_test = trim($printed_test);
    $fetchTestDetails = "SELECT DISTINCT code, B2C FROM `tests_" . strtolower($lab_name) . "` WHERE test_name = ?";
    $stmt = mysqli_prepare($db_conn, $fetchTestDetails);
    mysqli_stmt_bind_param($stmt, "s", $printed_test);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($test_row = mysqli_fetch_array($result)) {
        $test_details[] = [
            'code' => $test_row['code'],
            'test_name' => $printed_test,
            'test_price' => $test_row['B2C']
        ];
        $total_price += $test_row['B2C'];
    }
}

// $pdf = new TCPDF();
// $pdf->SetAutoPageBreak(TRUE, 10);
// $pdf->AddPage();
// $pdf->SetFont('dejavusans', '', 12);
$pdf = new TCPDF();
$pdf->SetPrintHeader(false); // Disable default header
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// Fetch lab logo path from database
$getLabLogoQuery = "SELECT lab_logo FROM `labs` WHERE lab_name = '$lab_name'";
$query = query($getLabLogoQuery);
confirm($query);
$row = mysqli_fetch_array($query);
$lab_logo = $row['lab_logo'];
$lab_logo_path = 'src/images/labs_images/' . $lab_logo; // Adjust path as per your folder structure

// Add Lab Logo to PDF (Centered)
if (file_exists($lab_logo_path)) {
    $pdf->Image($lab_logo_path, 60, 10, 80, 25, '', '', '', true, 300, '', false, false, 0, false, false, false);
}

// Move content below the logo
$pdf->Ln(30);

// Invoice Title (Bold)
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, "INVOICE", 0, 1, 'C');
$pdf->Ln(5);

// Reset to normal font
$pdf->SetFont('dejavusans', '', 12);

$html = '
<table width="100%" cellspacing="0" cellpadding="5">
    <tr>
        <!-- Left Side: Date, Franchise Name, Lab Name -->
        <td width="50%" style="vertical-align: top;">
            <p><strong>Date:</strong> ' . $formattedDate . '</p>
            <p><strong>Franchise Name:</strong> ' . $franchise_name . '</p>
            <p><strong>Lab Name:</strong> ' . $lab_name . '</p>
        </td>

        <!-- Right Side: Patient Details (Aligned Properly) -->
        <td width="50%" style="text-align: right; vertical-align: top;">
            <p><strong>Patient ID:</strong> ' . $patient_id . '</p>
            <p><strong>Name:</strong> ' . $patient_name . '</p>
            <p><strong>Gender & Age:</strong> ' . $patient_gender . ', ' . $patient_age . '</p>
            <p><strong>Contact:</strong> ' . $patient_mobile . '</p>
        </td>
    </tr>
</table>
<hr>

<!-- Test Details -->
<h3 style="margin-top: 15px; margin-bottom: 10px;">Test Details:</h3>
<table border="1" cellpadding="6">
<tr>
    <th><strong>Test Code</strong></th>
    <th><strong>Test Name</strong></th>
    <th><strong>Price</strong></th>
</tr>';


foreach ($test_details as $test) {
    $html .= '<tr>
        <td>' . $test['code'] . '</td>
        <td>' . $test['test_name'] . '</td>
        <td>&#8377;' . $test['test_price'] . '/-</td>
    </tr>';
}

$html .= '<tr>
    <td colspan="2"><strong>Total</strong></td>
    <td><strong>&#8377;' . $total_price . '/-</strong></td>
</tr>
</table>
<p style="text-align:center;">Thank You!</p>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice_' . $sr_no . '.pdf', 'D');
