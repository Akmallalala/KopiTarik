<?php

require __DIR__ . '/../include/admin_auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') verify_csrf();
require __DIR__ . '/../include/db_connect.php';
require_once __DIR__ . '/../include/product_validation.php';


// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: products.php");
    exit;
}


// ==========================================
// GET PRODUCT ID
// ==========================================

$productId = $_POST['product_id'] ?? null;

if (!$productId || !filter_var($productId, FILTER_VALIDATE_INT)) {
    header("Location: products.php?error=invalid");
    exit;
}


// ==========================================
// FIND PRODUCT FIRST
// ==========================================

$stmt = $pdo->prepare("
    SELECT menu_id, menu_name, image
    FROM menu_item
    WHERE menu_id = ?
");

$stmt->execute([$productId]);

$product = $stmt->fetch();


if (!$product) {
    header("Location: products.php?error=notfound");
    exit;
}


// ==========================================
// DELETE PRODUCT
// ==========================================

try {

    $stmt = $pdo->prepare("
        DELETE FROM menu_item
        WHERE menu_id = ?
    ");

    $stmt->execute([$productId]);


    // ======================================
    // DELETE PRODUCT IMAGE
    // ======================================

    if (!empty($product['image'])) {

        $imagePath =
            '../assets/images/products/'
            . $product['image'];

        if (file_exists($imagePath)) {
            delete_product_image($product['image']);
        }
    }


    header("Location: products.php?success=deleted");
    exit;


} catch (PDOException $e) {

    header("Location: products.php?error=delete");
    exit;
}