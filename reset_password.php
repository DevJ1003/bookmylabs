<?php
include "includes/functions.php";

// Get token from URL
$token = isset($_GET['token']) ? $_GET['token'] : '';

// If no token, redirect to the forgot password page
if (empty($token)) {
    header("Location: forgot.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $new_password = htmlspecialchars($_POST['new_password']); // Sanitize user input
    $confirm_password = htmlspecialchars($_POST['confirm_password']); // Sanitize user input

    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Validate token
        $validateQuery = "SELECT id FROM franchises WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
        $tokenQuery = query($validateQuery);
        confirm($tokenQuery);

        if ($row = mysqli_fetch_assoc($tokenQuery)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update password and remove reset token
            $updateQuery = "UPDATE franchises SET password = '$hashed_password', reset_token = NULL, reset_token_expiry = NULL WHERE id = " . $row['id'];
            $passwordQuery = query($updateQuery);
            confirm($passwordQuery);

            if ($passwordQuery) {
                echo "<script>
                    alert('Password changed successfully!');
                    window.location.href = 'login.php';
                    setTimeout(() => { window.close(); }, 1000);
                </script>";
            } else {
                echo "<script>alert('Error updating password. Try again.');</script>";
            }
        } else {
            echo "<script>alert('Invalid or expired token.'); window.location.href='forgot.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-password-form {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .reset-password-form h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .reset-password-form label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
            font-weight: 600;
            color: #555;
        }

        /* General styling for input fields */
        .reset-password-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
            box-sizing: border-box;
            /* Ensures padding is considered in the width calculation */
        }

        .reset-password-form input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reset-password-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .reset-password-form a {
            display: inline-block;
            margin-top: 10px;
            font-size: 16px;
            color: #007bff;
            text-decoration: none;
        }

        .reset-password-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="reset-password-form">
        <h2>Reset Your Password</h2>
        <form action="" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <input type="submit" name="reset_password_form" value="Reset Password">
        </form>
        <a href="login">Back to Login</a>
    </div>
</body>

</html>