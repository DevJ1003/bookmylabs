<?php include "includes/header_admin.php";

// Check if lab_name is set, otherwise show an error
if (!isset($_GET['lab_name']) || empty($_GET['lab_name'])) {
  die("Error: Lab name is missing.");
}
$Lab_name = $_GET['lab_name'];
?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="../src/styles/test.css">

<!-- ============================================ password verification modal ========================================= -->
<style>
  .modal {
    z-index: 1050;
    /* Bootstrap default for modals */
  }

  .modal-backdrop {
    z-index: 1040;
    /* Bootstrap default for modal backdrop */
  }

  .custom-close-btn {
    color: grey;
    font-size: 24px;
    font-weight: bold;
    background: transparent;
    border: none;
    opacity: 1;
    transition: background 0.3s ease, color 0.3s ease;
    padding: 5px 10px;
    border-radius: 5px;
  }

  .custom-close-btn:hover {
    background: red;
    color: white;
  }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script>
  function redirectToIndex() {
    window.location.href = "index";
  }
</script>
<!-- ================================================================================================================ -->

<?php
if (isset($_SESSION['lab_access_granted_test']) && $_SESSION['lab_access_granted_test'] === true) {
  // If access is granted, continue
} else {
  echo '<script>$(document).ready(function() { $("#passwordModal").modal("show"); });</script>';
}

if (isset($_GET['error']) && $_GET['error'] == 'wrong_password') {
  echo '<script>alert("Wrong password, not allowed for performing operations.");</script>';
}
?>
<!-- ================================================================================================================ -->

<!-- Password Verification Modal -->
<div id="passwordModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Enter Profile Password</h4>
        <button type="button" class="close custom-close-btn" onclick="redirectToIndex()">×</button>
      </div>
      <div class="modal-body">
        <form id="passwordForm" method="POST" action="verify_password_test">
          <div class="form-group">
            <label>Enter Profile Password:</label>
            <input type="password" name="profile_password" id="profile_password" class="form-control" required>
            <input type="hidden" name="lab_name" id="lab_name" class="form-control" value="<?php echo $Lab_name; ?>">
          </div>
          <button type="submit" class="btn btn-primary">Verify</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of Password Verification Modal -->
<div class="main-container">
  <div class="page-header">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="title">
          <h4>Manage Test</h4>
        </div>
        <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manage Tests</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <div class="pricing">




    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Search by Test Name...">
      <button id="searchButton" hidden>Search</button>
    </div>
    <?php displayMessage(); ?>


    <table id="pricing-table">
      <thead>
        <tr>
          <th>Test Code</th>
          <th>Test Name</th>
          <th>B2B</th>
          <th>B2C</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="pricing-tbody">
        <?php readAllTestPrice(htmlspecialchars($Lab_name)); ?>
      </tbody>
    </table>
    <div class="controls">
      <!-- <button id="add-button" onclick="confirmAdd()">Add</button> -->
      <a class="btn btn-success" href="test_add?lab_name=<?php echo $Lab_name; ?>">Add New Test</a>
    </div>
  </div>
</div>
</div>
<script src="../src/scripts/pricing.js"></script>

<script>
  function confirmDelete(TestId, LabName) {
    if (confirm("Are you sure you want to delete this test? This action cannot be undone.")) {
      window.location.href = "test_delete.php?delete=" + TestId + "&lab_name=" + encodeURIComponent(LabName);
    }
  }

  function cancelTestAdd(LabName) {
    window.location.href = "test.php?lab_name=" + encodeURIComponent(LabName);
  }
</script>
<?php include "includes/footer_admin.php"; ?>