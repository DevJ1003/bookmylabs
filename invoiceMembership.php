<?php include "includes/header.php";

if (isset($_GET['sr_no']) && isset($_GET['membership'])) {
    $sr_no = $_GET['sr_no'];
    $membership = $_GET['membership'];
} else {
    $sr_no = " ";
    $membership = " ";
}

// get invoice data =======================================================================================
global $db_conn;
$franchise_id = $_SESSION['id'];
$franchise_id = mysqli_real_escape_string($db_conn, $franchise_id);

$invoiceDataQuery = "SELECT * FROM `membership` WHERE id = '$sr_no' AND franchise_id = '$franchise_id' ";
$invoiceDataQuery .= "AND name = '$membership' ";
$query = query($invoiceDataQuery);
confirm($query);

while ($row = mysqli_fetch_array($query)) {

    $franchise_name = $row['franchise_name'];
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $address = $row['address'];
    $upi_reference = $row['upi_reference'];

    // date formatting
    $created_at = $row['created_at'];
    $originalDate = $created_at;
    $date = new DateTime($originalDate);
    $formattedDate = $date->format('jS F Y, h:i A');
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
                    <div class="row pb-30 justify-content-center text-center">
                        <div class="col-md-6">
                            <p class="font-14 mb-5">Franchise Name: <strong class="weight-600"><?php echo $franchise_name; ?></strong></p>
                            <p class="font-14 mb-5">Member Name: <strong class="weight-600"><?php echo $membership; ?></strong></p>
                        </div>
                    </div>

                    <div class="row pb-30 justify-content-center text-center">
                        <div class="col-md-6">
                            <p class="font-14 mb-5">Email: <strong class="weight-600"><?php echo $email; ?></strong></p>
                            <p class="font-14 mb-5">Phone: <strong class="weight-600"><?php echo $phone; ?></strong></p>
                            <p class="font-14 mb-5">Address: <strong class="weight-600"><?php echo $address; ?></strong></p>
                            <p class="font-14 mb-5">UPI Reference: <strong class="weight-600"><?php echo $upi_reference; ?></strong></p>
                            <p class="font-14 mb-5">Member Since: <strong class="weight-600"><?php echo $formattedDate; ?></strong></p>
                            <a href="download_invoice_membership.php?sr_no=<?php echo urlencode($sr_no); ?>&membership=<?php echo urlencode($membership); ?>" class="btn btn-primary">Download Invoice</a>
                        </div>
                    </div>

                    <!-- <div class="invoice-desc pb-30">
                        <div class="invoice-desc-head clearfix">
                            <div class="invoice-rate">Test Code</div>
                            <div class="invoice-sub">Test Description</div> -->
                    <!-- <div class="invoice-rate">Rate</div>
							<div class="invoice-hours">Hours</div> -->
                    <!-- <div class="invoice-subtotal">Subtotal</div>
                        </div>
                        <div class="invoice-desc-body">
                            <ul> -->
                    <?php
                    // $total_price = 0;

                    // foreach ($test_details as $test) {
                    //     $total_price += $test['test_price'];
                    ?>
                    <!-- <li class="clearfix">
                                        <div class="invoice-rate"><?php //echo $test['code']; 
                                                                    ?></div>
                                        <div class="invoice-sub"><?php //echo $test['test_name']; 
                                                                    ?></div>
                                        <div class="invoice-subtotal"><span class="weight-600"><?php //echo "₹" . $test['test_price']; 
                                                                                                ?>/-</span></div>
                                    </li> -->
                    <?php //} 
                    ?>
                    <!-- <li class="clearfix">
                                    <div class="invoice-sub"><strong>TOTAL</strong></div>
                                    <div class="invoice-subtotal"><span class="weight-600 font-24 text-danger"><?php //echo "₹" . $total_price; 
                                                                                                                ?>/-</span></div>
                                </li> -->
                    <!-- </ul>
                        </div>
                    </div> -->
                    <h4 class="text-center pb-20">Thank You!!</h4>
                </div>
            </div>
        </div>

        <?php include "includes/footer.php" ?>