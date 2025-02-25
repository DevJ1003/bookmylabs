<?php include "includes/header_admin.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="../src/styles/recentBookings.css">
<link rel="stylesheet" type="text/css" href="../src/styles/recentBookingsModal.css">

<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Recent Bookings</h4>
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
                    <select id="labNameFilter">
                        <option value="">All Labs</option>
                        <?php
                        $labQuery = "SELECT lab_name FROM `labs`";
                        $labResult = mysqli_query($db_conn, $labQuery);

                        while ($labRow = mysqli_fetch_assoc($labResult)) {
                            echo "<option value='{$labRow['lab_name']}'>{$labRow['lab_name']}</option>";
                        }
                        ?>
                    </select>
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
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>SR NO</th>
                                <th>Franchise Name</th>
                                <th>Lab Name</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Order Amount(B2C)</th>
                                <th>B2B Amount</th>
                                <th>Test Name</th>
                                <th>Booking Date & Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                                <th>Rejection Reason (if any)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php recentBookings(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-start mt-3">
                    <button type="button" class="btn btn-info mx-1" onclick="updateStatus('In-Process')">In-Process</button>
                    <button type="button" class="btn btn-success mx-1" onclick="updateStatus('Completed')">Completed</button>
                    <button type="button" class="btn btn-danger mx-1" onclick="updateStatus('Rejected/Cancelled')">Rejected/Cancelled</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectionModal" class="modal-overlay">
    <div class="modal-box">
        <h2>Reject Booking</h2>
        <p>Please enter the reason for rejecting this booking:</p>
        <input type="hidden" id="bookingId">
        <textarea id="rejectionReason" placeholder="Enter rejection reason..."></textarea>
        <div class="modal-actions">
            <button onclick="closeRejectionModal()" class="cancel-btn">Cancel</button>
            <button onclick="submitRejection()" class="reject-btn">Reject</button>
        </div>
    </div>
</div>


<!-- Bootstrap JS (for modal functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../src/scripts/recentBookings.js"></script>

<?php include "includes/footer_admin.php"; ?>