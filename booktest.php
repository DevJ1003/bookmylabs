<?php
include "includes/db.php";
include "includes/header.php";
?>

<style>
    .imagecontainer {
        width: 100%;
        height: 150px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .imagecontainer img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease-in-out;
    }

    /* Hover Effect */
    .imagecontainer:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .imagecontainer:hover img {
        transform: scale(1.1);
    }

    /* Ensure all cards are of equal height */
    .mcon {
        width: 100%;
        min-height: 280px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        background: white;
    }

    /* Ensure text content is aligned properly */
    .bottom {
        text-align: center;
        width: 100%;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .bottom h4 {
        font-size: 16px;
        font-weight: bold;
        margin: 10px 0;
        min-height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bottom a {
        margin-top: 10px;
    }

    .mcon:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .mcon:hover .imagecontainer img {
        transform: scale(1.1);
    }
</style>

<link rel="stylesheet" href="src/styles/booktest.css" class="css">
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Select Lab</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Select Lab</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search">
                <img src="https://cdn-icons-png.flaticon.com/512/2652/2652234.png" alt="">
                <input type="text" id="searchLab" placeholder="Search labs...">
            </div>

            <!-- Original Labs List -->
            <div class="product-wrap" id="originalLabList">
                <div class="product-list">
                    <ul class="cards">
                        <?php
                        // Define labs per page
                        $labsPerPage = 8;
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        $startIndex = ($currentPage - 1) * $labsPerPage;

                        // Fetch total labs count
                        $totalResult = mysqli_query($db_conn, "SELECT COUNT(*) AS total FROM labs");
                        $totalRow = mysqli_fetch_assoc($totalResult);
                        $totalLabs = $totalRow['total'];
                        $totalPages = ceil($totalLabs / $labsPerPage);

                        // Fetch labs from database with pagination
                        $query = "SELECT id, lab_name, lab_logo FROM labs LIMIT $startIndex, $labsPerPage";
                        $result = mysqli_query($db_conn, $query);
                        ?>
                        <?php while ($lab = mysqli_fetch_assoc($result)): ?>
                            <li class="mcon">
                                <div>
                                    <div class="imagecontainer">
                                        <img src="src/images/labsImages/<?php echo $lab['lab_logo']; ?>" alt="" style="max-width: 100%; height: auto;">
                                    </div>
                                    <div class="bottom">
                                        <h4> <img src="https://cdn-icons-png.flaticon.com/512/620/620423.png" style="margin-right: 12px;" alt=""> <a href="#"><?php echo htmlspecialchars($lab['lab_name']); ?></a></h4>
                                        <a href="select_test?lab_name=<?php echo $lab['lab_name']; ?>" class="btn btn-outline-primary" style="margin-top: 10px;">Select</a>
                                    </div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>

            <!-- Search Results -->
            <div class="product-wrap">
                <div class="product-list">
                    <ul class="cards" id="labResults">
                        <!-- Search results will be displayed here -->
                    </ul>
                </div>
            </div>

            <!-- Pagination -->
            <div class="blog-pagination mb-30" id="pagination">
                <div class="btn-toolbar justify-content-center mb-15">
                    <div class="btn-group">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?>" class="btn btn-outline-primary prev"><i class="fa fa-angle-double-left"></i></a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>" class="btn <?php echo $i === $currentPage ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?>" class="btn btn-outline-primary next"><i class="fa fa-angle-double-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("searchLab").addEventListener("keyup", function() {
        let query = this.value.trim();
        let xhr = new XMLHttpRequest();

        // If search box is empty, reset to show original labs and pagination
        if (query === "") {
            document.getElementById("originalLabList").style.display = "block"; // Show the original lab list
            document.getElementById("labResults").innerHTML = ""; // Clear search results
            document.getElementById("pagination").style.display = "block"; // Show pagination
            return;
        }

        // If there is a search query, fetch results and hide original labs list
        document.getElementById("originalLabList").style.display = "none"; // Hide original labs list
        document.getElementById("pagination").style.display = "none"; // Hide pagination
        xhr.open("GET", "search_labs.php?query=" + encodeURIComponent(query), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("labResults").innerHTML = xhr.responseText; // Display search results
            }
        };
        xhr.send();
    });
</script>

<?php include "includes/footer.php"; ?>