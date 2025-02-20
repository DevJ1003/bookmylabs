<?php
include "includes/functions.php";

$days = isset($_POST['days']) && $_POST['days'] !== "all" ? intval($_POST['days']) : null;

$data = [
    'totalRevenue' => totalRevenue($days),
    'totalBookings' => totalFranchiseBooking($days),
    'netPartners' => fetchNumberOfLabs($days),
    'totalRejected' => fetchTestStatus("Rejected/Cancelled", $days),
    'totalCompleted' => fetchTestStatus("Completed", $days),
    'totalProcessing' => fetchTestStatus("In-Process", $days),
    'totalPending' => fetchTestStatus("Pending", $days),
    'totalResample' => fetchTestStatus("Rejected/Cancelled", $days),
];

echo json_encode($data);
