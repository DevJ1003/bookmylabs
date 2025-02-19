<?php

include "../includes/functions.php";

if (isset($_GET['id'])) {

    $request_id = $_GET['id'];
    $requestApproveQuery = "UPDATE `recharge_requests` SET status = 'Approved' WHERE id = $request_id";
    $query = query($requestApproveQuery);
    confirm($query);

    if ($query) {
        redirect("rechargeRequests");
    } else {
        echo "Error";
    }
}
