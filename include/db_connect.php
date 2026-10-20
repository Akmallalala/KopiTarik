<?php

$host = getenv("KOPI_DB_HOST") ?: "localhost";
$dbname = getenv("KOPI_DB_NAME") ?: "coffee_shop";
$username = getenv("KOPI_DB_USER") ?: "root";
$password = getenv("KOPI_DB_PASSWORD") ?: "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database connection failed.");
}