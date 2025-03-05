<?php
include "includes/db.php";
require 'vendor/autoload.php';
include "includes/functions.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

displayMessage();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$email = trim($_POST['email']);

	// Validate email
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "<script>alert('Invalid email format!');</script>";
	} else {
		// Check if email exists
		$query = "SELECT id FROM franchises WHERE email = ?";
		$stmt = mysqli_prepare($db_conn, $query);
		mysqli_stmt_bind_param($stmt, "s", $email);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		if ($row = mysqli_fetch_assoc($result)) {
			// Generate new token and expiry time
			$new_token = bin2hex(random_bytes(50));
			date_default_timezone_set("Asia/Kolkata"); // Set timezone for India
			$new_expiry = date("Y-m-d H:i:s", strtotime("+20 minutes"));

			// Update the database with the new token
			$updateQuery = "UPDATE franchises SET reset_token = ?, reset_token_expiry = ? WHERE id = ?";
			$updateStmt = mysqli_prepare($db_conn, $updateQuery);
			mysqli_stmt_bind_param($updateStmt, "ssi", $new_token, $new_expiry, $row['id']);
			mysqli_stmt_execute($updateStmt);

			// Reset password link
			$reset_link = "https://franchisee.bookmylabs.in/reset_password.php?token=" . $new_token;

			// Send email with PHPMailer
			$mail = new PHPMailer(true);
			try {
				// Server settings
				$mail->isSMTP();
				$mail->Host = ''; // SMTP Server
				$mail->SMTPAuth = true;
				$mail->Username = '';  // Use your Gmail
				$mail->Password = '';      // Use App Password (DO NOT use your real password)
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
				$mail->Port = 587;

				// Email details
				$mail->setFrom('', 'BookMyLabs Support');
				$mail->addAddress($email);
				$mail->isHTML(true);
				$mail->Subject = 'BookMyLabs Support - Password Reset Link Request';
				$mail->Body = "
                    <html>
                        <head>
                            <style>
                                .email-container {
                                    width: 100%;
                                    max-width: 600px;
                                    margin: 0 auto;
                                    padding: 20px;
                                    font-family: Arial, sans-serif;
                                    text-align: center;
                                    background-color: #f4f4f4;
                                    border-radius: 10px;
                                }
                                .email-text, .normal-text {
                                    font-size: 18px;
                                    color: #333;
                                    font-weight: normal;
                                    margin-bottom: 20px;
                                }
                                .btn {
                                    background-color: #17a2b8;
                                    color: white;
                                    padding: 14px 20px;
                                    text-decoration: none;
                                    display: inline-block;
                                    font-size: 18px;
                                    font-weight: bold;
                                    border-radius: 5px;
                                    transition: background-color 0.3s;
                                }
                                .btn:hover { background-color: #138496; }
                                .logo { max-width: 150px; margin-bottom: 20px; }
                            </style>
                        </head>
                        <body>
                            <div class='email-container'>
                                <img src='cid:logo' alt='Company Logo' class='logo'>
                                <p class='email-text'>Password Reset</p>
                                <p class='normal-text'>If you've lost your password or wish to reset it, use the link below to get started.</p>
                                <a href='$reset_link' target='_blank' class='btn' style='color: white;'>Reset Password</a>
                                <p class='normal-text'>This link is valid for 20 minutes.</p>
                                <p class='email-text'>Thank you, BookMyLabs Team.</p>
                            </div>
                        </body>
                    </html>
                ";

				$mail->addEmbeddedImage('vendors/images/BOOK-MY-LAB.jpg', 'logo', 'BOOK-MY-LAB.jpg');
				$mail->send();

				setMessage("Password reset link sent to your email!", "success");
				redirect("login");
			} catch (Exception $e) {
				setMessage("Mailer Error: " . $mail->ErrorInfo, "danger");
				redirect("forgot");
			}
		} else {
			echo "<script>alert('Email not found!');</script>";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Book My Labs</title>
	<link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
</head>

<body>
	<div class="login-header box-shadow">
		<div class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<img class="logo" src="vendors/images/BOOK-MY-LAB.png" alt="">
			</div>
		</div>
	</div>

	<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6">
					<img src="vendors/images/forgot-password.png" alt="">
				</div>
				<div class="col-md-6">
					<div class="login-box bg-white box-shadow border-radius-10">
						<div class="login-title">
							<h2 class="text-center text-primary">Forgot Password</h2>
						</div>
						<h6 class="mb-20">Enter your email address to reset your password</h6>
						<form method="POST" action="">

							<div class="input-group custom">
								<input type="email" name="email" class="form-control form-control-lg" placeholder="Enter your email" required>
								<div class="input-group-append custom">
									<span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
								</div>
							</div>
							<div class="row align-items-center">
								<div class="col-6">
									<button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
								</div>
								<div class="col-6">
									<a class="btn btn-outline-primary btn-lg btn-block" href="login">Login</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- JS -->
	<script src="vendors/js/core.js"></script>
</body>

</html>