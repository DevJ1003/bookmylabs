<?php include "includes/header.php";

$franchise_name = $_SESSION['agency_name'];

$qrQuery = "SELECT booking_qr FROM franchises WHERE `owner_name` = '$franchise_name'";
$result = query($qrQuery);
confirm($result);
$row = mysqli_fetch_assoc($result);

?>

<!-- Custom css file -->
<link rel="stylesheet" type="text/css" href="src/styles/wallet2.css">

<div class="main-container" style="padding-top: 5px;">
    <div class="container" style="height: 100px; width: 100%;">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>
                    <img src="src/images/example_qr.png" class="wallet-icon" alt="Wallet Icon">
                    My QR
                </h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">My QR</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="balance" style="width: 50%;">
        <div class="left">
            <h3>Download QR Code for booking</h3>
            <button id="download-qr-btn">
                <i class='bi bi-download'></i> Download
            </button>
        </div>
        <div class="right">
            <h2>QR Code:</h2>
            <img src="src/images/booking_qr/<?php echo $row['booking_qr']; ?>" class="qr-code" alt="QR Code">
        </div>
    </div>
</div>

<script>
    document.getElementById("download-qr-btn").addEventListener("click", function() {
        window.location.href = "download_qr.php";
    });
</script>
<?php include "includes/footer.php"; ?>