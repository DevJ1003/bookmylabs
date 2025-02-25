<?php include "includes/header.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="src/styles/recentBookings.css">

<div class="main-container">
    <h1>Recent Booking</h1>
    <div class="search">
        <input type="text" id="searchInput" placeholder="Search by patient name...">
        <button id="searchButton">Search</button>
    </div>
    <div class="middle">
        <input type="date" id="dateFilter">
        <select id="dispatchOptionFilter">
            <option value="">Dispatch Option</option>
            <option value="Pickup">Sample Drawn</option>
            <option value="Courier">Home Collection</option>
        </select>
        <select id="labNameFilter">
            <option value="">All Labs</option>
            <?php
            $labQuery = "SELECT lab_name FROM `labs`";
            $labResult = mysqli_query($db_conn, $labQuery);

            while ($labRow = mysqli_fetch_assoc($labResult)) {
                echo "<option value='{$labRow['lab_name']}'>{$labRow['lab_name']}</option>";
            }
            ?>
        </select>
        <select id="orderStatusFilter">
            <option value="">All Status</option>
            <option value="Pending">Pending</option>
            <option value="In-Process">In-Process</option>
            <option value="Completed">Completed</option>
            <option value="Rejected/Cancelled">Rejected/Cancelled</option>
        </select>
        <button id="clearFilterButton">Clear Filter</button>
        <button id="downloadExcel">Download Excel</button>
    </div>
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table table-striped table-bordered hover multiple-select-row data-table-export-recent-booking">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>SR NO</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Lab Name</th>
                        <th>Dispatch Type</th>
                        <th>Order Amount(B2C)</th>
                        <th>B2B Amount</th>
                        <th>Test Name</th>
                        <th>Booking Date</th>
                        <th>Status</th>
                        <th>Invoice</th>
                        <th>Rejection Reason (if any)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php recentBookingsFranchise(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");
        const orderStatusFilter = document.getElementById("orderStatusFilter");
        const dispatchOptionFilter = document.getElementById("dispatchOptionFilter");
        const labNameFilter = document.getElementById("labNameFilter");
        const dateFilter = document.getElementById("dateFilter");
        const clearFilterButton = document.getElementById("clearFilterButton"); // Clear Filter Button
        const resultsTable = document.querySelector(".data-table-export-recent-booking tbody");

        function fetchFilteredResults() {
            const query = searchInput.value.trim();
            const status = orderStatusFilter.value;
            const dispatchOption = dispatchOptionFilter.value;
            const labName = labNameFilter.value;
            const selectedDate = dateFilter.value;

            fetch(`search_recent_booking.php?query=${encodeURIComponent(query)}&status=${encodeURIComponent(status)}&dispatch_option=${encodeURIComponent(dispatchOption)}&lab_name=${encodeURIComponent(labName)}&date=${encodeURIComponent(selectedDate)}`)
                .then(response => response.text())
                .then(data => {
                    resultsTable.innerHTML = data;
                })
                .catch(error => console.error("Error fetching filtered results:", error));
        }

        searchButton.addEventListener("click", function() {
            fetchFilteredResults();
        });

        searchInput.addEventListener("keyup", function(event) {
            if (event.key === "Enter") {
                fetchFilteredResults();
            }
        });

        orderStatusFilter.addEventListener("change", function() {
            fetchFilteredResults();
        });

        dispatchOptionFilter.addEventListener("change", function() {
            fetchFilteredResults();
        });

        labNameFilter.addEventListener("change", function() {
            fetchFilteredResults();
        });

        dateFilter.addEventListener("change", function() {
            fetchFilteredResults();
        });

        // ✅ Clear Filter Function
        clearFilterButton.addEventListener("click", function() {
            searchInput.value = ""; // Clear search input
            orderStatusFilter.value = ""; // Reset status filter
            dispatchOptionFilter.value = ""; // Reset dispatch option
            labNameFilter.value = ""; // Reset lab filter
            dateFilter.value = ""; // Reset date filter

            fetchFilteredResults(); // Reload data without filters
        });
    });

    document.getElementById("downloadExcel").addEventListener("click", function() {
        const query = encodeURIComponent(document.getElementById("searchInput").value.trim());
        const status = encodeURIComponent(document.getElementById("orderStatusFilter").value);
        const dispatchOption = encodeURIComponent(document.getElementById("dispatchOptionFilter").value);
        const labName = encodeURIComponent(document.getElementById("labNameFilter").value);
        const selectedDate = encodeURIComponent(document.getElementById("dateFilter").value);

        // Redirect to export_to_excel.php with filters as query parameters
        window.location.href = `export_to_excel.php?query=${query}&status=${status}&dispatch_option=${dispatchOption}&lab_name=${labName}&date=${selectedDate}`;
    });
</script>
<?php include "includes/footer.php"; ?>