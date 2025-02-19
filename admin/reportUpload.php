<?php include "includes/header_admin.php"; ?>

<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
      <div class="page-header">
        <div class="row">
          <div class="col-md-12 col-sm-12">
            <div class="title">
              <h4>Upload Reports</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Upload Reports</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>

      <!-- Upload Report Section -->
      <div class="container mt-4" style="width: 100%;height: 40vh;">
        <h2 class="mb-4">Upload Report</h2>
        <form action="reportUpload" method="POST" enctype="multipart/form-data">
          <?php uploadReport();
          displayMessage();
          ?>

          <!-- CSRF Token -->
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

          <div class="row g-3 align-items-center">
            <div class="col-md-3">
              <label for="patientName" class="form-label">Patient Name</label>
              <input type="text" class="form-control" id="patientName" name="patient_name" required>
            </div>
            <div class="col-md-3">
              <label for="franchiseSelect" class="form-label">Select Franchise</label>
              <select class="form-control" id="franchiseSelect" name="franchise_name" required>
                <option value="">Select</option>
                <?php fetchFranchiseName(); ?>
              </select>
            </div>
            <div class="col-md-3">
              <label for="testDate" class="form-label">Test Date</label>
              <input type="date" class="form-control" id="testDate" name="test_date" required>
            </div>
            <div class="col-md-3">
              <label for="reportFile" class="form-label">Upload Report PDF</label>
              <input type="file" class="form-control" id="reportFile" name="report_file" accept="application/pdf" required>
            </div>
          </div>
          <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary" name="uploadReport">Upload Report</button>
          </div>
        </form>
      </div>

      <!-- Reports Table -->
      <div class="container mt-4" style="width: 100%;">
        <h2 class="mb-4"></h2>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>Patient Name</th>
                <th>Test Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php fetchUploadedReports(); ?>
            </tbody>
          </table>
        </div>
      </div>

      <?php include "includes/footer_admin.php"; ?>