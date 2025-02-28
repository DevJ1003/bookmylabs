<?php include "includes/header_admin.php";

if (isset($_SESSION['id'])) {

    $id = $_SESSION['id'];
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

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Profile</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-30">
                    <div class="pd-20 card-box">
                        <form action="" method="POST" id="update_profile" enctype="multipart/form-data">
                            <?php updateProfile(); ?>

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <!-- First Row: Profile photo and owner details -->
                            <div class="row">
                                <!-- Left: Profile Photo -->
                                <!-- <div class="col-md-4 text-center">
                                    <div class="profile-photo">
                                        <a href="modal" data-toggle="modal" data-target="#modal" class="edit-avatar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <img src="src/images/<?php //echo $owner_photo; 
                                                                ?>" alt="" class="avatar-photo">
                                    </div>
                                </div> -->
                                <div class="col-md-4 text-center">
                                    <div class="profile-photo">
                                        <!-- Hidden File Input for Image Upload -->
                                        <input type="file" id="owner_image" name="owner_image" accept="image/*" style="display: none;" onchange="previewImage(event)">

                                        <!-- Clickable Image with Upload Feature -->
                                        <label for="owner_image" style="cursor: pointer;">
                                            <img id="preview" src="../src/images/profileImages/<?php echo $owner_photo; ?>"
                                                alt="Profile Image" class="avatar-photo" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                                        </label>

                                        <!-- Edit Icon for Better UX -->
                                        <a href="#" onclick="document.getElementById('owner_image').click();" class="edit-avatar">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </div>
                                </div>
                                <!--  -->

                                <!-- Right: Owner Name and Agency Name -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="owner_name">Franchise Owner Name</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" value="<?php echo $owner_name; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="agency_name">Franchise Agency Name</label>
                                        <input type="text" class="form-control" id="agency_name" name="agency_name" value="<?php echo $agency_name; ?>" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Second Row: Remaining Details -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email ID</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone No</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea class="form-control" id="address" name="address" required><?php echo $address; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pin_code">Pin Code</label>
                                        <input type="text" class="form-control" id="pin_code" name="pin_code" value="<?php echo $pin_code; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="adhaar_no">Aadhaar No</label>
                                        <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number" value="<?php echo $aadhaar_number; ?>" required>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="adhaar_upload">Aadhaar Upload</label>
                                        <input type="file" class="form-control" id="adhaar_upload" name="adhaar_upload" accept="image/*,.pdf" required>
                                    </div> -->
                                    <div class="form-group">
                                        <label for="pan_no">PAN No</label>
                                        <input type="text" class="form-control" id="pan_no" name="pan_number" value="<?php echo $pan_number; ?>" required>
                                    </div>
                                    <a href="javascript:void(0);" onclick="openChangePasswordWindow();" class="btn btn-danger">Change Password</a>
                                    <a href="javascript:void(0);" onclick="openChangeProfilePasswordWindow();" class="btn btn-danger">Change Profile Password</a>
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
                            <!-- Submit Button -->
                            <div class="form-group text-center">
                                <input type="submit" name="updateProfile" class="btn btn-primary btn-sm w-25" value="Submit">
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

    function openChangePasswordWindow() {
        var width = 500;
        var height = 400;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;

        var Id = <?php echo json_encode($_SESSION['id']); ?>; // Get franchise_id from session
        var url = "change_password_admin.php?id=" + Id;

        window.open(url, "Change Password", "width=" + width + ", height=" + height + ", top=" + top + ", left=" + left + ", resizable=no, scrollbars=no");
    }

    function openChangeProfilePasswordWindow() {
        var width = 500;
        var height = 400;
        var left = (screen.width - width) / 2;
        var top = (screen.height - height) / 2;

        var Id = <?php echo json_encode($_SESSION['id']); ?>; // Get franchise_id from session
        var url = "change_profile_password_admin.php?id=" + Id;

        window.open(url, "Change Profile Password", "width=" + width + ", height=" + height + ", top=" + top + ", left=" + left + ", resizable=no, scrollbars=no");
    }
</script>
<?php include "includes/footer_admin.php"; ?>