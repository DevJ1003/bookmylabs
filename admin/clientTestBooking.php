<?php include "../includes/db.php";
include "../includes/functions.php";
?>

<?php
// Get parameters from URL
$franchise_name = $_GET['franchise_name'] ?? '';
$token = $_GET['token'] ?? '';

// Validate the secure token from the database
$query = "SELECT * FROM franchises WHERE agency_name = '$franchise_name' AND secure_token = '$token'";
$result = mysqli_query($db_conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("<div class='error-message'>⛔ Invalid or tampered URL! Access denied.</div>");
}

// Generate Unique Patient ID
$patient_id = generatePatientID($db_conn);

// Fetch franchise details
$franchise = mysqli_fetch_assoc($result);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $patient_name = mysqli_real_escape_string($db_conn, $_POST['patient_name']);
    $patient_age = mysqli_real_escape_string($db_conn, $_POST['patient_age']);
    $patient_email = mysqli_real_escape_string($db_conn, $_POST['patient_email']);
    $patient_phone = mysqli_real_escape_string($db_conn, $_POST['patient_phone']);
    $patient_address = mysqli_real_escape_string($db_conn, $_POST['patient_address']);
    $test_type = mysqli_real_escape_string($db_conn, $_POST['test_type']);
    $orderAmount = $_POST['order_amount'];

    // File Upload Handling (Prescription)
    $prescription = "";
    $uploadDir = "../src/images/test_form_images/"; // Define the upload directory

    if (!empty($_FILES['prescription']['name'])) {
        $fileName = basename($_FILES['prescription']['name']);
        $targetFilePath = $uploadDir . $fileName; // Full path for saving the file

        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['prescription']['tmp_name'], $targetFilePath)) {
            $prescription = $fileName; // Store only the filename in the database
        } else {
            echo "<p class='error-message'>❌ Error uploading file!</p>";
        }
    }

    $insertQuery = "INSERT INTO `test_requests` (franchise_name, patient_id, patient_name, age, patient_email, selected_test, mobile, address, attachments, order_amount, created_at) 
                    VALUES ('$franchise_name', '$patient_id', '$patient_name', '$patient_age', '$patient_email', '$test_type', '$patient_phone', '$patient_address', '$prescription', '$orderAmount', NOW())";

    if (mysqli_query($db_conn, $insertQuery)) {
        // echo "<p class='success-message'>✅ Booking successful!</p>";
        // redirect("confirmation.php");
        // exit();
        echo "<script>
        window.location.href = 'confirmation.php';
    </script>";
        exit();
    } else {
        echo "<p class='error-message'>❌ Error: " . mysqli_error($db_conn) . "</p>";
    }
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