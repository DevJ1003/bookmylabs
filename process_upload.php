<?php
include "includes/db.php"; // Include database connection

if (isset($_POST['upload'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {
        $columns = fgetcsv($handle); // Read and ignore header row

        $row = [];
        while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
            if (count($data) == count($columns)) {
                // Correct row, process normally
                $row = $data;
            } else {
                // Incomplete row, merge with previous data
                $row[count($row) - 1] .= "\n" . $data[0];
                array_shift($data);
                $row = array_merge($row, $data);
            }

            // Ensure we process only full rows
            if (count($row) == count($columns)) {
                // Escape values to prevent SQL injection
                $escaped_values = array_map(fn($val) => mysqli_real_escape_string($db_conn, $val), $row);

                // Insert into tests_agilus table
                $query = "INSERT INTO `tests_agilus diagnostics` (`" . implode("`, `", $columns) . "`) VALUES ('" . implode("', '", $escaped_values) . "')";
                mysqli_query($db_conn, $query);
                $row = []; // Reset for next row
            }
        }
        fclose($handle);
        echo "CSV uploaded successfully!";
    } else {
        echo "Error opening file.";
    }
}
