<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Include PHPMailer

include "../includes/db.php";
include "../includes/functions.php";

// Admin email (Static)
$admin_email = 'devjoshi1384@gmail.com';

// Get parameters from URL securely
$franchise_name = mysqli_real_escape_string($db_conn, $_GET['franchise_name'] ?? '');
$token = mysqli_real_escape_string($db_conn, $_GET['token'] ?? '');

// Validate secure token using prepared statement
$query = "SELECT * FROM franchises WHERE agency_name = ? AND secure_token = ?";
$stmt = mysqli_prepare($db_conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $franchise_name, $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    die("<div class='error-message'>⛔ Invalid or tampered URL! Access denied.</div>");
}

$franchise = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Extract franchise email
$franchise_email = $franchise['email'] ?? '';

// Generate unique Patient ID
$patient_id = generatePatientIDTest($db_conn);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    // Sanitize form data
    $patient_name = trim(mysqli_real_escape_string($db_conn, $_POST['patient_name']));
    $patient_age = intval($_POST['patient_age']);
    $patient_email = filter_var($_POST['patient_email'], FILTER_SANITIZE_EMAIL);
    $patient_phone = trim(mysqli_real_escape_string($db_conn, $_POST['patient_phone']));
    $patient_address = trim(mysqli_real_escape_string($db_conn, $_POST['patient_address']));
    $test_type = trim(mysqli_real_escape_string($db_conn, $_POST['test_type']));
    $orderAmount = floatval($_POST['order_amount']);

    // Handle file upload securely
    $prescription = "";
    $uploadDir = "../src/images/test_form_images/";

    if (!empty($_FILES['prescription']['name'])) {
        $fileName = basename($_FILES['prescription']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = uniqid() . '.' . $fileExtension;
            $targetFilePath = $uploadDir . $newFileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['prescription']['tmp_name'], $targetFilePath)) {
                $prescription = $newFileName;
            } else {
                echo "<p class='error-message'>❌ Error uploading file!</p>";
            }
        } else {
            echo "<p class='error-message'>❌ Invalid file type!</p>";
        }
    }

    // Insert data using prepared statement
    $insertQuery = "INSERT INTO `test_requests` 
        (franchise_name, patient_id, patient_name, age, patient_email, selected_test, mobile, address, attachments, order_amount, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($db_conn, $insertQuery);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssssssd",
        $franchise_name,
        $patient_id,
        $patient_name,
        $patient_age,
        $patient_email,
        $test_type,
        $patient_phone,
        $patient_address,
        $prescription,
        $orderAmount
    );

    if (mysqli_stmt_execute($stmt)) {
        // Send confirmation emails to patient, franchise, and admin
        sendBookingEmail($patient_email, $patient_name, $test_type, $orderAmount);
        sendBookingEmail($franchise_email, $patient_name, $test_type, $orderAmount); // Franchise email
        sendBookingEmail($admin_email, $patient_name, $test_type, $orderAmount); // Admin email

        echo "<script>
            alert('Booking successful! Confirmation email sent.');
            window.location.href = 'confirmation.php';
        </script>";
    } else {
        echo "<p class='error-message'>❌ Failed to save booking!</p>";
    }

    mysqli_stmt_close($stmt);
}

// Function to send booking confirmation email
function sendBookingEmail($toEmail, $patientName, $testType, $orderAmount)
{
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'devjoshi1384@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'cxry slzk yhhj hlyn';   // Replace with your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('devjoshi1384@gmail.com', 'BookMyLabs Support!');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Test Booking Confirmation';
        $mail->addEmbeddedImage('../vendors/images/BOOK-MY-LAB.jpg', 'logo', 'BOOK-MY-LAB.jpg');

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; color: #333; border: 1px solid #ddd; border-radius: 8px; max-width: 600px; margin: auto;'>
                <!-- Logo Section -->
                <div style='text-align: center; margin-bottom: 20px;'>
                    <img src='cid:logo' alt='Lab Logo' style='max-width: 150px; height: auto;'>
                </div>
                <div style='background-color: #0056b3; padding: 15px; border-radius: 8px 8px 0 0; color: #ffffff; text-align: center;'>
                    <h2 style='margin: 0; font-size: 24px;'>Booking Confirmation</h2>
                </div>
                <div style='padding: 20px;'>
                    <p style='font-size: 16px; line-height: 1.6;'>
                        Hi <strong style='color: #0056b3;'>$patientName</strong>,
                    </p>
                    <p style='font-size: 16px; line-height: 1.6;'>
                        Your booking for <strong>$testType</strong> has been confirmed.
                    </p>
                    <table style='width: 100%; margin-top: 15px; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 10px; border: 1px solid #ddd; background-color: #f1f1f1; font-weight: bold; width: 50%;'>Patient Name:</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'>$patientName</td>
                        </tr>
                        <tr>
                            <td style='padding: 10px; border: 1px solid #ddd; background-color: #f1f1f1; font-weight: bold;'>Test Type:</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'>$testType</td>
                        </tr>
                        <tr>
                            <td style='padding: 10px; border: 1px solid #ddd; background-color: #f1f1f1; font-weight: bold;'>Order Amount:</td>
                            <td style='padding: 10px; border: 1px solid #ddd;'>₹" . number_format($orderAmount, 2) . "</td>
                        </tr>
                    </table>
                    <p style='font-size: 16px; line-height: 1.6; margin-top: 20px;'>
                        Thank you for choosing our service!
                    </p>
                </div>
                <div style='background-color: #f1f1f1; padding: 10px; border-radius: 0 0 8px 8px; text-align: center; font-size: 14px; color: #777;'>
                    If you have any questions, please contact us at <a href='mailto:support@bookmylab.com' style='color: #0056b3; text-decoration: none;'>support@bookmylab.com</a>.
                </div>
            </div>
        ";

        $mail->Timeout = 300;
        $mail->SMTPKeepAlive = true;
        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: {$mail->ErrorInfo}");
    }
}

// Function to generate a unique patient ID
function generatePatientIDTest($conn)
{
    do {
        $patient_id = 'P' . mt_rand(100000, 999999);
        $query = "SELECT COUNT(*) FROM `test_requests` WHERE patient_id = '$patient_id'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_fetch_array($result)[0];
    } while ($count > 0);

    return $patient_id;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Test Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: white;
            padding: 20px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .logo {
            width: 150px;
            margin-bottom: 15px;
        }

        h2 {
            color: #333;
            font-size: 22px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            text-align: left;
            margin: 8px 0 5px;
            font-weight: bold;
        }

        input,
        select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .file-input {
            border: none;
            background: #f8f8f8;
            padding: 10px;
        }

        button {
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            padding: 12px;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 5px;
        }

        button:hover {
            background-color: #218838;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
    <!-- Styles for the Modal -->
    <style>
        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        /* Modal Container Fix */
        .modal-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            z-index: 100;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            max-height: 80vh;
            overflow: hidden;
        }

        /* Keep the confirm button inside */
        .modal-body {
            flex: 1;
            overflow-y: auto;
            max-height: 400px;
            padding-bottom: 10px;
        }

        .confirm-button-container {
            width: 100%;
            padding: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            background: white;
        }

        .close-button {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: blue;
        }

        .close-button:hover {
            color: red;
            background: none;
        }

        /* Modal Body */
        .modal-body {
            margin-top: 10px;
        }

        /* Table Styling */
        .test-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: black;
            border-radius: 6px;
            overflow: hidden;
        }

        .test-table th {
            background: #0056b3;
            color: white;
        }

        .test-table th,
        .test-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        /* Select Button */
        .btn-select {
            background: #0056b3;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-select:hover {
            background: #004099;
        }

        /* Open Modal Button */
        .btn-open-modal {
            background: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-open-modal:hover {
            background: #0056b3;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -55%);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            background: white;
            color: black;
            padding: 20px;
            border-radius: 8px;
            z-index: 100;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            max-height: 500px;
            /* Prevents modal from overflowing */
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            max-height: 400px;
            /* Ensures scrolling */
            padding-bottom: 10px;
        }

        .confirm-button-container {
            width: 100%;
            padding: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            background: white;
        }
    </style>

</head>

<body>
    <div class="container">
        <img src="../vendors/images/BOOK-MY-LAB.jpg" alt="Lab Logo" class="logo">
        <h2>Book a Test for <strong style="color: #ff5733; font-size: 24px; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);">
                <?php echo htmlspecialchars($franchise_name); ?>
            </strong></h2>

        <form action="" id="clientTestBooking" method="POST" enctype="multipart/form-data">

            <label><i class="fa fa-user"></i> Patient Name:</label>
            <input type="text" name="patient_name" required>

            <label><i class="fa fa-birthday-cake"></i> Age:</label>
            <input type="number" name="patient_age" min="0" required>

            <label><i class="fa fa-envelope"></i> Email:</label>
            <input type="email" name="patient_email" required>

            <label><i class="fa fa-phone"></i> Phone:</label>
            <input type="text" name="patient_phone" required>

            <label><i class="fa fa-map-marker-alt"></i> Address:</label>
            <input type="text" name="patient_address">

            <!-- Open Modal Button -->
            <button type="button" class="btn-open-modal" onclick="openModal()">Select Test</button>


            <!-- Selected Test Display -->
            <div id="selectedTestDisplay" style="margin-top: 10px; font-weight: bold; color: #333;"></div>


            <!-- Hidden Input to Store Selected Tests -->
            <input type="hidden" name="test_type" id="selected_test" required>
            <input type="hidden" id="orderAmount" name="order_amount" required>


            <!-- Modal Background Overlay -->
            <div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>







            <div class="modal-container" id="testModal">
                <!-- Close Button -->
                <button class="close-button" onclick="closeModal()">✖</button>

                <div class="modal-header">
                    <h3>Select Tests</h3>
                </div>

                <div class="modal-body">
                    <!-- Search Bar -->
                    <input type="text" id="searchTest" placeholder="Search test..." onkeyup="filterTests()"
                        style="width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px;">

                    <table class="test-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Code</th>
                                <th>Test Name</th>
                                <th>B2C Price</th>
                            </tr>
                        </thead>
                        <tbody id="testList">
                            <?php clientFormTestNames(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Confirm Button -->
                <div class="confirm-button-container">
                    <!-- <button class="btn-open-modal" onclick="confirmSelection()">Confirm Selection</button> -->
                    <button type="button" id="openModalBtn" onclick="confirmSelection()">Select Test</button>

                </div>
            </div>

            <!-- Modal Overlay -->
            <div id="modalOverlay" class="modal-overlay" onclick="closeModal()"></div>











            <br>
            <label><i class="fa fa-file-medical"></i> Upload Prescription (Optional):</label>
            <input type="file" name="prescription" class="file-input">

            <button type="submit" name="submit_booking"><i class="fa fa-check-circle"></i> Book Test</button>
        </form>
    </div>



    <script>
        function openModal() {
            document.getElementById("testModal").style.display = "flex";
            document.getElementById("modalOverlay").style.display = "block";
        }

        function closeModal() {
            document.getElementById("testModal").style.display = "none";
            document.getElementById("modalOverlay").style.display = "none";
        }

        // Search function
        function filterTests() {
            let input = document.getElementById("searchTest").value.toLowerCase();
            let rows = document.querySelectorAll("#testList tr");

            rows.forEach(row => {
                let code = row.cells[1].innerText.toLowerCase();
                let name = row.cells[2].innerText.toLowerCase();
                row.style.display = (code.includes(input) || name.includes(input)) ? "" : "none";
            });
        }

        function confirmSelection() {
            let selectedTestNames = [];
            let selectedTestDetails = [];
            let totalPrice = 0;

            document.querySelectorAll("#testList input[type='checkbox']:checked").forEach(checkbox => {
                let testName = checkbox.dataset.testName;
                let testPrice = parseFloat(checkbox.dataset.testPrice);

                selectedTestNames.push(testName);
                selectedTestDetails.push(`${testName} - ₹${testPrice.toFixed(2)}`);
                totalPrice += testPrice;
            });

            // Store selected test names instead of test codes
            document.getElementById("selected_test").value = selectedTestNames.join(", ");

            // Update total price in hidden field
            document.getElementById("orderAmount").value = totalPrice.toFixed(2);

            // Display selected tests
            if (selectedTestDetails.length > 0) {
                document.getElementById("selectedTestDisplay").innerHTML =
                    `<h3 style="margin-top:10px;color:#0056b3;">Selected Tests</h3>` +
                    selectedTestDetails.join("<br>") +
                    `<hr><strong>Total Price: ₹${totalPrice.toFixed(2)}</strong>`;
            } else {
                document.getElementById("selectedTestDisplay").innerHTML = "";
            }

            closeModal();
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", function(event) {
                    // event.preventDefault(); // Prevents form from reloading the page
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            function updateOrderAmount(totalAmount) {
                document.getElementById("orderAmount").value = totalAmount;
            }

            let totalAmount = calculateTotalAmount(); // Your function to calculate total
            updateOrderAmount(totalAmount);
        });
    </script>
</body>

</html>