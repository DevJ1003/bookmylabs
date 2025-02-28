<?php include "includes/header.php"; ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Join Membership</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Join Membership</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-30">
                    <div class="pd-20 card-box">
                        <form action="joinMembership" id="membershipForm" method="POST" class="form">
                            <?php joinMembership(); ?>

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group text-center">
                                        <label><b>Scan QR Code to Pay Rs 999/-</b></label>
                                        <div>
                                            <img src="https://franchisee.bookmylabs.in/src/images/qr-code.jpg" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="upi_reference">UPI Reference ID</label>
                                        <input type="text" class="form-control" id="upi_reference" name="upi_reference" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" name="joinMembership" class="btn btn-primary btn-sm w-25" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>