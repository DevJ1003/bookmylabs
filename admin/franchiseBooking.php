<?php include "includes/header_admin.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="../src/styles/recentBookings.css">

<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Franchise Bookings</h4>
            </div>
            <div class="pd-20">
                <div class="search">
                    <input type="text" id="searchInput" placeholder="Search by patient name...">
                    <button id="searchButton">Search</button>
                </div>
                <div class="middle">
                    <input type="date" id="dateFilter">
                    <select id="dispatchOptionFilter">
                        <option value="">Dispatch Option</option>
                        <option value="Pickup">Sample Drawn</option>
                        <option value="Courier">Home Collection</option>
                    </select>
                    <!-- <select id="labNameFilter">
                        <option value="">All Labs</option>
                        <?php
                        // $labQuery = "SELECT lab_name FROM `labs`";
                        // $labResult = mysqli_query($db_conn, $labQuery);

                        // while ($labRow = mysqli_fetch_assoc($labResult)) {
                        //     echo "<option value='{$labRow['lab_name']}'>{$labRow['lab_name']}</option>";
                        // }
                        ?>
                    </select> -->
                    <select id="orderStatusFilter">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In-Process">In-Process</option>
                        <option value="Completed">Completed</option>
                        <option value="Rejected/Cancelled">Rejected/Cancelled</option>
                    </select>
                    <button id="clearFilterButton">Clear Filter</button>
                    <button id="downloadExcel">Download Excel</button>
                </div>
            </div>
            <div class="pb-20">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered hover multiple-select-row data-table-export-recent-booking">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>SR NO</th>
                                <th>Franchise</th>
                                <th>Patient Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Lab Name</th>
                                <th>Dispatch Type</th>
                                <th>Order Amount(B2C)</th>
                                <th>B2B Amount</th>
                                <th>Test Name</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php franchiseBookings(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../src/scripts/franchiseBooking.js"></script>
<?php include "includes/footer_admin.php"; ?>