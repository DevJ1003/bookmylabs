<?php

// This snippet is used to delete a lab from the labs table. The delete button is added in the Actions 
// column of the labs table. When the delete button is clicked, the lab is deleted from the database and
// the page is redirected to the manageLab page.

include "../includes/functions.php";

global $db_conn;

if (isset($_GET['delete'])) {

    $lab_id = $_GET['delete'];
    $lab_id = mysqli_real_escape_string($db_conn, $lab_id);

    $deleteLabsQuery = "DELETE FROM `labs` WHERE id = '$lab_id'";
    $query = query($deleteLabsQuery);
    confirm($query);

    redirect("manageLab");
    exit();
}
