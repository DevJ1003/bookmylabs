<?php
require_once __DIR__ . "/vendor/autoload.php";

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel; // ✅ Correct namespace

$test_url = "https://example.com";
$qr_code_path = __DIR__ . "/test_qr.png";

// ✅ Corrected: Use constructor directly instead of fromDefaults() or create()
$builder = new Builder(
    writer: new PngWriter(),
    data: $test_url,
    encoding: new Encoding('UTF-8'),
    errorCorrectionLevel: ErrorCorrectionLevel::Low, // ✅ Correct syntax
);

$qrCode = $builder->build();

// Save QR Code to file
file_put_contents($qr_code_path, $qrCode->getString());

echo "✅ QR Code generated successfully at: $qr_code_path";
