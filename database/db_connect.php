<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "coffee_shop";

$conn = new mysqli($host, $username, $password, $database);

// check database connection
if ($conn->connect_error){
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");
?>