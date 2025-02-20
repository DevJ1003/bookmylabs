<?php include "includes/header_admin.php"; ?>

<div class="main-container">
    <div class="pd-ltr-20">
        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Recent Bookings</h4>
            </div>
            <div class="pb-20">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered hover multiple-select-row data-table-export">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>SR NO</th>
                                <th>Franchise Name</th>
                                <th>Lab Name</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Order Amount</th>
                                <th>Test Name</th>
                                <th>Booking Date & Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php recentBookings(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer_admin.php"; ?>