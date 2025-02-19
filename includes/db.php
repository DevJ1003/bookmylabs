<?php

// Database configurations
// To be changed according to your database credentials
// Defined as per local developement environment

ob_start();
// session_start(); // disabled because we started session in csrf_token.php for token generation

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookmylab";

$db_conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$db_conn) {
    die("Connection failed!" . mysqli_connect_error());
} 
// else {
//     echo "Connected successfully!";
// }