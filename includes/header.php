<?php
include "functions.php";

checkRememberedUser();

if (!isset($_SESSION['id']) || !isset($_SESSION['email']) || !isset($_SESSION['agency_name'])) {
    redirect("login");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BookMyLabs - Book Any Test From Any Lab</title>
    <link rel="apple-touch-icon" sizes="180x180" href="vendors/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="vendors/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="vendors/images/favicon-16x16.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/datatables/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/jquery-steps/jquery.steps.css">
    <link rel="stylesheet" type="text/css" href="src/styles/wallet.css">
    <link rel="stylesheet" type="text/css" href="src/styles/pricing.css">
    <link rel="stylesheet" type="text/css" href="src/styles/report.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="src/styles/header.css">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>

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
            <a href="booktest" class="btn btn-primary book-test-btn">
                <i class="dw dw-calendar1"></i> Book Test
            </a>
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        <span class="user-icon">
                            <img src="vendors/images/photo1.jpg" alt="">
                        </span>
                        <span class="user-name"><?php echo $_SESSION['agency_name']; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                        <a class="dropdown-item" href="profile"><i class="dw dw-user1"></i> Profile</a>
                        <a class="dropdown-item" href="logout"><i class="dw dw-logout"></i> Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="left-side-bar" style="background-color:white; ">
        <div class="brand-logo" style="margin-top: 31px;">
            <a href="index" style="height: 40%;">
                <img src="./vendors/images/BOOK-MY-LAB.png" alt="" class="dark-logo">
                <img src="./vendors/images/BOOK-MY-LAB.png" alt="" class="light-logo">
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
                            <span class="micon dw dw-house-1" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Dashboard</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="booktest" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-edit2" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Book Test</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="recentBooking" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-list3" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Recent Bookings</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="wallet" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-wallet" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Wallet</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="report" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-bar-chart" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Reports</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="accountstatement" class="dropdown-toggle no-arrow">
                            <span class="micon dw dw-file" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Account Statement</b></span>
                        </a>
                    </li>
                    <li>
                        <a href="logout" class="dropdown-toggle no-arrow" style="position: fixed; bottom: 0;">
                            <span class="micon dw dw-logout" style="color:black;"></span>
                            <span class="mtext" style="color:black;"><b>Logout</b></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay">
        <?php updateWalletBalanceFranchise(); ?>
    </div>