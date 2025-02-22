<?php

include "../includes/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['rejection_reason'])) {
        $request_id = (int) $_POST['id']; // Convert to integer for security
        $rejection_reason = escape_string($_POST['rejection_reason']);

        // Debugging: Log received data
        error_log("Received ID: $request_id, Reason: $rejection_reason");

        $bookingRejectQuery = "UPDATE `test_requests` 
                               SET status = 'Rejected/Cancelled', rejection_reason = '$rejection_reason' 
                               WHERE id = $request_id";

        $query = query($bookingRejectQuery);
        confirm($query);

        if ($query) {
            echo "success";
        } else {
            echo "Database update failed!";
        }
    } else {
        echo "Missing parameters!";
    }
} else {
    echo "Invalid request!";
}
