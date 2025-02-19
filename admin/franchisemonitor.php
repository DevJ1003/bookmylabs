<?php include "includes/header_admin.php"; ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Franchise Monitor</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Franchise Monitor</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Month Filter with English Calendar -->
            <!-- <div class="row mb-3">
                <div class="col-md-4">
                    <label for="monthPicker">Select Month:</label>
                    <input type="text" id="monthPicker" class="form-control" placeholder="Select Month" />
                </div>
            </div> -->

            <!-- Download Excel Button -->
            <!-- <div class="mb-3">
                <a href="download_excel.php" class="btn btn-primary">Download Excel</a>
            </div> -->

            <!-- Recharge Requests Table -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Franchise Name</th>
                        <th>Total Bookings</th>
                        <th>Total Revenue</th>
                        <th>View Bookings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php franchiseMonitor(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap Datepicker -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        $('#monthPicker').datepicker({
            format: "mm/yyyy",
            startView: "months",
            minViewMode: "months",
            autoclose: true,
            language: 'en' // Use English language for the calendar
        }).on('changeDate', function(e) {
            // Get the selected month and year
            var month = e.date.getMonth() + 1; // Month is 0-indexed
            var year = e.date.getFullYear();
            // Implement your filter logic here
            console.log("Selected month: " + month + ", Year: " + year);
        });
    });
</script>

<?php include "includes/footer_admin.php"; ?>