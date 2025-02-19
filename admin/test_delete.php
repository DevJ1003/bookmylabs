<?php

// This snippet is used to delete a test from the pricing table. The delete button is added in the Actions 
// column of the pricing table. When the delete button is clicked, the test is deleted from the database and
// the page is redirected to the pricing page.

include "../includes/functions.php";

// global $db_conn;

// $franchise_id = $_SESSION['id'];
// $franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

if (isset($_GET['delete'])) {

    $test_id = $_GET['delete'];
    $test_id = mysqli_real_escape_string($db_conn, $test_id);

    $deleteTestPriceQuery = "DELETE FROM `test_details` WHERE id = '$test_id'";
    $query = query($deleteTestPriceQuery);
    confirm($query);

    redirect("test");
    exit();
}
