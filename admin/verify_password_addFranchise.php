<?php
// session_start();
include "../includes/db.php"; // Include your database connection file
include "../includes/functions.php"; // Include your functions file

if (!isset($_SESSION['id'])) {
    redirect("index?error=session_expired");
    exit();
}

if (isset($_POST['profile_password'])) {

    $id = $_SESSION['id'];
    $entered_password = $_POST['profile_password'];

    // Fetch the stored password from the database
    $admin_id = $_SESSION['id']; // Assuming admin ID is stored in session
    $passwordQuery = "SELECT profile_password FROM `franchises` WHERE id = $id AND usertype = 'Admin'";
    $query = query($passwordQuery);
    confirm($query);

    if ($result = mysqli_fetch_assoc($query)) {
        $stored_password = $result['profile_password'];
        // If passwords are hashed in the database, use password_verify()
        if (password_verify($entered_password, $stored_password)) {
            $_SESSION['lab_access_granted_addFranchise'] = true;
            setMessage("Access granted successfully.", "success");
            redirect("addFranchise");
            exit();
        } else {
            $_SESSION['lab_access_granted_addFranchise'] = false;
            setMessage("Wrong profile password, not allowed for performing operations.", "warning");
            redirect("index?error=wrong_password");
            exit();
        }
    } else {
        setMessage("User not found.");
        redirect("index?error=user_not_found", "error");
        exit();
    }
} else {
    setMessage("Access denied.", "warning");
    redirect("index");
    exit();
}
