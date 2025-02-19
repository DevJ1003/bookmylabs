<?php include "includes/header_admin.php"; ?>

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
        <td><?php readAllTestPrice(); ?></td>
      </tbody>
    </table>
    <div class="controls">
      <button id="add-button">Add</button>
    </div>
    <div id="input-form" class="hidden">
      <form id="pricingForm" method="POST" action="test" class="form">
        <?php addTestPrice(); ?>

        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label>Enter Test Code</label>
        <input type="text" id="code" name="test_code">
        <label>Enter Test Name</label>
        <input type="text" id="name" name="test_name">
        <label>Enter B2B Price</label>
        <input type="text" id="B2B" name="B2B">
        <label>Enter B2C Price</label>
        <input type="text" id="B2B" name="B2C">
        <div class="button-group">
          <button id="save-button" type="submit" name="addTestPrice" style="width: 100px;">Save</button>
          <button id="cancel-button" style="width: 100px;" onclick="cancelTestAdd()">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="../src/scripts/pricing.js"></script>

<script>
  function confirmDelete(TestId) {
    if (confirm("Are you sure you want to delete this test? This action cannot be undone.")) {
      window.location.href = "test_delete.php?delete=" + TestId;
    }
  }

  function cancelTestAdd() {
    window.location.href = "test";
  }
</script>
<?php include "includes/footer_admin.php"; ?>