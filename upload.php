<?php include "includes/header.php"; ?>

<div class="main-container" style="padding-top: 5px;">
    <h2>Upload CSV File</h2>
    <form action="process_upload" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" required>
        <button type="submit" name="upload">Upload</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>