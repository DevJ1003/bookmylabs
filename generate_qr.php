<?php
require_once __DIR__ . "/vendor/autoload.php";

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Define QR Code Content
$booking_url = "https://localhost/newtemp";

// Create QR Code Object (Old Syntax)
$qrCode = new QrCode($booking_url);
// $qrCode->setSize(300); // If your version doesn't support this, REMOVE this line

// Define the QR Code File Path
$qr_filename = __DIR__ . "/test_qr.png";

// Create a Writer Instance
$writer = new PngWriter();
$result = $writer->write($qrCode);

// Save QR Code to File
file_put_contents($qr_filename, $result->getString());

echo "QR Code saved successfully as $qr_filename";
