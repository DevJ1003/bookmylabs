<?php

include "db.php";
include "helpers/csrf_token.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;

// This file contains all the functions that are required to perform CRUD operations on the database

/*********************************************** HELPER FUNCTIONS *************************************/

// // function setMessage() is used to set messages
// function setMessage($msg)
// {
//     if (!empty($msg)) {
//         $_SESSION['message'] = $msg;
//     } else {
//         $msg = "";
//     }
// }

// // function displayMessage() is used to display the messages from setMessage function
// function displayMessage()
// {
//     if (isset($_SESSION['message'])) {
//         echo $_SESSION['message'];
//         unset($_SESSION['message']);
//     }
// }

// Function to set message with type (success, warning, danger, info)
function setMessage($msg, $type = 'success')
{
    if (!empty($msg)) {
        $_SESSION['message'] = [
            'text' => $msg,
            'type' => $type // success, warning, danger, info
        ];
    }
}

// Function to display the alert message with an icon
function displayMessage()
{
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message']['text'];
        $type = $_SESSION['message']['type'];

        // Assign icons based on message type
        if ($type == "success") {
            $icon = "✅"; // Green check mark
        } elseif ($type == "warning") {
            $icon = "⚠️"; // Warning symbol
        } elseif ($type == "danger") {
            $icon = "❌"; // Red cross
        } elseif ($type == "info") {
            $icon = "ℹ️"; // Information icon
        } else {
            $icon = ""; // No icon if type is unknown
        }

        echo "<div id='autoHideAlert' class='alert alert-$type' role='alert'>
                $icon $message
              </div>";

        unset($_SESSION['message']); // Clear message after displaying
    }
}


// function redirect() is used to redirect the pages
function redirect($location)
{
    header("Location: $location");
}

// function query($sql) used to execute the query
function query($sql)
{
    global $db_conn;
    return mysqli_query($db_conn, $sql);
}

// function confirm() is used to check if the query failed while execution
function confirm($result)
{
    global $db_conn;
    if (!$result) {
        die("QUERY FAILED" . mysqli_error($db_conn));
    }
}

// function escape_string() is used to escape the string
function escape_string($string)
{
    global $db_conn;
    return mysqli_real_escape_string($db_conn, $string);
}

// function fetch_array() is used to fetch the array
function fetch_array($result)
{
    return mysqli_fetch_array($result);
}

// function IsLoggedIn() is used to check if the user is logged in or not
function IsLoggedIn()
{
    if (isset($_SESSION['username'])) {
        return true;
    } else {
        return false;
    }
}

// function get_user_name() is used to get the username from the session
function get_user_name()
{
    if (isset($_SESSION['username'])) {
        return $_SESSION['username'];
    }
}

// function get_user_id() is used to get the user id from the session
function get_user_id()
{
    if (isset($_SESSION['id'])) {
        return $_SESSION['id'];
    }
}

/**************************************** END OF HELPER FUNCTIONS ****************************************/
// 
// 
// 
// 
/*************************************** REGISTER/LOGIN FUNCTIONS ****************************************/

// function registerUser() is used to register the user
function registerUser()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

        $username = $_POST['username'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $usertype = $_POST['usertype'];

        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));

        $registerQuery = "INSERT INTO `users` (username, email, city, state, usertype, password) ";
        $registerQuery .= "VALUES('{$username}', '{$email}', '{$city}', '{$state}', '{$usertype}' , '{$hashed_password}') ";
        $query = query($registerQuery);
        confirm($query);

        setMessage("User Registered!", "success");
        redirect("login");
    }
}

// function loginUser() is a login function 
function loginUser()
{
    global $db_conn;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_form'])) {
        // CSRF token validation
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            setMessage("Invalid CSRF token.", "danger");
            redirect("login");
            exit();
        }

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $remember = trim($_POST['remember']);

        $email = mysqli_real_escape_string($db_conn, $email);
        $password = mysqli_real_escape_string($db_conn, $password);

        $loginQuery = "SELECT * FROM `franchises` WHERE email = '$email' ";
        $query = query($loginQuery);
        confirm($query);

        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_array($query)) {
                $db_userid = $row['id'];
                $db_email = $row['email'];
                $dbpassword = $row['password'];

                if (password_verify($password, $dbpassword)) {
                    $_SESSION['id'] = $db_userid;
                    $_SESSION['email'] = $db_email;
                    $_SESSION['usertype'] = $row['usertype'];
                    $_SESSION['agency_name'] = $row['agency_name'];

                    // Handle "Remember Me" functionality
                    if ($remember) {
                        $token = bin2hex(random_bytes(32)); // Generate a secure token
                        $expires = time() + (30 * 24 * 60 * 60); // 30 days

                        // Store token in database
                        $tokenQuery = "UPDATE `franchises` SET remember_token='$token' WHERE id='$db_userid'";
                        query($tokenQuery);
                        confirm($tokenQuery);

                        // Set secure cookie
                        setcookie("remember_token", $token, $expires, "/", "", true, true);
                    }

                    if ($_SESSION['usertype'] == 'Admin') {
                        setMessage("Valid credentials. You are now logged in.", "success");
                        redirect("admin");
                        exit();
                    } else {
                        setMessage("Valid credentials. You are now logged in.", "success");
                        redirect("index");
                        exit();
                    }
                } else {
                    setMessage("Invalid credentials. Please try again.", "danger");
                    redirect("login");
                    exit();
                }
            }
        } else {
            setMessage("Invalid credentials. Please try again.", "danger");
            redirect("login");
            exit();
        }
    }
}

// function checkRememberedUser() used to check the cookies for user login
function checkRememberedUser()
{
    global $db_conn;

    if (!isset($_SESSION['id']) && isset($_COOKIE['remember_token'])) {
        $token = mysqli_real_escape_string($db_conn, $_COOKIE['remember_token']);

        $query = "SELECT * FROM `franchises` WHERE remember_token = '$token'";
        $result = query($query);
        confirm($result);

        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_array($result);
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['agency_name'] = $user['agency_name'];
        }
    }
}

/************************************ END OF REGISTER/LOGIN FUNCTIONS ************************************/
// 
// 
// 
// 
/************************************** FRANCHISE MODULE FUNCTIONS ***************************************/

// function generatePatientID() used to generate random patient id
function generatePatientID()
{
    $year = date('Y');  // Get current year (e.g., 2024)

    do {
        $randomNumber = mt_rand(10000, 99999); // Generate a 5-digit number
        $patientID = 'P' . $year . $randomNumber; // Format: PYYYYxxxxx

        // Check if the generated ID already exists
        $checkQuery = "SELECT COUNT(*) as count FROM `test_requests` WHERE patient_id = '$patientID'";
        $result = query($checkQuery);
        $row = mysqli_fetch_assoc($result);
        $exists = $row['count'] > 0;
    } while ($exists); // Regenerate if ID already exists

    return $patientID;
}



// function TestRequestForm() is used to submit the test request form
function TestRequestForm()
{
    global $db_conn;

    $franchise_id = $_SESSION['id'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

    if (isset($_GET['lab_name'])) {
        $lab_name = $_GET['lab_name'];
    } else {
        $lab_name = "Unknown Lab";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_request'])) {

        $franchise_name = $_SESSION['agency_name'];
        $lab_name = $_POST['lab_name'];
        $patient_name = $_POST['name'];
        $patient_age = $_POST['age'];
        $patient_gender = $_POST['gender'];
        $patient_mobile = $_POST['mobile'];
        $patient_address = $_POST['address'];

        // Generate Unique Patient ID
        $patient_id = generatePatientID($db_conn);

        // Get selected tests
        $selected_test_names = array_unique(explode(',', $_POST['selectedTestNames']));
        $selected_tests_string = implode(',', $selected_test_names);

        $patient_dispatch_option = $_POST['dispatchOption'];
        $patient_sample_drawn_date = $_POST['drawnDate'];
        $patient_sample_drawn_time = $_POST['drawnTime'];
        $patient_fasting_status = $_POST['fastingStatus'];
        $patient_reference_doctor = $_POST['referenceDoctor'];

        $image = $_FILES['file']['name'];
        $temp_image = $_FILES['file']['tmp_name'];
        move_uploaded_file($temp_image, "src/images/test_form_images/$image");

        // Order amount
        $order_amount = $_POST['orderAmount'];

        // Deduction amount
        $order_deduction_amount = $_POST['orderDeductionAmount'];

        if ($patient_age <= 0) {
            setMessage("Invalid age. Please enter a positive value.", "warning");
            exit();
        }

        // =================== Wallet Balance Check & Update ===================
        $fetchWalletBalanceFranchise = "SELECT `wallet_balance` FROM `franchises` WHERE id = '$franchise_id'";
        $fetchBalanceQuery = query($fetchWalletBalanceFranchise);
        confirm($fetchBalanceQuery);

        $row = mysqli_fetch_assoc($fetchBalanceQuery);
        $availableWalletBalance = $row['wallet_balance'] ?? 0;

        if ($availableWalletBalance > $order_amount) {
            $updateWalletBalanceQuery = "INSERT INTO `recharge_requests` (franchise_id, franchise_name, amount, status, created_at) ";
            $updateWalletBalanceQuery .= "VALUES ('$franchise_id', '$franchise_name', '-$order_deduction_amount', 'Approved', NOW())";
            $balanceQuery = query($updateWalletBalanceQuery);
            confirm($balanceQuery);
        } else {
            setMessage("Requested amount cannot be processed. Recharge your wallet!");
            exit();
        }
        // =====================================================================

        // Insert into test_requests table with generated Patient ID
        $testRequestQuery = "INSERT INTO `test_requests` (franchise_id, franchise_name, lab_name, patient_id, patient_name, age, gender, mobile, address, ";
        $testRequestQuery .= "selected_test, dispatch_option, sample_drawn_date, sample_drawn_time, fasting_status, reference_doctor, ";
        $testRequestQuery .= "attachments, order_amount, b2b_amount, created_at) ";
        $testRequestQuery .= "VALUES ('$franchise_id', '$franchise_name', '$lab_name', '$patient_id', '$patient_name', '$patient_age', '$patient_gender', ";
        $testRequestQuery .= "'$patient_mobile', '$patient_address', '$selected_tests_string', '$patient_dispatch_option', '$patient_sample_drawn_date', ";
        $testRequestQuery .= "'$patient_sample_drawn_time', '$patient_fasting_status', '$patient_reference_doctor', '$image', $order_amount, '$order_deduction_amount', NOW())";

        $query = query($testRequestQuery);
        confirm($query);

        setMessage("Your request has been submitted successfully! Patient ID: $patient_id", "success");
        redirect("recentBooking");
        exit();
    }
}


// function updateWalletBalanceFranchise() used to update wallet balance for franchise
function updateWalletBalanceFranchise()
{
    global $db_conn;

    $franchise_id = $_SESSION['id'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

    $fetchApprovedWalletBalance = "SELECT SUM(amount) AS total_amount FROM `recharge_requests` WHERE status = 'Approved' AND franchise_id = '$franchise_id' ";
    $query1 = query($fetchApprovedWalletBalance);
    confirm($query1);

    $row = mysqli_fetch_assoc($query1);
    $approvedWalletBalance = $row['total_amount'];

    $updateWalletBalanceFranchiseQuery = "UPDATE `franchises` SET wallet_balance = '$approvedWalletBalance' WHERE id = '$franchise_id'";
    query($updateWalletBalanceFranchiseQuery);
    confirm($updateWalletBalanceFranchiseQuery);
}

// // function selectedTestsAmount() is used to calculate the amount for selected tests
function selectedTestsAmount($lab_name, $test_ids)
{
    global $db_conn;

    if (!is_array($test_ids)) {
        $test_ids = explode(",", $test_ids);
    }

    if (!is_array($test_ids) || empty($test_ids)) {
        return 0;
    }
    $escaped_ids = array_map('intval', $test_ids);

    $test_ids_list = implode(", ", $escaped_ids);
    $lab_name = strtolower($lab_name);

    $query = "SELECT SUM(B2C) AS total_amount, SUM(B2B) AS deduction_amount FROM `tests_$lab_name` WHERE id IN ($test_ids_list)";
    $result = query($query);
    confirm($result);

    $row = mysqli_fetch_array($result);
    return [
        'total_amount' => $row['total_amount'] ?? 0,
        'deduction_amount' => $row['deduction_amount'] ?? 0
    ];
}

// function recentBookingsFranchise() is used to fetch the recent bookings
function recentBookingsFranchise()
{
    global $db_conn;

    $franchise_id = $_SESSION['id'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

    $recentBookingsQuery = "SELECT * FROM `test_requests` WHERE franchise_id = $franchise_id AND status = 'Pending' ORDER BY created_at DESC";
    $query = query($recentBookingsQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $sr_no = $row['id'];
        $patient_id = $row['patient_id'];
        $patient_name = $row['patient_name'];
        $patient_gender = $row['gender'];
        $patient_age = $row['age'];
        $selected_tests = $row['selected_test'];
        $lab_name = strtoupper($row['lab_name']);
        $patient_dispatch_option = $row['dispatch_option'];
        $order_amount = $row['order_amount'];
        $b2b_amount = $row['b2b_amount'];

        // date formatting
        $created_at = $row['created_at'];
        $originalDate = $created_at;
        $date = new DateTime($originalDate);
        $formattedDate = $date->format('jS F Y, h:i A');
        $status = $row['status'];
        $rejection_reason = $row['rejection_reason'];

        echo "<tr>";
        echo "<td><input type='checkbox'></td>";
        echo "<td>{$sr_no}</td>";
        echo "<td>{$patient_id}</td>";
        echo "<td>{$patient_name}</td>";
        echo "<td>{$patient_gender}</td>";
        echo "<td>{$patient_age}</td>";
        echo "<td>{$lab_name}</td>";
        echo "<td>{$patient_dispatch_option}</td>";
        echo "<td>₹{$order_amount}</td>";
        echo "<td>₹{$b2b_amount}</td>";
        echo "<td>{$selected_tests}</td>";
        echo "<td>{$formattedDate}</td>";
        echo "<td>{$status}</td>";
        echo "<td><a href='invoice?sr_no=$sr_no&patient_name=$patient_name' target='_blank'>View Invoice</a></td>";
        echo "<td>{$rejection_reason}</td>";
        echo "</tr>";
    }

    return null;
}

// function fetchWalletBalance() is used to fetch balance of the franchise's wallet
function fetchWalletBalance()
{
    global $db_conn;

    $franchise_id = $_SESSION['id'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

    $walletBalanceQuery = "SELECT wallet_balance FROM `franchises` WHERE id = $franchise_id ";
    $query = query($walletBalanceQuery);
    confirm($query);

    $result = mysqli_fetch_assoc($query);

    if ($wallet_balance = $result['wallet_balance']) {
        echo $wallet_balance;
    }

    return null;
}

// function rechargeRequests() is used to submit the franchise's request for wallet recharge 
function rechargeRequests()
{
    global $db_conn;

    $franchise_id = $_SESSION['id'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);
    $franchise_name = $_SESSION['agency_name'];
    $franchise_name = mysqli_real_escape_string($db_conn, $franchise_name);

    $upi_reference_id = $_SESSION['id'];
    $upi_reference_id = mysqli_real_escape_string($db_conn, $upi_reference_id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wallet_request'])) {

        $amount = $_POST['amount'];
        $upi_reference_id = $_POST['upi_reference'];

        $image = $_FILES['file']['name'];
        $temp_image = $_FILES['file']['tmp_name'];
        move_uploaded_file($temp_image, "src/images/upiReferenceImages/$image");

        if ($amount <= 0) {
            setMessage("Invalid amount. Please enter a possitive value.", "warning");
            exit();
        }

        $rechargeWalletQuery = "INSERT INTO `recharge_requests` (franchise_id, franchise_name, amount, upi_reference, attachments, created_at) ";
        $rechargeWalletQuery .= "VALUES ('$franchise_id', '$franchise_name', '$amount', '$upi_reference_id', '$image', NOW()) ";
        $query = query($rechargeWalletQuery);
        confirm($query);

        // if (confirm($query)) {
        //     echo "Your request has been submitted and is pending for approval";
        // } else {
        //     echo "There was an error submitting your request. Please try again.";
        // }
        exit();
    }
}

// function totalRevenue() used for calculating total revenue earned through B2C bookings
function totalRevenue($days = null)
{
    global $db_conn;
    $franchise_id = $_SESSION['id'];

    $query = "SELECT SUM(order_amount) AS total FROM test_requests WHERE status = 'Completed' AND franchise_id = ?";
    if ($days) {
        $query .= " AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
    }

    $stmt = mysqli_prepare($db_conn, $query);

    if ($days) {
        mysqli_stmt_bind_param($stmt, "ii", $franchise_id, $days);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $franchise_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}


// function totalFranchiseBooking() is used to fetch the total number of bookings
function totalFranchiseBooking($days = null)
{
    global $db_conn;
    $franchise_id = $_SESSION['id'];

    $query = "SELECT COUNT(*) AS total FROM test_requests WHERE franchise_id = ?";
    if ($days) {
        $query .= " AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
    }

    $stmt = mysqli_prepare($db_conn, $query);

    if ($days) {
        mysqli_stmt_bind_param($stmt, "ii", $franchise_id, $days);
    } else {
        mysqli_stmt_bind_param($stmt, "i", $franchise_id);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

// function fetchNumberOfLabs() used to fetch the number of labs
function fetchNumberOfLabs($days = null)
{
    global $db_conn;
    $query = "SELECT COUNT(DISTINCT lab_name) AS total_labs FROM `labs`";

    if ($days !== null) {
        $query .= " WHERE DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
    }

    $stmt = mysqli_prepare($db_conn, $query);

    if ($days !== null) {
        mysqli_stmt_bind_param($stmt, "i", $days);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);
    return $row['total_labs'] ?? 0;
}



// function fetchTestStatus() fetches the test status for franchise panel
function fetchTestStatus($status, $days = null)
{
    global $db_conn;
    $franchise_id = $_SESSION['id'];

    $query = "SELECT COUNT(*) AS count FROM test_requests WHERE franchise_id = ? AND status = ?";
    if ($days) {
        $query .= " AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
    }

    $stmt = mysqli_prepare($db_conn, $query);

    if ($days) {
        mysqli_stmt_bind_param($stmt, "isi", $franchise_id, $status, $days);
    } else {
        mysqli_stmt_bind_param($stmt, "is", $franchise_id, $status);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $row = mysqli_fetch_assoc($result);
    return $row['count'] ?? 0;
}

// function fetchUploadedReportsFranchise() used to fetch uploaded reports
function fetchUploadedReportsFranchise()
{
    global $db_conn;
    $franchise_name = $_SESSION['agency_name'];
    $franchise_name = mysqli_real_escape_string($db_conn, $franchise_name);

    $fetchUploadedReportsFranchiseQuery = "SELECT * FROM `reports` WHERE franchise_name = '$franchise_name' ORDER BY created_at DESC";
    $query = query($fetchUploadedReportsFranchiseQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $patient_name = $row['patient_name'];
        $franchise_name = $row['franchise_name'];
        $test_date = date("d-m-Y", strtotime($row['test_date']));
        $report_file = $row['report_files'];

        echo "<tr>";
        echo "<td>$patient_name</td>";
        // echo "<td>$franchise_name</td>";
        echo "<td>$test_date</td>";
        echo "<td>
                <a href='reportDownloadFranchise?file=" . urlencode($report_file) . "' class='btn btn-outline-primary btn-sm'>
                    <i class='bi bi-download'></i>
                </a>
            </td>";
        echo "</tr>";
    }
}

// function updatePassword() used to update password
function updatePassword()
{
    global $db_conn;
    $franchise_id = $_SESSION['id'];

    if (isset($_POST['changePassword'])) {

        // CSRF protection
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF Token mismatch! Possible CSRF attack.");
        }

        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            echo "<script>alert('New passwords do not match!');</script>";
        } else {
            $selectOldPasswordQuery = "SELECT password FROM `franchises` WHERE id = $franchise_id";
            $oldPasswordQuery = query($selectOldPasswordQuery);
            confirm($oldPasswordQuery);

            if ($row = mysqli_fetch_assoc($oldPasswordQuery)) {
                $stored_hashed_password = $row['password'];

                if (!password_verify($current_password, $stored_hashed_password)) {
                    echo "<script>alert('Current password is incorrect!');</script>";
                } else {
                    $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    $updatePasswordQuery = "UPDATE `franchises` SET password = '$new_hashed_password' WHERE id = $franchise_id";
                    $newPasswordQuery = query($updatePasswordQuery);
                    confirm($newPasswordQuery);

                    if ($newPasswordQuery) {
                        echo "<script>alert('Password changed successfully!'); window.close();</script>";
                    } else {
                        echo "<script>alert('Error updating password!');</script>";
                    }
                }
            }
        }
    }
}

// function updatePassword() used to update password
function updateProfilePassword()
{
    global $db_conn;
    $franchise_id = $_SESSION['id'];

    if (isset($_POST['changeProfilePassword'])) {

        // CSRF protection
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF Token mismatch! Possible CSRF attack.");
        }

        $current_profile_password = $_POST['current_profile_password'];
        $new_profile_password = $_POST['new_profile_password'];
        $confirm_profile_password = $_POST['confirm_profile_password'];

        if ($new_profile_password !== $confirm_profile_password) {
            echo "<script>alert('New profile passwords do not match!');</script>";
        } else {
            $selectOldProfilePasswordQuery = "SELECT profile_password FROM `franchises` WHERE id = $franchise_id AND usertype = 'Admin'";
            $oldProfilePasswordQuery = query($selectOldProfilePasswordQuery);
            confirm($oldProfilePasswordQuery);

            if ($row = mysqli_fetch_assoc($oldProfilePasswordQuery)) {
                $stored_hashed_profile_password = $row['profile_password'];

                if (!password_verify($current_profile_password, $stored_hashed_profile_password)) {
                    echo "<script>alert('Current profile password is incorrect!');</script>";
                } else {
                    $new_hashed_profile_password = password_hash($new_profile_password, PASSWORD_BCRYPT);

                    $updateProfilePasswordQuery = "UPDATE `franchises` SET profile_password = '$new_hashed_profile_password' WHERE id = $franchise_id AND usertype = 'Admin'";
                    $newProfilePasswordQuery = query($updateProfilePasswordQuery);
                    confirm($newProfilePasswordQuery);

                    if ($newProfilePasswordQuery) {
                        echo "<script>alert('Profile Password changed successfully!'); window.close();</script>";
                    } else {
                        echo "<script>alert('Error updating profile password!');</script>";
                    }
                }
            }
        }
    }
}

// function joinMembership() used to create new memberships
function joinMembership()
{

    global $db_conn;
    $franchise_id = $_SESSION['id'];
    $franchise_name = $_SESSION['agency_name'];

    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);
    $franchise_name = mysqli_real_escape_string($db_conn, $franchise_name);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['joinMembership'])) {

        $name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $upi_reference = $_POST['upi_reference'];

        $joinMembershipQuery = "INSERT INTO `membership` (franchise_id, franchise_name, name, email, phone, address, ";
        $joinMembershipQuery .= " upi_reference, created_at) VALUES ('$franchise_id', '$franchise_name', '$name', '$email', ";
        $joinMembershipQuery .= " '$phone', '$address', '$upi_reference', NOW())";
        $query = query($joinMembershipQuery);
        confirm($query);

        // setMessage("New Membership created successfully!", "success");
        // redirect("viewMembership");

        if ($query) {
            include 'sendMembershipEmail.php';
            sendMembershipEmail($name, $email, $phone, $address, $upi_reference, $franchise_name);

            setMessage("New Membership created successfully!", "success");
            redirect("viewMembership");
        }
    }
}

// function viewMembership() used to view all memberships
function viewMembership()
{
    global $db_conn;
    $franchise_id = $_SESSION['id'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

    $viewMembershipQuery = "SELECT * FROM `membership` WHERE franchise_id = '$franchise_id'";
    $query = query($viewMembershipQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $sr_no = $row['id'];
        $full_name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
        $upi_reference = $row['upi_reference'];
        // date formatting
        $created_at = $row['created_at'];
        $originalDate = $created_at;
        $date = new DateTime($originalDate);
        $formattedDate = $date->format('jS F Y, h:i A');

        echo "<tr>";
        // echo "<td><input type='checkbox'></td>";
        echo "<td>$sr_no</td>";
        echo "<td>$full_name</td>";
        echo "<td>$email</td>";
        echo "<td>$phone</td>";
        echo "<td>$address</td>";
        echo "<td>$upi_reference</td>";
        echo "<td>$formattedDate</td>";
        echo "<td><a href='invoiceMembership?sr_no=$sr_no&membership=$full_name' target='_blank'>View Membership</a></td>";
        echo "</tr>";
    }
}

/********************************** END OF FRANCHISE MODULE FUNCTIONS ************************************/
// 
// 
// 
// 
/**************************************** ADMIN MODULE FUNCTIONS *****************************************/

// function viewAllLabs() is used to view all the labs
function viewAllLabs()
{
    $viewAllLabsQuery = "SELECT * FROM `labs`";
    $query = query($viewAllLabsQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $lab_id = $row['id'];
        $lab_name = $row['lab_name'];
        $lab_logo = $row['lab_logo'];

        echo "<tr>";
        echo "<td>{$lab_name}</td>";
        echo "<td><img src='../src/images/labsImages/$lab_logo' alt='Lab Logo' style='width: 50px; height: 50px;'></td>";
        echo "<td>
                <a class='edit-button' href='lab_update?update=$lab_id'>Edit</a>
                <a class='delete-button' href='#' onclick='confirmDelete($lab_id)'>Delete</a>
            </td>";
        echo "</tr>";
    }
}

// function addLabs() is used to add new labs
function addLabs()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addLabs'])) {

        global $db_conn;

        $lab_name = $_POST['labName'];
        $lab_name = mysqli_real_escape_string($db_conn, $_POST['labName']);

        $lab_logo = $_FILES['labLogo']['name'];
        $temp_image = $_FILES['labLogo']['tmp_name'];
        move_uploaded_file($temp_image, "src/images/labsImages/$lab_logo");

        $addLabsQuery = "INSERT INTO `labs` (lab_name, lab_logo, created_at) ";
        $addLabsQuery .= "VALUES ('$lab_name', '$lab_logo', NOW())";
        $query = query($addLabsQuery);
        confirm($query);

        // creation of database table for labs dynamically
        $createLabTestTable = "CREATE TABLE `tests_$lab_name` (
                            `id` int(10) NOT NULL,
                            `code` varchar(255) NOT NULL,
                            `test_name` varchar(255) NOT NULL,
                            `B2B` int(255) NOT NULL,
                            `B2C` int(255) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        $query = query($createLabTestTable);
        confirm($query);

        setMessage("New Lab added!", "success");
        redirect("manageLab");
    }

    // exit();
}

// function readLabData() is used to read particular lab details like name, logo.
function readLabData($lab_id)
{

    $readLabDataQuery = "SELECT * FROM `labs` WHERE id = '$lab_id'";
    $query = query($readLabDataQuery);
    confirm($query);

    if ($row = mysqli_fetch_assoc($query)) {

        return [
            'id' => $row['id'],
            'lab_name' => $row['lab_name'],
            'lab_logo' => $row['lab_logo'],
        ];
    } else {
        setMessage("No lab found!", "warning");
    }
}

// function updateTestPrice() is used to update test details like name, price etc.
function updateLabDetails($lab_id, $lab_name)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateLab'])) {

        $lab_name_updated = $_POST['labName_updated'];
        $lab_logo_updated = $_FILES['labLogo_updated']['name'];
        $lab_logo_updated_image = $_FILES['labLogo_updated']['tmp_name'];
        move_uploaded_file($lab_logo_updated_image, "src/images/labsImages/$lab_logo_updated");

        $updateLabDetailsQuery = "UPDATE `labs` SET lab_name = '{$lab_name_updated}', lab_logo = '{$lab_logo_updated}' ";
        $updateLabDetailsQuery .= "WHERE id = '{$lab_id}' ";
        $query = query($updateLabDetailsQuery);
        confirm($query);

        $safe_old_name = preg_replace("/[^a-zA-Z0-9_]/", "_", strtolower($lab_name));
        $safe_new_name = preg_replace("/[^a-zA-Z0-9_]/", "_", strtolower($lab_name_updated));

        $labTestTableNameChangeQuery = "RENAME TABLE `tests_$safe_old_name` TO `tests_" . $safe_new_name . "`";
        $nameQuery = query($labTestTableNameChangeQuery);
        confirm($nameQuery);

        setMessage("Lab details updated!", "success");
        redirect("manageLab");
    }
}

// function readAllTestPrice() is used to read all test details like name, price etc.
function readAllTestPrice($Lab_name)
{
    global $db_conn;

    if (!$Lab_name) {
        die("Error: Lab name is missing.");
    }

    $lab_name = strtolower($Lab_name);
    $readAllTestPriceQuery = "SELECT * FROM `tests_$lab_name`";

    $query = mysqli_query($db_conn, $readAllTestPriceQuery);
    if (!$query) {
        die("SQL Error: " . mysqli_error($db_conn));
    }

    while ($row = mysqli_fetch_array($query)) {

        $test_id = $row['id'];
        $code = $row['code'];
        $name = $row['test_name'];
        $B2B = $row['B2B'];
        $B2C = $row['B2C'];

        echo "<tr>";
        echo "<td>{$code}</td>";
        echo "<td>{$name}</td>";
        echo "<td>{$B2B}</td>";
        echo "<td>{$B2C}</td>";
        echo "<td>
            <div class='action-buttons'>
                <a class='edit-button' href='test_update?update=$test_id&lab_name=$Lab_name'>Edit</a>
               <a class='delete-button' href='#' onclick=\"confirmDelete($test_id, '" . htmlspecialchars($Lab_name, ENT_QUOTES) . "')\">Delete</a>
            </div>
               </td>";
        echo "</tr>";
    }
}

// function addTestPrice() is used to add test names, prices.
function addTestPrice()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTestPrice'])) {

        if (!isset($_POST['lab_name']) || empty($_POST['lab_name'])) {
            die("Error: Lab name is missing in form submission.");
        }

        $Lab_name = $_POST['lab_name'];
        $lab_name = strtolower($Lab_name);

        $code = $_POST['test_code'];
        $name = $_POST['test_name'];
        $B2B = $_POST['B2B'];
        $B2C = $_POST['B2C'];

        $addTestPrice = "INSERT INTO `tests_$lab_name` (code, test_name, B2B, B2C) ";
        $addTestPrice .= "VALUES ('$code', '$name', '$B2B', '$B2C')";
        $query = query($addTestPrice);
        confirm($query);

        setMessage("New Test added!", "success");
        redirect("test?lab_name=" . urlencode($Lab_name));
    }
}

// function readTestPrice() is used to read particular test details like name, price etc.
function readTestPrice($lab_name, $test_id)
{
    $lab_name = strtolower($lab_name);
    $readAllTestPriceQuery = "SELECT * FROM `tests_$lab_name` WHERE id = '$test_id'";
    $query = query($readAllTestPriceQuery);
    confirm($query);

    if ($row = mysqli_fetch_assoc($query)) {

        return [
            'id' => $row['id'],
            'code' => $row['code'],
            'test_name' => $row['test_name'],
            'B2B' => $row['B2B'],
            'B2C' => $row['B2C']
        ];
    } else {
        setMessage("No test found!", "warning");
    }
}

// function updateTestPrice() is used to update test details like name, price etc.
function updateTestPrice($Lab_name, $test_id)
{
    $lab_name = strtolower($Lab_name);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateTestPrice'])) {

        $code = $_POST['code_updated'];
        $name = $_POST['name_updated'];
        $B2B = $_POST['B2B_updated'];
        $B2C = $_POST['B2C_updated'];

        $updateTestDetailsQuery = "UPDATE `tests_$lab_name` SET code = '{$code}', test_name = '{$name}', B2B = '{$B2B}', B2C = '{$B2C}' ";
        $updateTestDetailsQuery .= "WHERE id = '{$test_id}'";
        $query = query($updateTestDetailsQuery);
        confirm($query);

        setMessage("Test details updated!", "success");
        redirect("test?lab_name=$Lab_name");
    }
}

// function addFranchise() is used to add a new franchise
function addFranchise()
{
    global $db_conn;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addFranchise'])) {

        $owner_name = mysqli_real_escape_string($db_conn, $_POST['owner_name']);
        $agency_name = mysqli_real_escape_string($db_conn, $_POST['agency_name']);
        $email = mysqli_real_escape_string($db_conn, $_POST['email']);
        $phone = mysqli_real_escape_string($db_conn, $_POST['phone']);
        $address = mysqli_real_escape_string($db_conn, $_POST['address']);
        $pin_code = mysqli_real_escape_string($db_conn, $_POST['pin_code']);
        $package = mysqli_real_escape_string($db_conn, $_POST['package']);
        $aadhaar_number = mysqli_real_escape_string($db_conn, $_POST['aadhaar_number']);
        $pan_number = mysqli_real_escape_string($db_conn, $_POST['pan_number']);

        // File Uploads
        $aadhaar_upload = $_FILES['aadhaar_upload']['name'];
        move_uploaded_file($_FILES['aadhaar_upload']['tmp_name'], "../src/images/franchiseDocuments/$aadhaar_upload");

        $pan_upload = $_FILES['pan_upload']['name'];
        move_uploaded_file($_FILES['pan_upload']['tmp_name'], "../src/images/franchiseDocuments/$pan_upload");

        $owner_image = $_FILES['owner_image']['name'];
        move_uploaded_file($_FILES['owner_image']['tmp_name'], "../src/images/profileImages/$owner_image");

        $owner_signature = $_FILES['owner_signature']['name'];
        move_uploaded_file($_FILES['owner_signature']['tmp_name'], "../src/images/franchiseDocuments/$owner_signature");

        // Check if the email already exists
        $emailCheckResult = mysqli_query($db_conn, "SELECT email FROM franchises WHERE email = '$email'");
        if (mysqli_num_rows($emailCheckResult) > 0) {
            setMessage("The email '$email' is already registered.", "warning");
            redirect("addfranchise");
            exit();
        }

        // Generate a unique, secure token
        $secure_token = bin2hex(random_bytes(16));

        // Generate Booking URL with Secure Token
        $booking_url = "https://localhost/newtemp/admin/clientTestBooking?franchise_name=" . urlencode($agency_name) . "&token=" . $secure_token;

        // Ensure QR Code Directory Exists
        $qr_directory = "../src/images/booking_qr";
        if (!is_dir($qr_directory)) {
            mkdir($qr_directory, 0777, true);
        }

        // Generate sanitized filename for QR code
        $qr_filename = preg_replace('/[^a-zA-Z0-9]/', '_', strtolower($agency_name)) . "_qr.png";
        $qr_code_path = "$qr_directory/$qr_filename";

        // ✅ Generate and save QR Code using Endroid QR Code v6
        $qrCode = (new Builder(
            writer: new PngWriter(),
            data: $booking_url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low
        ))->build();

        file_put_contents($qr_code_path, $qrCode->getString()); // Save QR code

        // Insert into Database
        $hashed_password = password_hash("password", PASSWORD_DEFAULT);
        $addFranchiseQuery = "INSERT INTO `franchises` (owner_name, agency_name, email, phone, address, pin_code, package, 
            aadhaar_number, aadhaar_image, pan_number, pan_image, owner_image, owner_signature, password, booking_qr, secure_token, created_at) 
            VALUES ('$owner_name', '$agency_name', '$email', '$phone', '$address', '$pin_code', '$package', 
            '$aadhaar_number', '$aadhaar_upload', '$pan_number', '$pan_upload', 
            '$owner_image', '$owner_signature', '$hashed_password', '$qr_filename', '$secure_token', NOW())";

        if (mysqli_query($db_conn, $addFranchiseQuery)) {
            setMessage("Franchise Added Successfully!", "success");
            redirect("franchisemonitor");
        } else {
            setMessage("Error Adding Franchise: " . mysqli_error($db_conn), "danger");
        }
    }
}




// function updateProfile() is used to update the profile of the franchise
function updateProfile()
{
    global $db_conn;
    $id = $_SESSION['id'];
    $id = mysqli_real_escape_string($db_conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {

        $owner_name = $_POST['owner_name'];
        $agency_name = $_POST['agency_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $pin_code = $_POST['pin_code'];
        $aadhaar_number = $_POST['aadhaar_number'];
        $pan_number = $_POST['pan_number'];

        // Fetch existing owner image
        $fetchImageQuery = "SELECT owner_image FROM franchises WHERE id = '$id'";
        $result = query($fetchImageQuery);
        confirm($result);
        $row = fetch_array($result);
        $existing_image = $row['owner_image'];

        // Check if a new image is uploaded
        if (!empty($_FILES['owner_image']['name'])) {
            $owner_image = $_FILES['owner_image']['name'];
            $owner_image_temp = $_FILES['owner_image']['tmp_name'];

            if ($_SESSION['usertype'] === 'Admin') {
                move_uploaded_file($owner_image_temp, "../src/images/profileImages/$owner_image");
            } else {
                move_uploaded_file($owner_image_temp, "src/images/profileImages/$owner_image");
            }
        } else {
            $owner_image = $existing_image;
        }

        $updateProfileQuery = "UPDATE `franchises` SET owner_name = '{$owner_name}', agency_name = '{$agency_name}', email = '{$email}', ";
        $updateProfileQuery .= "phone = '$phone', address = '$address', pin_code = '$pin_code', aadhaar_number = '$aadhaar_number', ";
        $updateProfileQuery .= "pan_number = '$pan_number', owner_image = '$owner_image' ";
        $updateProfileQuery .= "WHERE id = '$id' ";
        $query = query($updateProfileQuery);
        confirm($query);

        // check for usertype and redirect accordingly
        if ($_SESSION['usertype'] === 'Admin') {
            setMessage("Profile details updated!", "success");
            redirect("profile_admin");
        } else {
            setMessage("Profile details updated!", "success");
            redirect("profile");
        }
    }
}

// function uploadReport() used to upload report
function uploadReport()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uploadReport'])) {

        $patient_name = $_POST['patient_name'];
        $franchise_name = $_POST['franchise_name'];
        $test_date = $_POST['test_date'];

        $upload_report = $_FILES['report_file']['name'];
        $upload_report_temp = $_FILES['report_file']['tmp_name'];
        move_uploaded_file($upload_report_temp, "reportFiles/$upload_report");

        $uploadReportQuery = "INSERT INTO `reports` (patient_name, franchise_name, test_date, report_files, created_at) ";
        $uploadReportQuery .= "VALUES ('$patient_name', '$franchise_name', '$test_date', '$upload_report', NOW())";
        $query = query($uploadReportQuery);
        confirm($query);

        setMessage("Report added successfully!", "success");
        redirect("reportUpload");
    }
}

// function fetchFranchiseName() is used to fetch franchise name
function fetchFranchiseName()
{
    $fetchFranchiseName = "SELECT agency_name FROM `franchises` WHERE usertype = 'Franchise'";
    $query = query($fetchFranchiseName);
    confirm($query);

    while ($row = mysqli_fetch_assoc($query)) {
        $agency_name = $row['agency_name'];
        echo "<option value='$agency_name'>{$agency_name}</option>";
    }
}

// function fetchUploadedReports() used to fetch uploaded report details.
function fetchUploadedReports()
{
    $fetchUploadedReports = "SELECT * FROM `reports` ORDER BY created_at DESC";
    $query = query($fetchUploadedReports);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $patient_name = $row['patient_name'];
        $test_date = date("d-m-Y", strtotime($row['test_date']));
        $report_file = $row['report_files'];

        echo "<tr>";
        echo "<td>{$patient_name}</td>";
        echo "<td>{$test_date}</td>";
        echo  "<td>
                    <a href='reportDownload?file=" . urlencode($report_file) . "' class='btn btn-outline-primary btn-sm'>
                    <i class='bi bi-download'></i>
                    </a>
                </td>";
        echo "</tr>";
    }
}

// function franchiseMonitor() fetches details like franchise name, total bookings, total revenue
function franchiseMonitor()
{
    $fetchFranchisesDetails = "SELECT f.id, f.agency_name, COUNT(t.franchise_id) AS total_bookings, COALESCE(SUM(t.order_amount), 0) ";
    $fetchFranchisesDetails .= "AS total_revenue FROM `franchises` f LEFT JOIN `test_requests` t ON f.id = t.franchise_id ";
    $fetchFranchisesDetails .= "WHERE f.usertype = 'Franchise' GROUP BY f.id, f.agency_name";
    $query = query($fetchFranchisesDetails);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $franchise_id = $row['id'];
        $franchise_name = $row['agency_name'];
        $total_bookings = $row['total_bookings'];
        $total_revenue = $row['total_revenue'];

        echo "<tr>";
        echo "<td>
                <a href=viewFranchiseProfile?franchise_id=$franchise_id>$franchise_name</a>
                </td>";
        echo "<td>$total_bookings</td>";
        echo "<td>₹ $total_revenue</td>";
        echo "<td>
                <a href=franchiseBooking?franchise_id=$franchise_id>View</a>
                </td>";
        echo "<td><a class='delete-button' href='#' onclick='confirmDelete($franchise_id)'>Delete</a></td>";
        echo "</tr>";
    }
}

// function franchiseBookings() is used to fetch the recent bookings for franchise
function franchiseBookings()
{
    if (isset($_GET['franchise_id'])) {

        $franchise_id = $_GET['franchise_id'];
        $franchiseBookingsQuery = "SELECT * FROM `test_requests` WHERE franchise_id = $franchise_id AND status = 'Pending' ORDER BY created_at DESC";
        $query = query($franchiseBookingsQuery);
        confirm($query);

        while ($row = mysqli_fetch_array($query)) {

            $sr_no = $row['id'];
            $franchise_name = $row['franchise_name'];
            $patient_name = $row['patient_name'];
            $patient_gender = $row['gender'];
            $patient_age = $row['age'];
            $selected_tests = $row['selected_test'];
            $lab_name = $row['lab_name'];
            $patient_dispatch_option = $row['dispatch_option'];
            $order_amount = $row['order_amount'];
            $b2b_amount = $row['b2b_amount'];

            // date formatting
            $created_at = $row['created_at'];
            $originalDate = $created_at;
            $date = new DateTime($originalDate);
            $formattedDate = $date->format('jS F Y, h:i A');

            $status = $row['status'];

            echo "<tr>";
            echo "<td><input type='checkbox'></td>";
            echo "<td>{$sr_no}</td>";
            echo "<td>{$franchise_name}</td>";
            echo "<td>{$patient_name}</td>";
            echo "<td>{$patient_gender}</td>";
            echo "<td>{$patient_age}</td>";
            echo "<td>{$lab_name}</td>";
            echo "<td>{$patient_dispatch_option}</td>";
            echo "<td>₹{$order_amount}</td>";
            echo "<td>₹{$b2b_amount}</td>";
            echo "<td>{$selected_tests}</td>";
            echo "<td>{$formattedDate}</td>";
            echo "<td>{$status}</td>";
            // echo "<td><a href='invoice?sr_no=$sr_no&patient_name=$patient_name' target='_blank'>View Invoice</a></td>";
            echo "</tr>";
        }

        // return null;
    }
}

// function recentBookings() fetches all bookings
function recentBookings()
{
    $recentBookingsQuery = "SELECT * FROM `test_requests` WHERE status = 'Pending' ORDER BY created_at DESC";
    $query = query($recentBookingsQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $sr_no = $row['id'];
        $franchise_name = $row['franchise_name'];
        $lab_name = $row['lab_name'];
        $patient_id = $row['patient_id'];
        $patient_name = $row['patient_name'];
        $order_amount = $row['order_amount'];
        $b2b_amount = $row['b2b_amount'];
        $test_names = $row['selected_test'];

        // date formatting
        $created_at = $row['created_at'];
        $originalDate = $created_at;
        $date = new DateTime($originalDate);
        $formattedDate = $date->format('jS F Y, h:i A');

        $status = $row['status'];
        $rejection_reason = $row['rejection_reason'];

        echo "<tr>";
        echo "<td><input type='checkbox' name='booking_ids[]' value='{$sr_no}' class='booking-checkbox'></td>";
        echo "<td>{$sr_no}</td>";
        echo "<td>{$franchise_name}</td>";
        echo "<td>{$lab_name}</td>";
        echo "<td>{$patient_id}</td>";
        echo "<td>{$patient_name}</td>";
        echo "<td>₹{$order_amount}</td>";
        echo "<td>₹{$b2b_amount}</td>";
        echo "<td>{$test_names}</td>";
        echo "<td>{$formattedDate}</td>";
        echo "<td>{$status}</td>";
        echo "<td>
                <div style='display: flex; gap: 5px;'>
                    <a class='btn btn-info' href='bookingInProcess?id=$sr_no' style='color: white;'>In-Process</a>
                    <a class='btn btn-success' href='bookingCompleted?id=$sr_no' style='color: white;'>Completed</a>
                    <a class='btn btn-danger' href='#' onclick='openRejectionModal($sr_no)' style='color: white;'>Rejected/Cancelled</a>
                </div>
            </td>";
        echo "<td>{$rejection_reason}</td>";
        echo "</tr>";
    }
}


// function updateProfile() is used to update the profile of the franchise
function updateProfileFranchise()
{
    global $db_conn;
    $id = $_GET['franchise_id'];
    $id = mysqli_real_escape_string($db_conn, $id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {

        $owner_name = $_POST['owner_name'];
        $agency_name = $_POST['agency_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $pin_code = $_POST['pin_code'];
        $aadhaar_number = $_POST['aadhaar_number'];
        $pan_number = $_POST['pan_number'];

        // Fetch existing owner image
        $fetchImageQuery = "SELECT owner_image FROM franchises WHERE id = '$id'";
        $result = query($fetchImageQuery);
        confirm($result);
        $row = fetch_array($result);
        $existing_image = $row['owner_image'];

        // Check if a new image is uploaded
        if (!empty($_FILES['owner_image']['name'])) {
            $owner_image = $_FILES['owner_image']['name'];
            $owner_image_temp = $_FILES['owner_image']['tmp_name'];

            if ($_SESSION['usertype'] === 'Admin') {
                move_uploaded_file($owner_image_temp, "../src/images/profileImages/$owner_image");
            } else {
                move_uploaded_file($owner_image_temp, "src/images/profileImages/$owner_image");
            }
        } else {
            $owner_image = $existing_image;
        }

        $updateProfileQuery = "UPDATE `franchises` SET owner_name = '{$owner_name}', agency_name = '{$agency_name}', email = '{$email}', ";
        $updateProfileQuery .= "phone = '$phone', address = '$address', pin_code = '$pin_code', aadhaar_number = '$aadhaar_number', ";
        $updateProfileQuery .= "pan_number = '$pan_number', owner_image = '$owner_image' ";
        $updateProfileQuery .= "WHERE id = '$id' ";
        $query = query($updateProfileQuery);
        confirm($query);

        setMessage("Profile details updated!", "success");
        redirect("viewFranchiseProfile?franchise_id=$id");
    }
}


// function updatePassword() used to update password
function updatePasswordAdmin($franchise_id)
{
    global $db_conn;

    if (isset($_POST['changePassword'])) {

        // CSRF protection
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF Token mismatch! Possible CSRF attack.");
        }

        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
            echo "<script>alert('New passwords do not match!');</script>";
        } else {
            $selectOldPasswordQuery = "SELECT password FROM `franchises` WHERE id = $franchise_id";
            $oldPasswordQuery = query($selectOldPasswordQuery);
            confirm($oldPasswordQuery);

            if ($row = mysqli_fetch_assoc($oldPasswordQuery)) {
                $stored_hashed_password = $row['password'];

                if (!password_verify($current_password, $stored_hashed_password)) {
                    echo "<script>alert('Current password is incorrect!');</script>";
                } else {
                    $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    $updatePasswordQuery = "UPDATE `franchises` SET password = '$new_hashed_password' WHERE id = $franchise_id";
                    $newPasswordQuery = query($updatePasswordQuery);
                    confirm($newPasswordQuery);

                    if ($newPasswordQuery) {
                        echo "<script>alert('Password changed successfully!'); window.close();</script>";
                    } else {
                        echo "<script>alert('Error updating password!');</script>";
                    }
                }
            }
        }
    }
}

// function viewMembershipAdmin() used to show all memberships created by franchises
function viewMembershipAdmin()
{
    $viewMembershipAdminQuery = "SELECT * FROM `membership`";
    $query = query($viewMembershipAdminQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $sr_no = $row['id'];
        $franchise_name = $row['franchise_name'];
        $full_name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
        $upi_reference = $row['upi_reference'];

        // date formatting
        $created_at = $row['created_at'];
        $originalDate = $created_at;
        $date = new DateTime($originalDate);
        $formattedDate = $date->format('jS F Y, h:i A');

        echo "<tr>";
        // echo "<td><input type='checkbox'></td>";
        echo "<td>$sr_no</td>";
        echo "<td>$franchise_name</td>";
        echo "<td>$full_name</td>";
        echo "<td>$email</td>";
        echo "<td>$phone</td>";
        echo "<td>$address</td>";
        echo "<td>$upi_reference</td>";
        echo "<td>$formattedDate</td>";
        echo "</tr>";
    }
}

/************************************ END OF ADMIN MODULE FUNCTIONS **************************************/
// 
// 
// 
// 
/************************************** ADMIN DASHBOARD FUNCTIONS ****************************************/

// function totalRevenue() used for calculating total revenue earned through B2C bookings for all franchises
function totalRevenueAdmin()
{

    $totalRevenueAdminQuery = "SELECT SUM(order_amount) AS total FROM test_requests WHERE status = 'Completed'";
    $query = query($totalRevenueAdminQuery);
    confirm($query);

    $row = mysqli_fetch_assoc($query);
    if ($row) {
        echo $row['total'] ?? 0;
    }
    return null;
}

// function totalLabs() is used to fetch the total number of labs
function totalLabs()
{
    $totalLabsQuery = "SELECT COUNT(*) AS total_labs FROM `labs`";
    $query = query($totalLabsQuery);
    confirm($query);

    $result = mysqli_fetch_assoc($query);
    if ($result) {
        echo $result['total_labs'];
    }

    return null;
}

// function totalFranchise() is used to fetch the total number of labs
function totalFranchise()
{
    $totalFranchiseQuery = "SELECT COUNT(*) AS total_franchise FROM `franchises` WHERE usertype = 'Franchise'";
    $query = query($totalFranchiseQuery);
    confirm($query);

    $result = mysqli_fetch_assoc($query);
    if ($result) {
        echo $result['total_franchise'];
    }

    return null;
}

// function totalBookings() is used to fetch the total number of bookings
function totalBookings()
{
    $totalBookingsQuery = "SELECT COUNT(*) AS total_bookings FROM `test_requests`";
    $query = query($totalBookingsQuery);
    confirm($query);

    $result = mysqli_fetch_assoc($query);
    if ($result) {
        echo $result['total_bookings'];
    }

    return null;
}


// function fetchTestStatusAdmin() fetches the test status for admin panel
function fetchTestStatusAdmin($status)
{
    $fetchTestStatusAdminQuery = "SELECT COUNT(*) AS count FROM test_requests WHERE status = '$status'";
    $query = query($fetchTestStatusAdminQuery);
    confirm($query);

    $row = mysqli_fetch_assoc($query);
    if ($row) {
        echo $row['count'];
    }

    return null;
}


// function fetchNumberOfMemberships() used to fetch number of total memberships
function fetchNumberOfMemberships()
{
    $fetchNumberOfMemberships = "SELECT COUNT(*) AS count FROM membership";
    $query = query($fetchNumberOfMemberships);
    confirm($query);

    $row = mysqli_fetch_assoc($query);
    if ($row) {
        echo $row['count'];
    }

    return null;
}


// function totalTests() used to fetch total number of tests from all the tables
function totalTests()
{

    global $db_conn;

    $labQuery = "SELECT lab_name FROM labs";
    $labResult = query($labQuery);
    confirm($labResult);

    if ($labResult->num_rows > 0) {
        $queryParts = [];

        while ($row = $labResult->fetch_assoc()) {

            $lab_name = strtolower($row['lab_name']);
            $labTable = "tests_" . $lab_name;

            $checkTableQuery = "SHOW TABLES LIKE '$labTable'";
            $tableExists = $db_conn->query($checkTableQuery);

            if ($tableExists->num_rows > 0) {
                $queryParts[] = "SELECT COUNT(*) AS total_tests FROM `$labTable`";
            }
        }

        if (!empty($queryParts)) {
            $finalQuery = "SELECT SUM(total_tests) AS total_tests_count FROM (" . implode(" UNION ALL ", $queryParts) . ") AS counts";
            $result = query($finalQuery);
            confirm($result);

            $row = $result->fetch_assoc();
            echo $row['total_tests_count'];
        } else {
            echo "No test tables found.";
        }
    } else {
        echo "No labs found.";
    }
}

// function fetchRechargeRequests() used to fetch recharge request details
function fetchRechargeRequests()
{

    $fetchRechargeRequestsQuery = "SELECT * FROM `recharge_requests` WHERE amount > 0 ORDER BY created_at DESC";
    $query = query($fetchRechargeRequestsQuery);
    confirm($query);

    while ($row = mysqli_fetch_array($query)) {

        $recharge_id = $row['id'];
        $franchise_name = $row['franchise_name'];
        $amount = $row['amount'];
        $upi_reference = $row['upi_reference'];
        $attachments = $row['attachments'];
        $status = $row['status'];

        echo "<tr>";
        echo "<td>{$franchise_name}</td>";
        echo "<td>₹{$amount}</td>";
        echo "<td>{$upi_reference}</td>";
        echo "<td><a href='../src/images/upiReferenceImages/{$attachments}' target='_blank'>View Proof</a></td>";
        echo "<td>{$status}</td>";
        echo "<td>
                <a class='btn btn-success' href='requestApproved?id=$recharge_id' style='color: white;'>Approve</a>
                <a class='btn btn-danger' href='requestRejected?id=$recharge_id' style='color: white;'>Reject</a>
            </td>";
        echo "</tr>";
    }
}

/*********************************** END OF ADMIN DASHBOARD FUNCTIONS ************************************/


function clientFormTestNames()
{
    $clientFormTestNamesQuery = "SELECT * FROM `tests_thyrocare`";
    $query = query($clientFormTestNamesQuery);
    confirm($query);

    while ($row = mysqli_fetch_assoc($query)) {
        echo "<tr>
                <td><input type='checkbox' data-test-code='{$row['code']}' data-test-name='{$row['test_name']}' data-test-price='{$row['B2C']}'></td>
                <td>" . htmlspecialchars($row['code']) . "</td>
                <td>" . htmlspecialchars($row['test_name']) . "</td>
                <td>₹" . htmlspecialchars($row['B2C']) . "</td>
              </tr>";
    }
}
