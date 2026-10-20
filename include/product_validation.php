<?php
function validate_product_input($name, $price, $stock, $availability) {
    $errors = [];
    if (strlen($name) > 100) $errors[] = 'Product name must be 100 bytes or less.';
    if (!is_scalar($price) || !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', (string)$price)) $errors[] = 'Price must have at most two decimal places and be below 100,000,000.';
    if (!is_scalar($stock) || filter_var($stock, FILTER_VALIDATE_INT) === false || $stock < 0 || $stock > 2147483647) $errors[] = 'Stock must be a whole number between 0 and 2147483647.';
    if (!in_array($availability, ['0','1',0,1], true)) $errors[] = 'Invalid availability.';
    return $errors;
}
function valid_uploaded_image($image) {
    if (!is_string($image['tmp_name'] ?? null) || !is_uploaded_file($image['tmp_name'])) return false;
    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($image['tmp_name']);
    $expected = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp'];
    return isset($expected[$ext]) && $expected[$ext] === $mime && @getimagesize($image['tmp_name']) !== false;
}
function delete_product_image($filename) {
    if (!is_string($filename) || $filename === '' || basename($filename) !== $filename) return;
    $base = realpath(__DIR__ . '/../assets/images/products');
    $file = realpath(__DIR__ . '/../assets/images/products/' . $filename);
    if ($base && $file && dirname($file) === $base && is_file($file)) unlink($file);
}
