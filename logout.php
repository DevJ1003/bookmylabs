<?php

include "includes/functions.php";

session_start();
if (isset($_SESSION['id'])) {
    global $db_conn;
    $userId = $_SESSION['id'];

    $query = "UPDATE `franchises` SET remember_token = NULL WHERE id = '$userId'";
    query($query);
    confirm($query);
}

// Destroy session
session_unset();
session_destroy();

setcookie("remember_token", "", time() - 3600, "/"); // Expired cookie

redirect('index');
exit();
