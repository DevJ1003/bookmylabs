<?php

include "../includes/functions.php";

if (isset($_GET['id'])) {

    $request_id = $_GET['id'];
    $requestRejectQuery = "UPDATE `recharge_requests` SET status = 'Rejected' WHERE id = $request_id";
    $query = query($requestRejectQuery);
    confirm($query);

    if ($query) {
        redirect("rechargeRequests");
    } else {
        echo "Error";
    }
}
