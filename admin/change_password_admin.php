<?php

include "../includes/db.php";
include "../includes/functions.php";

if (!isset($_SESSION['id'])) {
    die("Unauthorized access! Please log in.");
}

$id = (int) $_SESSION['id'];

// Check if the franchise_id in the URL matches the logged-in user's ID
if (!isset($_GET['id']) || (int)$_GET['id'] !== $id) {
    die("Unauthorized access!");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 380px;
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            padding: 25px;
        }

        .form-control {
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-primary {
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
            width: 100%;
            background-color: #007bff;
            border: none;
            transition: 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h4 class="title">Change Password</h4>
        <form action="" method="POST" id="update_password">
            <?php updatePassword(); ?>

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control" name="new_password" required minlength="6">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password" required minlength="6">
            </div>
            <div class="text-center">
                <input type="submit" name="changePassword" class="btn btn-primary" value="Change Password">
            </div>
        </form>
    </div>
</body>

</html>