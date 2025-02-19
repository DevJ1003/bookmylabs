<?php
include "includes/db.php";  // Include the database connection

// Get the search term and lab name from the query
$searchQuery = isset($_GET['query']) ? mysqli_real_escape_string($db_conn, $_GET['query']) : '';
$labName = isset($_GET['lab_name']) ? strtolower($_GET['lab_name']) : '';

// Modify the query to filter by the search query
$fetchLabTestNamesQuery = "SELECT id, test_name, B2B, B2C FROM `tests_$labName` WHERE test_name LIKE '%$searchQuery%'";

// Execute the query using mysqli_query directly
$query = mysqli_query($db_conn, $fetchLabTestNamesQuery);

// Check if the query was successful and if any results were found
if (mysqli_num_rows($query) > 0) {
    // Fetch and return filtered results
    while ($row = mysqli_fetch_array($query)) { ?>
        <div class="card">
            <div class="insidecard">
                <h4 class="ctop">
                    <span class="test-name" title="<?php echo $row['test_name']; ?>"><?php echo $row['test_name']; ?></span>
                </h4>
                <p class="clinova">
                    <img src="https://cdn-icons-png.flaticon.com/512/883/883360.png" alt="">
                    Selected Lab Name
                </p>
                <p class="price">
                    <span class="reducedprice">&#8377; <?php echo $row['B2C']; ?></span>
                </p>
                <label class="checkbox">
                    <div>
                        <input type="checkbox" name="selected_tests[]" value="<?php echo $row['id'] . '|' . $row['test_name']; ?>">
                    </div>
                </label>
            </div>
        </div>
<?php }
} else {
    echo '<p>No tests found.</p>';
}
?>