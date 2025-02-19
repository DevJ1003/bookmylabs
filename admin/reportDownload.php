<?php
// This file is used to handle secure download of patient test reports.

if (isset($_GET['file'])) {
    $file_name = basename($_GET['file']);

    $file_path = realpath(__DIR__ . "/reportFiles/" . $file_name);

    if (!file_exists($file_path)) {
        die("Error: File not found at " . realpath($file_path));
    }

    header("Content-Description: File Transfer");
    header("Content-Type: application/pdf");
    header("Content-Disposition: attachment; filename=\"" . $file_name . "\"");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    header("Content-Length: " . filesize($file_path));

    flush();
    readfile($file_path);
    exit;
} else {
    die("Error: No file specified.");
}
