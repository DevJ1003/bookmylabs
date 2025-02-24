<?php include "includes/header_admin.php";
displayMessage();
?>


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

            <!-- Search Bar -->
            <div class="mb-3">
                <input type="text" id="searchFranchise" class="form-control" placeholder="Search by franchise name..." onkeyup="filterTable()">
            </div>

            <!-- Recharge Requests Table -->
            <table class="table table-bordered" id="franchiseTable">
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

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function filterTable() {
        var input = document.getElementById("searchFranchise");
        var filter = input.value.toLowerCase();
        var table = document.getElementById("franchiseTable");
        var rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var franchiseNameCell = rows[i].getElementsByTagName("td")[0]; // First column

            if (franchiseNameCell) {
                var txtValue = franchiseNameCell.textContent || franchiseNameCell.innerText;
                if (txtValue.toLowerCase().includes(filter)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
</script>

<?php include "includes/footer_admin.php"; ?>