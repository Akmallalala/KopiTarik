<?php

require_once __DIR__ . '/../include/security.php';

require __DIR__ . '/../include/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

verify_csrf();

// Restrict redirects to a known local page.
$redirect = (
    isset($_POST['redirect'])
    && is_string($_POST['redirect'])
    && $_POST['redirect'] === 'checkout.php'
) ? 'checkout.php' : '';

$loginErrorUrl = '../login.php?error=invalid'
    . ($redirect === 'checkout.php' ? '&redirect=checkout.php' : '');

$loginEmptyUrl = '../login.php?error=empty'
    . ($redirect === 'checkout.php' ? '&redirect=checkout.php' : '');

$email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
if (!is_string($_POST['password'] ?? null)) {
    header('Location: ' . $loginErrorUrl);
    exit;
}
$password = $_POST['password'] ?? '';


// ==========================================
// BASIC VALIDATION
// ==========================================

if (empty($email) || empty($password)) {
    header('Location: ' . $loginEmptyUrl);
    exit;
}


// ==========================================
// CHECK ADMIN
// ==========================================

$stmt = $pdo->prepare("
    SELECT admin_id, username, email, password
    FROM admin
    WHERE email = ?
    LIMIT 1
");

$stmt->execute([$email]);

$admin = $stmt->fetch();

if ($admin && password_verify($password, $admin['password'])) {

    unset($_SESSION['last_receipt']);
    session_regenerate_id(true);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    // Remove any customer session
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);

    // Create admin session
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_username'] = $admin['username'];

    header("Location: ../admin/dashboard.php");
    exit;
}


// ==========================================
// CHECK CUSTOMER
// ==========================================

$stmt = $pdo->prepare("
    SELECT customer_id, name, email, password
    FROM customer
    WHERE email = ?
    LIMIT 1
");

$stmt->execute([$email]);

$customer = $stmt->fetch();

if ($customer && password_verify($password, $customer['password'])) {

    unset($_SESSION['last_receipt']);
    session_regenerate_id(true);
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    // Remove any admin session
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);

    // Create customer session
    $_SESSION['customer_id'] = $customer['customer_id'];
    $_SESSION['customer_name'] = $customer['name'];

    header(
    'Location: ../'
    . ($redirect === 'checkout.php' ? 'checkout.php' : 'index.php')
);
    exit;
}


// ==========================================
// LOGIN FAILED
// ==========================================

header('Location: ' . $loginErrorUrl);
exit;