<?php include "includes/header.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="src/styles/index.css">

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="card-box pd-20 height-100-p mb-30">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="font-20 weight-500 mb-10 text-capitalize">
                            Welcome back, <div class="weight-600 font-30 text-blue"><?php echo $_SESSION['agency_name']; ?></div>
                        </h4>
                    </div>
                    <div class="col-md-4 text-right">
                        <select id="dateFilter" class="form-control">
                            <option value="all">All Data</option>
                            <option value="15">Last 15 Days</option>
                            <option value="30">Last 30 Days</option>
                        </select>
                    </div>
                </div>
                <?php displayMessage(); ?>
                <div class="row gy-4">
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card revenue">
                            <h5>Total Revenue</h5>
                            <p id="totalRevenue">₹<?php echo totalRevenue(); ?>/-</p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card booking">
                            <h5>Total Booking</h5>
                            <p id="totalBookings"><?php echo totalFranchiseBooking(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card net-partner">
                            <h5>Net Partner</h5>
                            <p id="netPartners"><?php echo fetchNumberOfLabs(); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card rejected">
                            <h5>Total Rejected</h5>
                            <p id="totalRejected"><?php echo fetchTestStatus("Rejected/Cancelled"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card completed">
                            <h5>Total Completed</h5>
                            <p id="totalCompleted"><?php echo fetchTestStatus("Completed"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card processing">
                            <h5>Total Processing</h5>
                            <p id="totalProcessing"><?php echo fetchTestStatus("In-Process"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card pending">
                            <h5>Total Pending</h5>
                            <p id="totalPending"><?php echo fetchTestStatus("Pending"); ?></p>
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 20px;">
                        <div class="dashboard-card resample">
                            <h5>Total Resample</h5>
                            <p id="totalResample"><?php echo fetchTestStatus("Rejected/Cancelled"); ?></p>
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


<script>
    document.getElementById("dateFilter").addEventListener("change", function() {
        let days = this.value;

        fetch("fetch_dashboard_data.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "days=" + days
            })
            .then(response => response.json())
            .then(data => {
                console.log("Response from server:", data);

                // Debugging: Check if elements exist before updating them
                if (document.getElementById("totalRevenue")) {
                    document.getElementById("totalRevenue").innerText = "₹" + data.totalRevenue + "/-";
                } else {
                    console.error("Element #totalRevenue not found!");
                }

                if (document.getElementById("totalBookings")) {
                    document.getElementById("totalBookings").innerText = data.totalBookings;
                } else {
                    console.error("Element #totalBookings not found!");
                }

                if (document.getElementById("netPartners")) {
                    document.getElementById("netPartners").innerText = data.netPartners;
                } else {
                    console.error("Element #netPartners not found!");
                }

                if (document.getElementById("totalRejected")) {
                    document.getElementById("totalRejected").innerText = data.totalRejected;
                } else {
                    console.error("Element #totalRejected not found!");
                }

                if (document.getElementById("totalCompleted")) {
                    document.getElementById("totalCompleted").innerText = data.totalCompleted;
                } else {
                    console.error("Element #totalCompleted not found!");
                }

                if (document.getElementById("totalProcessing")) {
                    document.getElementById("totalProcessing").innerText = data.totalProcessing;
                } else {
                    console.error("Element #totalProcessing not found!");
                }

                if (document.getElementById("totalPending")) {
                    document.getElementById("totalPending").innerText = data.totalPending;
                } else {
                    console.error("Element #totalPending not found!");
                }

                if (document.getElementById("totalResample")) {
                    document.getElementById("totalResample").innerText = data.totalResample;
                } else {
                    console.error("Element #totalResample not found!");
                }
            })
            .catch(error => console.error("AJAX Error:", error));
    });
</script>

<?php include "includes/footer.php"; ?>