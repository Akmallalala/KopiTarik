<?php
// Run from a terminal only; never expose an admin-creation web endpoint.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../include/db_connect.php';
if ($argc !== 3 || !filter_var($argv[2], FILTER_VALIDATE_EMAIL) || strlen($argv[1]) > 50 || trim($argv[1]) === '') {
    fwrite(STDERR, "Usage: php tools/create_admin.php username email@example.com\n"); exit(1);
}
fwrite(STDOUT, "New admin password (input is visible in this terminal): ");
$password = rtrim(fgets(STDIN), "\r\n");
if (strlen($password) < 12 || strlen($password) > 72) { fwrite(STDERR, "Use a password of 12 to 72 bytes.\n"); exit(1); }
try {
    $stmt = $pdo->prepare('INSERT INTO admin (username,email,password) VALUES (?,?,?)');
    $stmt->execute([trim($argv[1]),$argv[2],password_hash($password,PASSWORD_DEFAULT)]);
    fwrite(STDOUT, "Admin created. You can now log in.\n");
} catch (PDOException $e) { fwrite(STDERR, "Could not create admin. Check for an existing username or email.\n"); exit(1); }
