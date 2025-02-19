<?php include "includes/header_admin.php";

if (isset($_GET['update'])) {
    $lab_id = $_GET['update'];
    $lab_data = readLabData($lab_id);
    if ($lab_data) {
        $lab_name = $lab_data['lab_name'];
        $lab_logo = $lab_data['lab_logo'];
    }
} else {
    $lab_id = "";
    $lab_name = "";
    $lab_logo = "";
}

?>

<div class="main-container">
    <div class="pricing-update">
        <div id="input-form" class="form">
            <form id="labForm" name="" method="POST" enctype="multipart/form-data">
                <?php updateLabDetails($lab_id, $lab_name);
                ?>
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" id="labId" name="labId"><!-- Hidden lab ID -->
                <div class="form-group">
                    <label>Lab Name</label>
                    <input type="text" id="labName" name="labName_updated" class="form-control" value="<?php echo $lab_name; ?>" required>
                </div>
                <div class="form-group">
                    <label>Lab Logo</label>
                    <input type="file" id="labLogo" name="labLogo_updated" class="form-control" accept="image/*,.pdf" value="<?php echo $lab_logo; ?>" required>
                </div>
                <div class="button-group">
                    <button id="save-button" type="submit" name="updateLab" style="width: 100px;">Save</button>
                    <button id="cancel-button" style="width: 100px;" onclick="cancelLabUpdate()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function cancelLabUpdate() {
        window.location.href = "manageLab";
    }
</script>
<?php include "includes/footer_admin.php"; ?>