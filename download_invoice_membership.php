<?php

include "includes/functions.php";
require_once(__DIR__ . '/includes/tcpdf/tcpdf.php');

if (isset($_GET['sr_no']) && isset($_GET['membership'])) {
    $sr_no = $_GET['sr_no'];
    $membership = $_GET['membership'];
} else {
    die("Invalid invoice request.");
}

$franchise_id = $_SESSION['id'];
$franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

$invoiceDataQuery = "SELECT * FROM `membership` WHERE id = '$sr_no' AND franchise_id = '$franchise_id' AND name = '$membership'";
$query = query($invoiceDataQuery);
confirm($query);

$row = mysqli_fetch_array($query);
if (!$row) {
    die("Invoice not found.");
}

$franchise_name = $row['franchise_name'];
$email = $row['email'];
$phone = $row['phone'];
$address = $row['address'];
$upi_reference = $row['upi_reference'];
$formattedDate = date('jS F Y, h:i A', strtotime($row['created_at']));

$pdf = new TCPDF();
$pdf->SetPrintHeader(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// Load Logo from Folder
$pdf->Image('vendors/images/BOOK-MY-LAB.jpg', 60, 10, 80, 25, '', '', '', true, 300, '', false, false, 0, false, false, false);

$pdf->Ln(30);
$pdf->SetFont('dejavusans', 'B', 14);
$pdf->Cell(0, 10, "INVOICE", 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('dejavusans', '', 12);

$html = '
<table width="100%" cellspacing="0" cellpadding="5" align="center" style="text-align:center;">
    <tr>
        <td>
            <p><strong>Date:</strong> ' . $formattedDate . '</p>
            <p><strong>Franchise Name:</strong> ' . $franchise_name . '</p>
            <p><strong>Member Name:</strong> ' . $membership . '</p>
            <p><strong>Email:</strong> ' . $email . '</p>
            <p><strong>Phone:</strong> ' . $phone . '</p>
            <p><strong>Address:</strong> ' . $address . '</p>
            <p><strong>UPI Reference:</strong> ' . $upi_reference . '</p>
        </td>
    </tr>
</table>
<hr>
<p style="text-align:center;">Thank You!</p>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('invoice_' . $sr_no . '.pdf', 'D');
