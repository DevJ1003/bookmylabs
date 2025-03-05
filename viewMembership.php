<?php include "includes/header.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="src/styles/recentBookings.css">

<div class="main-container">
    <h1>Membership</h1>
    <div class="search">
        <input type="text" id="searchInput" placeholder="Search by name...">
        <button id="searchButton">Search</button>
        <button onclick="window.location.href='joinMembership'">Add Membership</button>
    </div>
    <?php displayMessage(); ?>
    <div class="pb-20">
        <div class="table-responsive">
            <table class="table table-striped table-bordered hover multiple-select-row data-table-export-recent-booking">
                <thead>
                    <tr>
                        <!-- <th>Select</th> -->
                        <th>SR NO</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>UPI Reference</th>
                        <th>Created at</th>
                    </tr>
                </thead>
                <tbody>
                    <?php viewMembership(); ?>
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

            fetch(`search_membership_franchise.php?query=${encodeURIComponent(query)}`)
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
<?php include "includes/footer.php"; ?>