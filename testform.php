<?php include "includes/header.php";

$selected_test_ids = [];
$selected_test_names = [];

if (isset($_GET['selected_tests']) && is_array($_GET['selected_tests'])) {
    foreach ($_GET['selected_tests'] as $test) {
        list($id, $name) = explode("|", $test);
        $selected_test_ids[] = intval($id);
        $selected_test_names[] = $name;
    }
} else {
    $selected_test_ids = [];
    $selected_test_names = ["No tests selected"];
}

if (isset($_GET['lab_name'])) {
    $lab_name = $_GET['lab_name'];
} else {
    $lab_name = "Unknown Lab";
}

$lab_name = strtolower($lab_name);
$total_amount = selectedTestsAmount($lab_name, $selected_test_ids);


?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Test Request Form</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">TRF</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="pd-20 card-box mb-30">
                <div class="wizard-content">
                    <form class="form" action="testform" method="POST" enctype="multipart/form-data">
                        <?php TestRequestForm(); ?>

                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <h5>Patient Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Age:</label>
                                    <input type="number" name="age" class="form-control" min="0" required>
                                </div>
                                <div class="form-group">
                                    <label>Gender:</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Mobile Number:</label>
                                    <input type="text" name="mobile" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Address:</label>
                                    <input type="text" name="address" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="selectedTests">Selected Tests:</label>
                                    <input type="hidden" name="selectedTests" id="selectedTests" class="form-control" value="<?php echo implode(',', $selected_test_ids); ?>" required>
                                    <input type="hidden" name="selectedTestNames" id="selectedTestNames" class="form-control" value="<?php echo implode(',', $selected_test_names); ?>" required>
                                    <!-- Display the selected test(s) -->
                                    <p><?php echo implode(", ", $selected_test_names); ?></p>
                                </div>
                                <div class="form-group">
                                    <label for="orderAmount">Total Amount:</label>
                                    <input type="hidden" name="orderAmount" id="orderAmount" class="form-control"
                                        value="<?php echo $total_amount; ?>" required>
                                    <p>₹<?php echo $total_amount; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sample Dispatch Option:</label>
                                    <select name="dispatchOption" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="pickup">Sample Drawn</option>
                                        <option value="courier">Home Collection</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Sample Drawn Date:</label>
                                    <input type="date" name="drawnDate" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Sample Drawn Time (6 AM - 6 PM):</label>
                                    <input type="time" name="drawnTime" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Fasting Status:</label>
                                    <select name="fastingStatus" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Reference Doctor (Optional):</label>
                                    <input type="text" name="referenceDoctor" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="attachment">Attachment (Optional):</label>
                                    <input id="file" type="file" name="file" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="lab_name" value="<?php echo htmlspecialchars($lab_name); ?>">
                        </div>
                        <div class="button-group">
                            <input type="submit" name="test_request" class="btn btn-primary" value="Submit" style="width: 100px;">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="src/scripts/submit_test.js"></script>
<?php include "includes/footer.php"; ?>