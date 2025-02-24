<?php

// This snippet is used to delete a test from the pricing table. The delete button is added in the Actions 
// column of the pricing table. When the delete button is clicked, the test is deleted from the database and
// the page is redirected to the pricing page.

include "../includes/functions.php";

global $db_conn;

if (isset($_GET['delete']) && isset($_GET['lab_name'])) {

    $test_id = $_GET['delete'];
    $test_id = mysqli_real_escape_string($db_conn, $test_id);

    $Lab_name = $_GET['lab_name'];
    $lab_name = strtolower($Lab_name);

    $deleteTestPriceQuery = "DELETE FROM `tests_$lab_name` WHERE id = '$test_id'";
    $query = query($deleteTestPriceQuery);
    confirm($query);

    setMessage("Test deleted successfully!");
    redirect("test?lab_name=$Lab_name");
    exit();
}
