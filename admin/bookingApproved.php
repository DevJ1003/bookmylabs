<?php

include "../includes/functions.php";

if (isset($_GET['id'])) {

    $request_id = $_GET['id'];
    $bookingApproveQuery = "UPDATE `test_requests` SET status = 'Approved' WHERE id = $request_id";
    $query = query($bookingApproveQuery);
    confirm($query);

    if ($query) {
        redirect("recentBookings");
    } else {
        echo "Error";
    }
}
