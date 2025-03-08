<?php

// This snippet is used to delete a franchise from the franchises table. The delete button is added in the Actions 
// column of the franchises table. When the delete button is clicked, the franchise is deleted from the database and
// the page is redirected to the franchisemonitor page.

include "../includes/functions.php";

global $db_conn;

if (isset($_GET['delete'])) {

    $franchise_id = $_GET['delete'];
    $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

    $deleteLabsQuery = "DELETE FROM `franchises` WHERE id = '$franchise_id'";
    $query = query($deleteLabsQuery);
    confirm($query);

    redirect("franchisemonitor");
    exit();
}
