<?php
include "includes/db.php";

// Get the search term from the query parameter
$searchTerm = isset($_GET['query']) ? mysqli_real_escape_string($db_conn, $_GET['query']) : '';

// Query to search labs by name (you can modify the query if needed)
$query = "SELECT id, lab_name, lab_logo FROM labs WHERE lab_name LIKE '%$searchTerm%' LIMIT 8";
$result = mysqli_query($db_conn, $query);

// Check if any labs match the search
if (mysqli_num_rows($result) > 0) {
    // Display the labs
    while ($lab = mysqli_fetch_assoc($result)) {
        echo '<li class="mcon">
                <div class="" style="">
                    <div class="imagecontainer" style="">
                        <img src="src/images/labs_images/' . $lab['lab_logo'] . '" alt="" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="bottom" style="">
                        <h4> <img src="https://cdn-icons-png.flaticon.com/512/620/620423.png" style="margin-right: 12px;" alt=""> <a href="#">' . htmlspecialchars($lab['lab_name']) . '</a></h4>
                        <a href="select_test?lab_name=' . urlencode($lab['lab_name']) . '" class="btn btn-outline-primary" style="margin-top: 10px;">Select</a>
                    </div>
                </div>
            </li>';
    }
} else {
    echo '<li>No labs found</li>';
}
