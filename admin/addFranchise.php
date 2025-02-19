<?php include "includes/header_admin.php"; ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Add Franchise</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Franchise</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-30">
                    <div class="pd-20 card-box">
                        <form action="addfranchise" method="POST" enctype="multipart/form-data">
                            <?php addFranchise();
                            displayMessage();
                            ?>

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="owner_name">Franchise Owner Name</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="agency_name">Franchise Agency Name</label>
                                        <input type="text" class="form-control" id="agency_name" name="agency_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email ID</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone No</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pin_code">Pin Code</label>
                                        <input type="text" class="form-control" id="pin_code" name="pin_code" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package"><b>Select Package</b></label>
                                        <select class="form-control" id="package" name="package" required style="background-color: #fff5e6; color: #333;">
                                            <option value="" disabled selected>Select a package</option>
                                            <option value="Standard">
                                                <i class="fas fa-check-circle"></i> Standard - €10/month
                                            </option>
                                            <option value="Advanced">
                                                <i class="fas fa-star"></i> Advanced - €15/month
                                            </option>
                                            <option value="Enterprise">
                                                <i class="fas fa-building"></i> Enterprise - €25/month
                                            </option>
                                            <option value="Premium">
                                                <i class="fas fa-crown"></i> Premium - €40/month
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="aadhaar_number">Aadhaar No.</label>
                                        <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="adhaar_upload">Aadhaar Upload</label>
                                        <input type="file" class="form-control" id="aadhaar_upload" name="aadhaar_upload" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pan_no">PAN No</label>
                                        <input type="text" class="form-control" id="pan_number" name="pan_number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pan_upload">PAN Upload</label>
                                        <input type="file" class="form-control" id="pan_upload" name="pan_upload" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="owner_photo">Owner Photo</label>
                                        <input type="file" class="form-control" id="owner_image" name="owner_image" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="owner_signature">Owner Signature</label>
                                        <input type="file" class="form-control" id="owner_signature" name="owner_signature" accept="image/*,.pdf" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" name="addFranchise" class="btn btn-primary btn-sm w-25" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer_admin.php"; ?>