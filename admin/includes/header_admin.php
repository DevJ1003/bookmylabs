<?php include "../includes/functions.php";

checkRememberedUser();

if (!isset($_SESSION['id']) || !isset($_SESSION['email']) || !isset($_SESSION['agency_name']) || !isset($_SESSION['usertype'])) {
    redirect("../login");
    exit();
}

// this query is used to fetch only user image from using id from session
$imageQuery = "SELECT owner_image FROM `franchises` WHERE id = {$_SESSION['id']}";
$query = query($imageQuery);
confirm($query);

$row = fetch_array($query);
$image = $row['owner_image'];

// access control - restricting admin to access franchise module
// Restrict franchise users from accessing admin module
if ($_SESSION['usertype'] !== 'Admin') {
    redirect("../index");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>BookMyLabs - Admin!</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../vendors/images/favicon-16x16.png">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../vendors/styles/style.css">
    <link rel="stylesheet" type="text/css" href="../src/plugins/jquery-steps/jquery.steps.css">
    <link rel="stylesheet" type="text/css" href="../src/styles/wallet.css">
    <link rel="stylesheet" type="text/css" href="../src/styles/pricing.css">
    <link rel="stylesheet" type="text/css" href="../src/styles/report.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
    <style>
        .left-side-bar {
            background-color: rgb(56, 91, 146);
        }
    </style>
</head>

<body>
    <!-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo"><img src="vendors/images/BOOK-MY-LAB.png" alt="" style="height:100px;"></div>
            <div class='loader-progress' id="progress_div">
                <div class='bar' id='bar1'></div>
            </div>
            <div class='percent' id='percent1'>0%</div>
            <div class="loading-text">
                Loading...
            </div>
        </div>
    </div> -->

    <div class="header">
        <div class="header-left">
            <div class="menu-icon dw dw-menu"></div>
            <div class="header-search">
                <!-- <h6>BOOK MY LABS</h6> -->
            </div>
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src="../src/images/profileImages/<?php echo $image; ?>" alt="">
                        </span>
                        <span class="user-name">admin</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="profile_admin"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="../logout"><i class="dw dw-logout"></i> Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="left-side-bar">
        <div class="brand-logo" style="margin-top: 30px;">
            <a href="index">
                <img src="../vendors/images/dark.png" alt="" class="dark-logo">
                <img src="../vendors/images/dark.png" alt="" class="light-logo">
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll" style="margin-top: 30px;">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li class="dropdown">
                        <a href="index" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-hou-1"><img src="https://img.icons8.com/?size=100&id=6g6b5Mh-1uJ7&format=png&color=FFFFFF"></span><span class="mtext">Home</span>
                        </a>
                    </li>
                    <li>
                        <a href="manageLab" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-hospitals"><img src="https://img.icons8.com/?size=100&id=55232&format=png&color=FFFFFF">
                            </span><span class="mtext">Labs</span>
                        </a>
                    </li>
                    <li>
                        <a href="testSelectLab" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-vials"><img src="https://img.icons8.com/?size=100&id=mvl6qRkeZvoH&format=png&color=FFFFFF"></span><span class="mtext">Tests</span>
                        </a>
                    </li>
                    <li>
                        <a href="addFranchise" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-use"><img src="https://img.icons8.com/?size=100&id=23374&format=png&color=FFFFFF"></span><span class="mtext">Add Franchise</span>
                        </a>
                    </li>
                    <li>
                        <a href="rechargeRequests" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-wall"><img src="https://img.icons8.com/?size=100&id=20296&format=png&color=FFFFFF"></span><span class="mtext">Recharge Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="franchisemonitor" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-analytic"><img src="https://img.icons8.com/?size=100&id=14103&format=png&color=FFFFFF"></span><span class="mtext">Franchise Monitor</span>
                        </a>
                    </li>
                    <li>
                        <a href="recentBookings" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-calenda"><img src="https://img.icons8.com/?size=100&id=22621&format=png&color=FFFFFF"></span><span class="mtext">Recent Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="viewMembershipAdmin" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-calenda"><img src="../src/images/group.png"></span><span class="mtext">View Membership</span>
                        </a>
                    </li>
                    <li>
                        <a href="reportUpload" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-uploa"><img src="https://img.icons8.com/?size=100&id=dM-cqlhsN3-n&format=png&color=FFFFFF"></span><span class="mtext">Upload Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>