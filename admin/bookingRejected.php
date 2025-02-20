<?php

include "../includes/functions.php";

if (isset($_GET['id'])) {

    $request_id = $_GET['id'];
    $bookingRejectQuery = "UPDATE `test_requests` SET status = 'Rejected/Cancelled' WHERE id = $request_id";
    $query = query($bookingRejectQuery);
    confirm($query);

    if ($query) {
        redirect("recentBookings");
    } else {
        echo "Error";
    }
}
