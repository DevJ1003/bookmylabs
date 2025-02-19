<?php include "includes/header.php"; ?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="src/styles/wallet2.css">

<div class="main-container" style="padding-top: 5px;">
  <div class="container" style="height: 100px; width: 100%;">
    <div class="col-md-12 col-sm-12">
      <div class="title">
        <h4>
          <img src="src/images/wallet.jpg" class="wallet-icon" alt="Wallet Icon">
          Wallet
        </h4>
      </div>
      <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Wallet</li>
        </ol>
      </nav>
    </div>
  </div>
  <div class="balance" style="width: 50%;">
    <div class="left">
      <h3>Wallet Balance:</h3>
      <h1>₹<?php fetchWalletBalance(); ?>/-</h1>
      <h1>Remark:</h1>
      <button id="add-money-btn">Add Money</button>
    </div>
    <div class="right">
      <h2>Scan QR Code to Add Money:</h2>
      <img src="src/images/qr-code.jpg" class="qr-code" alt="QR Code">
    </div>
  </div>

  <!-- Popup Modal -->
  <div id="add-money-modal" class="modal hidden">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>Add Money</h2>
      <form id="add-money-form" action="wallet" method="POST" class="form" enctype="multipart/form-data">
        <?php rechargeRequests();
        displayMessage();
        ?>
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="upi_reference">UPI Reference Number</label>
        <input type="text" id="upi_reference" name="upi_reference" required>
        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" required>
        <label for="image">Proof of Payment (Image)</label>
        <input type="file" id="file" name="file" accept="image/*">
        <button type="submit" name="wallet_request">Submit</button>
      </form>
    </div>
  </div>
</div>

<script src="src/scripts/wallet.js"></script>
<?php include "includes/footer.php"; ?>