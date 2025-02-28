<?php
include "../includes/db.php";

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $query = mysqli_real_escape_string($db_conn, $query);

    $sql = "SELECT * FROM `membership` 
            WHERE name LIKE '%$query%' 
            OR email LIKE '%$query%' 
            OR phone LIKE '%$query%' 
            OR franchise_name LIKE '%$query%'";

    $result = mysqli_query($db_conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            // date formatting
            $created_at = $row['created_at'];
            $originalDate = $created_at;
            $date = new DateTime($originalDate);
            $formattedDate = $date->format('jS F Y, h:i A');

            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['franchise_name']}</td>
                <td>{$row['name']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['address']}</td>
                <td>{$row['upi_reference']}</td>
                <td>{$formattedDate}</td>
            </tr>";
            $row['id']++;
        }
    } else {
        echo "<tr><td colspan='8'>No results found</td></tr>";
    }
}
