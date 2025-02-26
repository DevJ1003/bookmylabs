<?php include "includes/header_admin.php";

if (isset($_GET['franchise_id'])) {

    $id = $_GET['franchise_id'];
    $profile_update_fetch = "SELECT * FROM `franchises` WHERE id = '$id' ";
    $query = query($profile_update_fetch);
    confirm($query);

    while ($row = fetch_array($query)) {
        $owner_name = $row['owner_name'];
        $agency_name = $row['agency_name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
        $pin_code = $row['pin_code'];
        $aadhaar_number = $row['aadhaar_number'];
        $pan_number = $row['pan_number'];
        $owner_photo = $row['owner_image'];
    }
}

?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Franchise Profile</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Franchise Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-30">
                    <div class="pd-20 card-box">
                        <form action="" method="" id="" enctype="multipart/form-data">
                            <!-- First Row: Profile photo and owner details -->
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="profile-photo">
                                        <img id="preview" src="../src/images/<?php echo $owner_photo; ?>"
                                            alt="Profile Image" class="avatar-photo"
                                            style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                                    </div>
                                </div>

                                <!-- Right: Owner Name and Agency Name -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="owner_name">Franchise Owner Name</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" value="<?php echo $owner_name; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="agency_name">Franchise Agency Name</label>
                                        <input type="text" class="form-control" id="agency_name" name="agency_name" value="<?php echo $agency_name; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Second Row: Remaining Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email ID</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone No</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" disabled><?php echo $address; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pin_code">Pin Code</label>
                                        <input type="text" class="form-control" id="pin_code" name="pin_code" value="<?php echo $pin_code; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adhaar_no">Aadhaar No</label>
                                        <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number" value="<?php echo $aadhaar_number; ?>" readonly>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="adhaar_upload">Aadhaar Upload</label>
                                        <input type="file" class="form-control" id="adhaar_upload" name="adhaar_upload" accept="image/*,.pdf" required>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="pan_no">PAN No</label>
                                        <input type="text" class="form-control" id="pan_no" name="pan_number" value="<?php echo $pan_number; ?>" readonly>
                                    </div>
                                    <!-- <a href="javascript:void(0);" onclick="openChangePasswordWindow();" class="btn btn-danger">Change Password</a> -->
                                    <!-- <div class="form-group">
                                        <label for="pan_upload">PAN Upload</label>
                                        <input type="file" class="form-control" id="pan_upload" name="pan_upload" accept="image/*,.pdf" required>
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for="owner_photo">Owner Photo</label>
                                        <input type="file" class="form-control" id="owner_photo" name="owner_photo" accept="image/*" required>
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for="owner_signature">Owner Signature</label>
                                        <input type="file" class="form-control" id="owner_signature" name="owner_signature" accept="image/*" required>
                                    </div> -->
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-primary btn-sm w-25" onclick="window.location.href='updateFranchiseProfile?franchise_id=<?php echo $_GET['franchise_id']; ?>';">
                                    <i class="fa fa-user-add"></i> Update Profile
                                </button>
                                <button type="button" class="btn btn-primary btn-sm w-25" onclick="window.location.href='franchisemonitor';">
                                    <i class="fa fa-arrow-left me-1"></i> Go back to Franchise Monitor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function previewImage(event) {
        const preview = document.getElementById("preview");
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                preview.src = reader.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
<?php include "includes/footer_admin.php"; ?>