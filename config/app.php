<?php
require_once __DIR__ . '/../include/security.php';
require_once __DIR__ . '/../include/db_connect.php';
require_once __DIR__ . '/../include/shop.php';
function h($value) { return e($value); }
function money($value) { return 'BND ' . number_format((float)$value, 2); }
function csrf() { return $_SESSION['csrf_token']; }
function flash($message = null) {
    if ($message !== null) { $_SESSION['flash'] = $message; return; }
    $message = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']); return $message;
}
function go($url) { header('Location: ' . $url); exit; }
function logged() { return isset($_SESSION['customer_id']); }
function require_user() { if (!logged()) { flash('Please sign in first.'); go('login.php'); } }
function image_path($filename) {
    $filename = basename((string)$filename);
    foreach (['assets/images/products/','assets/img/'] as $dir) {
        if ($filename && is_file(__DIR__ . '/../' . $dir . $filename)) return $dir . rawurlencode($filename);
    }
    return 'assets/img/products-coffee-1.png';
}
function item_picture($item) { return image_path($item['image'] ?? ''); }
function cart_count() { return array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')); }
// Reject malformed form arrays before string/number conversions.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $value) if (!is_string($value)) { http_response_code(400); exit('Invalid form value.'); }
}
