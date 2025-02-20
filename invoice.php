<?php include "includes/header.php";

if (isset($_GET['sr_no']) && isset($_GET['patient_name'])) {
	$sr_no = $_GET['sr_no'];
	$patient_name = $_GET['patient_name'];
} else {
	$sr_no = " ";
	$patient_name = " ";
}

// get invoice data =======================================================================================
global $db_conn;
$franchise_id = $_SESSION['id'];
$franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

$invoiceDataQuery = "SELECT * FROM `test_requests` WHERE id = '$sr_no' AND franchise_id = '$franchise_id' ";
$invoiceDataQuery .= "AND patient_name = '$patient_name' ";
$query = query($invoiceDataQuery);
confirm($query);

while ($row = mysqli_fetch_array($query)) {

	$patient_id = $row['patient_id'];
	$patient_age = $row['age'];
	$patient_gender = $row['gender'];
	$patient_mobile = $row['mobile'];
	$patient_address = $row['address'];

	$franchise_name = $row['franchise_name'];
	$lab_name = $row['lab_name'];
	$lab_name = strtoupper($lab_name);
	// $lab_code = $row['lab_code'];

	$test_names = $row['selected_test'];
	$test_names = explode(",", $test_names);

	// date formatting
	$created_at = $row['created_at'];
	$originalDate = $created_at;
	$date = new DateTime($originalDate);
	$formattedDate = $date->format('jS F Y, h:i A');
}
// ========================================================================================================

$test_details = [];
$unique_tests = [];

foreach ($test_names as $printed_test) {
	$printed_test = trim($printed_test);

	if (!in_array($printed_test, $unique_tests)) {

		$lab_name = strtolower($lab_name);
		$fetchTestDetails = "SELECT DISTINCT code, B2C FROM `tests_$lab_name` WHERE test_name = ?";
		$stmt = mysqli_prepare($db_conn, $fetchTestDetails);
		mysqli_stmt_bind_param($stmt, "s", $printed_test);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		while ($row = mysqli_fetch_array($result)) {
			$test_details[] = [
				'code' => $row['code'],
				'test_name' => $printed_test,
				'test_price' => $row['B2C']
			];
		}

		$unique_tests[] = $printed_test;
	}
}

// ========================================================================================================

?>
<div class="main-container">
	<div class="pd-ltr-20 xs-pd-20-10">
		<div class="min-height-200px">
			<div class="page-header">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="title">
							<h4>Invoice</h4>
						</div>
						<nav aria-label="breadcrumb" role="navigation">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Invoice</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
			<div class="invoice-wrap">
				<div class="invoice-box">
					<div class="invoice-header text-center">
						<div class="lab-logo">
							<img src="vendors/images/BOOK-MY-LAB.png" alt="Lab Logo" style="max-height: 80px;">
						</div>
						<div class="d-flex justify-content-between align-items-center mt-2">
							<div class="flex-grow-1 text-center">
								<h4 class="mb-0 weight-600">INVOICE</h4>
							</div>
						</div>
					</div>
					<div class="row pb-30">
						<div class="col-md-6">
							<p class="font-14 mb-5">Date: <strong class="weight-600"><?php echo $formattedDate; ?></strong></p>
							<p class="font-14 mb-5">Franchise Name: <strong class="weight-600"><?php echo $franchise_name; ?></strong></p>
							<!-- <p class="font-14 mb-5">Lab Code: <strong class="weight-600"><?php //echo $lab_code; 
																								?></strong></p> -->
							<p class="font-14 mb-5">Lab Name: <strong class="weight-600"><?php echo strtoupper($lab_name); ?></strong></p>
							<a href="download_invoice.php?sr_no=<?php echo $sr_no; ?>&patient_name=<?php echo urlencode($patient_name); ?>" class="btn btn-primary">Download Invoice</a>
						</div>
						<div class="col-md-6">
							<div class="text-right">
								<p class="font-14 mb-5">Patient ID: <strong class="weight-600"><?php echo $patient_id; ?></strong></p>
								<p class="font-14 mb-5">Name & Age: <strong class="weight-600"><?php echo $patient_name . ", " . $patient_age; ?></strong></p>
								<p class="font-14 mb-5">Gender: <strong class="weight-600"><?php echo $patient_gender; ?></strong></p>
								<p class="font-14 mb-5">Contact No.: <strong class="weight-600"><?php echo $patient_mobile; ?></strong></p>
								<p class="font-14 mb-5">Address: <strong class="weight-600"><?php echo $patient_address; ?></strong></p>
							</div>
						</div>
					</div>
					<div class="invoice-desc pb-30">
						<div class="invoice-desc-head clearfix">
							<div class="invoice-rate">Test Code</div>
							<div class="invoice-sub">Test Description</div>
							<!-- <div class="invoice-rate">Rate</div>
							<div class="invoice-hours">Hours</div> -->
							<div class="invoice-subtotal">Subtotal</div>
						</div>
						<div class="invoice-desc-body">
							<ul>
								<?php
								$total_price = 0;

								foreach ($test_details as $test) {
									$total_price += $test['test_price'];
								?>
									<li class="clearfix">
										<div class="invoice-rate"><?php echo $test['code']; ?></div>
										<div class="invoice-sub"><?php echo $test['test_name']; ?></div>
										<div class="invoice-subtotal"><span class="weight-600"><?php echo "₹" . $test['test_price']; ?>/-</span></div>
									</li>
								<?php } ?>
								<li class="clearfix">
									<div class="invoice-sub"><strong>TOTAL</strong></div>
									<div class="invoice-subtotal"><span class="weight-600 font-24 text-danger"><?php echo "₹" . $total_price; ?>/-</span></div>
								</li>
							</ul>
						</div>
					</div>
					<h4 class="text-center pb-20">Thank You!!</h4>
				</div>
			</div>
		</div>

		<?php include "includes/footer.php" ?>