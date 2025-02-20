<?php
include "includes/header.php";

global $db_conn;
$franchise_id = (int) $_SESSION['id'];

$days = isset($_GET['days']) ? (int) $_GET['days'] : 15;

// Corrected query without using placeholders
$statementQuery = "SELECT COUNT(id) AS total_bookings, SUM(order_amount) AS b2c_revenue FROM `test_requests` ";
$statementQuery .= "WHERE franchise_id = $franchise_id AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL $days DAY)";
$query = query($statementQuery);
confirm($query);

if ($row = mysqli_fetch_array($query)) {
    $total_bookings = $row['total_bookings'] ?? 0;
    $b2c_revenue = $row['b2c_revenue'] ?? 0;
}

// b2b deduction function
$fetchDeductionAmountQuery = "SELECT SUM(amount) AS deduction_amount FROM `recharge_requests` WHERE franchise_id = $franchise_id ";
$fetchDeductionAmountQuery .= " AND amount < 0 AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL $days DAY)";
$deductionQuery = query($fetchDeductionAmountQuery);
confirm($deductionQuery);

if ($row = mysqli_fetch_array($deductionQuery)) {
    $b2b_deduction_amount = $row['deduction_amount'] ?? 0;
    // $b2b_revenue += $deduction_amount;
}

?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Account Statement</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Account Statement</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Filter Dropdown for Selecting Days Range -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="dayRangeSelect">Select Date Range:</label>
                    <select id="dayRangeSelect" class="form-control">
                        <option value="">-- Select Range --</option>
                        <option value="15" <?= ($days == 15) ? 'selected' : '' ?>>Last 15 Days</option>
                        <option value="30" <?= ($days == 30) ? 'selected' : '' ?>>Last 30 Days</option>
                        <option value="45" <?= ($days == 45) ? 'selected' : '' ?>>Last 45 Days</option>
                        <option value="60" <?= ($days == 60) ? 'selected' : '' ?>>Last 60 Days</option>
                    </select>
                </div>
            </div>

            <!-- Recharge Requests Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Total Revenue B2C</th>
                        <th>Total Bookings</th>
                        <th>B2B Deduction</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>₹<?php echo $b2c_revenue; ?>/-</td>
                        <td><?php echo $total_bookings; ?></td>
                        <td>-₹<?php echo number_format(abs($b2b_deduction_amount)); ?></td>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.getElementById('dayRangeSelect').addEventListener('change', function() {
        var selectedDays = this.value;
        if (selectedDays) {
            window.location.href = window.location.pathname + "?days=" + selectedDays;
        }
    });

    // Keep the selected value after page reload
    document.addEventListener("DOMContentLoaded", function() {
        var urlParams = new URLSearchParams(window.location.search);
        var selectedDays = urlParams.get("days");
        if (selectedDays) {
            document.getElementById("dayRangeSelect").value = selectedDays;
        }
    });
</script>

<?php include "includes/footer.php"; ?>