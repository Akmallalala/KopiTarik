<?php

date_default_timezone_set('Asia/Brunei');

$host = "localhost";
$dbname = "KopiTarikDatabase";
$username = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Read and write database timestamps consistently in UTC.
    $pdo->exec("SET time_zone = '+00:00'");

} catch (PDOException $exception) {
    error_log($exception->getMessage());
    exit("Database connection failed.");
}

/**
 * Convert a UTC database date into Brunei time for display.
 */
function local_datetime(
    ?string $value,
    string $format = 'd M Y, h:i A'
): string {
    if ($value === null || trim($value) === '') {
        return '—';
    }

    $date = new DateTimeImmutable(
        $value,
        new DateTimeZone('UTC')
    );

    return $date
        ->setTimezone(new DateTimeZone('Asia/Brunei'))
        ->format($format);
}