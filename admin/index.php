<?php include "includes/header_admin.php"; ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="font-20 weight-500 mb-10 text-capitalize">
                            Welcome back, <div class="weight-600 font-30 text-blue">Admin<?php //echo $_SESSION['agency_name']; 
                                                                                            ?></div>
                        </h4>
                    </div>
                </div>
                <?php displayMessage(); ?>
                <div class="row gy-4">
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;">
                        <div class="dashboard-card revenue">
                            <h5>Total Revenue</h5>
                            <p id="totalRevenue">₹<?php totalRevenueAdmin(); ?>/-</p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='recentBookings'">
                        <div class="dashboard-card booking">
                            <h5>Total Booking</h5>
                            <p id="totalBookings"><?php totalBookings(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='manageLab'">
                        <div class="dashboard-card net-partner">
                            <h5>Net Partner</h5>
                            <p id="netPartners"><?php totalLabs(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='franchisemonitor'">
                        <div class="dashboard-card total-franchise">
                            <h5>Total Franchise</h5>
                            <p id="netPartners"><?php totalFranchise(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='recentBookings'">
                        <div class="dashboard-card rejected">
                            <h5>Total Rejected</h5>
                            <p id="totalRejected"><?php fetchTestStatusAdmin("Rejected/Cancelled"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='recentBookings'">
                        <div class="dashboard-card completed">
                            <h5>Total Completed</h5>
                            <p id="totalCompleted"><?php fetchTestStatusAdmin("Completed"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='recentBookings'">
                        <div class="dashboard-card processing">
                            <h5>Total In-Process</h5>
                            <p id="totalProcessing"><?php fetchTestStatusAdmin("In-Process"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='recentBookings'">
                        <div class="dashboard-card pending">
                            <h5>Total Pending</h5>
                            <p id="totalPending"><?php fetchTestStatusAdmin("Pending"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='recentBookings'">
                        <div class="dashboard-card resample">
                            <h5>Total Resample</h5>
                            <p id="totalResample"><?php fetchTestStatusAdmin("Rejected/Cancelled"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px; cursor: pointer;" onclick="window.location.href='testSelectLab'">
                        <div class="dashboard-card total-tests">
                            <h5>Total Tests</h5>
                            <p id="totalResample"><?php totalTests(); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .dashboard-card {



        transition: all 0.3s ease-in-out;
        min-height: 120px;
        /* Equal height for all cards */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Hover Effect */
    .dashboard-card:hover {
        transform: translateY(-5px);
        /* Lift effect */
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.2);
    }

    /* Making sure all cards have equal font size */
    .dashboard-card h5 {
        font-size: 18px;
        font-weight: bold;
    }

    .dashboard-card p {
        font-size: 16px;
        font-weight: bold;
        margin-top: 5px;
    }

    .dashboard-card {
        position: relative;
        padding: 20px;
        border-radius: 10px;
        color: #000;
        font-weight: bold;
        overflow: hidden;
        min-height: 120px;
    }

    .dashboard-card h5 {
        font-size: 16px;
    }

    .dashboard-card p {
        font-size: 24px;
        font-weight: bold;
    }

    .dashboard-card::after {
        content: "";
        position: absolute;
        right: -40px;
        bottom: 10px;
        width: 150px;
        /* Increased from 80px */
        height: 150px;
        /* Increased from 80px */
        background-size: contain;
        background-repeat: no-repeat;
        opacity: 0.2;
    }

    .revenue {
        background: #e0ffe0;
    }

    .revenue::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/536/536011.png');
    }

    .booking {
        background: #e0f0ff;
    }

    .booking::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/489/489848.png');
    }

    .healthfit {
        background: #e6e9ff;
    }

    .healthfit::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/1322/1322246.png');
    }

    .net-partner {
        background: #e0ffe0;
    }

    .net-partner::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/1322/1322246.png');
    }

    .total-franchise {
        background: #f0e6ff;
    }

    .total-franchise::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/1322/1322246.png');
    }

    .total-tests {
        background: #e0f0ff;
    }

    .total-tests::after {
        background-image: url('https://img.icons8.com/pulsar-line/48/beaker.png');
    }

    .rejected {
        background: #ffe0e0;
    }

    .rejected::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/3293/3293868.png');
    }

    .completed {
        background: #e0fffa;
    }

    .completed::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/58/58679.png');
    }

    .processing {
        background: #fff5e0;
    }

    .processing::after {
        background-image: url('https://img.icons8.com/ios/100/hourglass.png');
    }

    .pending {
        background: #fff8d6;
    }

    .pending::after {
        background-image: url('https://img.icons8.com/ios/100/clock.png');
    }

    .resample {
        background: #f0e6ff;
    }

    .resample::after {
        background-image: url('https://cdn-icons-png.flaticon.com/512/126/126502.png');
    }
</style>


<style>
    #dateFilter {
        max-width: 200px;
        display: inline-block;
        font-size: 14px;
    }
</style>

<?php include "includes/footer_admin.php" ?>