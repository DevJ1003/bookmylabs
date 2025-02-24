<?php include "includes/header_admin.php";
$Lab_name = isset($_GET['lab_name']) ? $_GET['lab_name'] : '';
?>

<div class="main-container">
    <div class="pricing-update">
        <div id="input-form" class="form">
            <form action="test_add" id="pricingForm" method="POST" class="form">
                <?php addTestPrice();
                ?>
                <input type="hidden" name="lab_name" value="<?php echo htmlspecialchars($Lab_name); ?>">

                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <label>Enter Test Code</label>
                <input type="text" id="code" name="test_code" required>
                <label>Enter Test Name</label>
                <input type="text" id="name" name="test_name" required>
                <label>Enter B2B Price</label>
                <input type="text" id="B2B" name="B2B" required>
                <label>Enter B2C Price</label>
                <input type="text" id="B2C" name="B2C" required>
                <div class="button-group">
                    <button id="save-button" type="submit" name="addTestPrice" style="width: 100px;">Save</button>
                    <button id="cancel-button" type="button" style="width: 100px;" onclick="cancelTestUpdate('<?php echo htmlspecialchars($Lab_name); ?>'); return false;">Cancel</button>

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