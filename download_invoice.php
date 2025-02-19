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

$pdf = new TCPDF();
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

$html = '
<h2 style="text-align:center;">INVOICE</h2>
<p><strong>Date:</strong> ' . $formattedDate . '</p>
<p><strong>Franchise Name:</strong> ' . $franchise_name . '</p>
<p><strong>Lab Name:</strong> ' . $lab_name . '</p>
<hr>
<h3>Patient Details:</h3>
<p><strong>Patient Name:</strong> ' . $patient_name . '</p>
<p><strong>Age:</strong> ' . $patient_age . '</p>
<p><strong>Gender:</strong> ' . $patient_gender . '</p>
<p><strong>Contact:</strong> ' . $patient_mobile . '</p>
<hr>
<h3>Test Details:</h3>
<table border="1" cellpadding="5">
<tr>
    <th>Test Code</th>
    <th>Test Name</th>
    <th>Price</th>
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
