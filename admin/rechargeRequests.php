<?php include "includes/header_admin.php"; ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Recharge Requests</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Recharge Requests</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Recharge Requests Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Franchise Name</th>
                        <th>Amount</th>
                        <th>UPI Reference No</th>
                        <th>Proof</th>
                        <th>Current Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php fetchRechargeRequests(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "includes/footer_admin.php"; ?>