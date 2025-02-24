<?php include "includes/header_admin.php";

if (isset($_GET['update']) || isset($_GET['lab_name'])) {
    $test_id = $_GET['update'];
    $Lab_name = $_GET['lab_name'];
    $test_data = readTestPrice($Lab_name, $test_id);
    if ($test_data) {
        $test_id = $test_data['id'];
        $code = $test_data['code'];
        $name = $test_data['test_name'];
        $B2B = $test_data['B2B'];
        $B2C = $test_data['B2C'];
    }
} else {
    $test_id = "";
    $name = $B2B = $B2C = "";
}

?>

<div class="main-container">
    <div class="pricing-update">
        <div id="input-form" class="form">
            <form action="" id="testUpdateForm" method="POST" class="form">
                <?php updateTestPrice($Lab_name, $test_id); ?>

                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label>Enter Test Code</label>
                <input type="text" id="code_updated" name="code_updated" value="<?php echo $code; ?>">
                <label>Enter Test Name</label>
                <input type="text" id="name_updated" name="name_updated" value="<?php echo $name; ?>">
                <label>Enter B2B Price</label>
                <input type="text" id="B2B_updated" name="B2B_updated" value="<?php echo $B2B; ?>">
                <label>Enter B2C Price</label>
                <input type="text" id="B2C_updated" name="B2C_updated" value="<?php echo $B2C; ?>">
                <div class="button-group">
                    <button id="save-button" type="submit" name="updateTestPrice" style="width: 100px;">Save</button>
                    <button id="cancel-button" style="width: 100px;" onclick="cancelTestUpdate('<?php echo htmlspecialchars($Lab_name); ?>'); return false;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../src/scripts/pricing.js"></script>
<script>
    function cancelTestUpdate(LabName) {
        window.location.href = "test.php?lab_name=" + encodeURIComponent(LabName);
    }
</script>
<?php include "includes/footer_admin.php"; ?>