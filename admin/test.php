<?php include "includes/header_admin.php";

// Check if lab_name is set, otherwise show an error
if (!isset($_GET['lab_name']) || empty($_GET['lab_name'])) {
  die("Error: Lab name is missing.");
}
$Lab_name = $_GET['lab_name'];
?>

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