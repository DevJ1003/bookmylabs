<?php
include "includes/db.php";

$labName = isset($_GET['lab_name']) ? strtolower($_GET['lab_name']) : '';

$fetchLabTestNamesQuery = "SELECT id, test_name, B2B, B2C FROM `tests_$labName`";
$query = mysqli_query($db_conn, $fetchLabTestNamesQuery);

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_array($query)) { ?>
        <div class="card">
            <div class="insidecard">
                <h4 class="ctop">
                    <span class="test-name" title="<?php echo $row['test_name']; ?>"><?php echo $row['test_name']; ?></span>
                </h4>
                <p class="clinova">
                    <img src="https://cdn-icons-png.flaticon.com/512/883/883360.png" alt="">
                    <?php echo htmlspecialchars(ucwords($labName)); ?>
                </p>
                <p class="price">
                    <span class="linethrough" style="color: red;">&#8377;<?php echo $row['B2C']; ?></span>
                    <span class="reducedprice">&#8377; <?php echo $row['B2B']; ?></span>
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