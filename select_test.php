<?php
include "includes/header.php";

if (isset($_GET['lab_name'])) {
    $Lab_name = $_GET['lab_name'];
} else {
    $Lab_name = "";
}

$lab_name = strtolower($Lab_name);
$fetchLabTestNamesQuery = "SELECT id, test_name, B2B, B2C FROM `tests_$lab_name`";
$query = mysqli_query($db_conn, $fetchLabTestNamesQuery);
confirm($query);
?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="src/styles/select_test.css">
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="search">
                <img src="vendors/images/assets/search.png" alt="">
                <input type="text" id="searchInput" placeholder="Search Labs">
            </div>
            <section>
                <form action="testform.php" method="GET">

                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="scrollable-container">
                        <div class="row" id="labTestResults">
                            <?php while ($row = mysqli_fetch_array($query)) { ?>
                                <div class="card">
                                    <div class="insidecard">
                                        <h4 class="ctop">
                                            <span class="test-name" title="<?php echo $row['test_name']; ?>"><?php echo $row['test_name']; ?></span>
                                        </h4>
                                        <p class="clinova">
                                            <img src="https://cdn-icons-png.flaticon.com/512/883/883360.png" alt="">
                                            <?php echo $Lab_name; ?>
                                        </p>
                                        <p class="price">
                                            <span class="linethrough" style="color: red;">&#8377;<?php echo $row['B2C']; ?></span>
                                            <span class="reducedprice">&#8377;<?php echo $row['B2B']; ?></span>
                                        </p>
                                        <label class="checkbox">
                                            <div>
                                                <input type="checkbox" name="selected_tests[]" value="<?php echo $row['id'] . '|' . $row['test_name']; ?>">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <input type="hidden" name="lab_name" value="<?php echo htmlspecialchars($Lab_name, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="text-center mt-4">
                        <input type="submit" class="btn btn-primary btn-lg" value="Submit" style="width: 150px;">
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>

<style>
    .scrollable-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
</style>

<!-- JavaScript for search functionality -->
<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let searchQuery = this.value.trim();
        let labName = "<?php echo $Lab_name; ?>";
        let xhr = new XMLHttpRequest();

        if (searchQuery.length > 0) {
            xhr.open("GET", "search_tests.php?query=" + encodeURIComponent(searchQuery) + "&lab_name=" + labName, true);
        } else {
            xhr.open("GET", "fetch_all_tests.php?lab_name=" + labName, true);
        }

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.querySelector("#labTestResults").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });
</script>

<?php include "includes/footer.php"; ?>