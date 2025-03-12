<?php include "includes/header_admin.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="../src/styles/recentBookings.css">

<div class="main-container">
    <h1>Membership</h1>
    <div class="search">
        <input type="text" id="searchInput" placeholder="Search by name...">
        <button id="searchButton">Search</button>
        <button id="downloadExcel">Download Excel</button>
    </div>
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table table-striped table-bordered hover multiple-select-row data-table-export-recent-booking">
                <thead>
                    <tr>
                        <!-- <th>Select</th> -->
                        <th>SR NO</th>
                        <th>Franchise Name</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>UPI Reference</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php viewMembershipAdmin(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");
        const resultsTable = document.querySelector(".data-table-export-recent-booking tbody");

        function fetchFilteredResults() {
            const query = searchInput.value.trim();

            fetch(`search_membership.php?query=${encodeURIComponent(query)}`)
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
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");
        const downloadExcel = document.getElementById("downloadExcel");
        const resultsTable = document.querySelector(".data-table-export-recent-booking tbody");

        function fetchFilteredResults() {
            const query = searchInput.value.trim();

            fetch(`search_membership.php?query=${encodeURIComponent(query)}`)
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

        // ✅ Download Excel Function
        downloadExcel.addEventListener("click", function() {
            const query = encodeURIComponent(searchInput.value.trim());
            window.location.href = `export_membership.php?query=${query}`;
        });
    });
</script>

<?php include "includes/footer_admin.php"; ?>