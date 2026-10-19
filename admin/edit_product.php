<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// GET PRODUCT ID
// ==========================================

$productId = $_GET['id'] ?? null;

if (!$productId || !is_numeric($productId)) {
    header("Location: products.php");
    exit;
}


// ==========================================
// GET PRODUCT
// ==========================================

$stmt = $pdo->prepare("
    SELECT *
    FROM menu_item
    WHERE menu_id = ?
");

$stmt->execute([$productId]);

$product = $stmt->fetch();

if (!$product) {
    header("Location: products.php");
    exit;
}


// ==========================================
// GET CATEGORIES
// ==========================================

$stmt = $pdo->query("
    SELECT category_id, category_name
    FROM category
    ORDER BY category_name ASC
");

$categories = $stmt->fetchAll();


// ==========================================
// DEFAULT VALUES
// ==========================================

$menuName = $product['menu_name'];
$categoryId = $product['category_id'];
$description = $product['description'];
$price = $product['price'];
$stockQuantity = $product['stock_quantity'];
$availability = $product['availability'];
$currentImage = $product['image'];

$errors = [];


// ==========================================
// FORM SUBMITTED
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $menuName = trim($_POST['menu_name'] ?? '');
    $categoryId = $_POST['category_id'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $stockQuantity = $_POST['stock_quantity'] ?? '';
    $availability = $_POST['availability'] ?? 1;


    // ======================================
    // VALIDATION
    // ======================================

    if ($menuName === '') {
        $errors[] = "Product name is required.";
    }

    if ($categoryId === '') {
        $errors[] = "Please select a category.";
    }

    if ($price === '' || !is_numeric($price) || $price < 0) {
        $errors[] = "Please enter a valid price.";
    }

    if (
        $stockQuantity === '' ||
        filter_var($stockQuantity, FILTER_VALIDATE_INT) === false ||
        $stockQuantity < 0
    ) {
        $errors[] = "Please enter a valid stock quantity.";
    }

    if (!in_array((int)$availability, [0, 1], true)) {
        $errors[] = "Invalid availability value.";
    }


    // ======================================
    // CHECK CATEGORY EXISTS
    // ======================================

    if ($categoryId !== '') {

        $stmt = $pdo->prepare("
            SELECT category_id
            FROM category
            WHERE category_id = ?
        ");

        $stmt->execute([$categoryId]);

        if (!$stmt->fetch()) {
            $errors[] = "Selected category does not exist.";
        }
    }


    // ======================================
    // IMAGE HANDLING
    // ======================================

    $newImageName = $currentImage;
    $newUploadPath = null;

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $image = $_FILES['image'];

        if ($image['error'] !== UPLOAD_ERR_OK) {

            $errors[] = "There was a problem uploading the image.";

        } elseif ($image['size'] > 2 * 1024 * 1024) {

            $errors[] = "Image must be 2MB or smaller.";

        } else {

            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            $extension = strtolower(
                pathinfo(
                    $image['name'],
                    PATHINFO_EXTENSION
                )
            );

            if (!in_array($extension, $allowedExtensions, true)) {

                $errors[] =
                    "Only JPG, JPEG, PNG and WEBP images are allowed.";

            } else {

                $newImageName =
                    bin2hex(random_bytes(8))
                    . '.'
                    . $extension;

                $newUploadPath =
                    '../assets/images/products/'
                    . $newImageName;
            }
        }
    }


    // ======================================
    // UPLOAD NEW IMAGE
    // ======================================

    if (empty($errors) && $newUploadPath !== null) {

        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $newUploadPath
        )) {

            $errors[] = "Failed to save the new image.";
        }
    }


    // ======================================
    // UPDATE PRODUCT
    // ======================================

    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                UPDATE menu_item
                SET
                    category_id = ?,
                    menu_name = ?,
                    description = ?,
                    price = ?,
                    stock_quantity = ?,
                    availability = ?,
                    image = ?
                WHERE menu_id = ?
            ");

            $stmt->execute([
                $categoryId,
                $menuName,
                $description,
                $price,
                $stockQuantity,
                $availability,
                $newImageName,
                $productId
            ]);


            // ==================================
            // DELETE OLD IMAGE IF REPLACED
            // ==================================

            if (
                $newUploadPath !== null &&
                !empty($currentImage)
            ) {

                $oldImagePath =
                    '../assets/images/products/'
                    . $currentImage;

                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }


            header(
                "Location: products.php?success=updated"
            );

            exit;


        } catch (PDOException $e) {

            if (
                $newUploadPath !== null &&
                file_exists($newUploadPath)
            ) {
                unlink($newUploadPath);
            }

            $errors[] = "Unable to update product.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Product | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>

<body class="admin-body">

<div class="admin-layout dashboard-layout admin-theme">

    <?php include 'include/sidebar.php'; ?>


    <main class="admin-main">

        <div class="admin-page-header">

            <div>

                <h1>Edit Product</h1>

                <p>
                    Update product information.
                </p>

            </div>

            <a
                href="products.php"
                class="btn-secondary"
            >
                ← Back to Products
            </a>

        </div>


        <?php if (!empty($errors)): ?>

            <div class="alert-error">

                <strong>
                    Please fix the following:
                </strong>

                <ul>

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <section class="admin-form-section">

            <form
                method="POST"
                enctype="multipart/form-data"
                class="admin-form"
            >


                <!-- PRODUCT NAME -->

                <div class="form-group">

                    <label for="menu_name">
                        Product Name *
                    </label>

                    <input
                        type="text"
                        id="menu_name"
                        name="menu_name"
                        value="<?= htmlspecialchars($menuName) ?>"
                        maxlength="100"
                        required
                    >

                </div>


                <!-- CATEGORY -->

                <div class="form-group">

                    <label for="category_id">
                        Category *
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>

                        <?php foreach ($categories as $category): ?>

                            <option
                                value="<?= $category['category_id'] ?>"
                                <?= $categoryId == $category['category_id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= htmlspecialchars(
                                    $category['category_name']
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                    ><?= htmlspecialchars($description ?? '') ?></textarea>

                </div>


                <!-- PRICE + STOCK -->

                <div class="form-row">

                    <div class="form-group">

                        <label for="price">
                            Price ($) *
                        </label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            value="<?= htmlspecialchars($price) ?>"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="stock_quantity">
                            Stock Quantity *
                        </label>

                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            value="<?= htmlspecialchars($stockQuantity) ?>"
                            min="0"
                            step="1"
                            required
                        >

                    </div>

                </div>


                <!-- AVAILABILITY -->

                <div class="form-group">

                    <label for="availability">
                        Availability *
                    </label>

                    <select
                        id="availability"
                        name="availability"
                        required
                    >

                        <option
                            value="1"
                            <?= $availability == 1
                                ? 'selected'
                                : '' ?>
                        >
                            Available
                        </option>

                        <option
                            value="0"
                            <?= $availability == 0
                                ? 'selected'
                                : '' ?>
                        >
                            Unavailable
                        </option>

                    </select>

                </div>


                <!-- CURRENT IMAGE -->

                <?php if (!empty($currentImage)): ?>

                    <div class="form-group">

                        <label>
                            Current Image
                        </label>

                        <div class="current-product-image">

                            <img
                                src="../assets/images/products/<?= htmlspecialchars($currentImage) ?>"
                                alt="<?= htmlspecialchars($menuName) ?>"
                            >

                        </div>

                    </div>

                <?php endif; ?>


                <!-- NEW IMAGE -->

                <div class="form-group">

                    <label for="image">
                        Replace Product Image
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small>
                        Leave empty to keep the current image.
                        Maximum size: 2MB.
                    </small>

                </div>


                <!-- ACTIONS -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Save Changes
                    </button>

                    <a
                        href="products.php"
                        class="btn-secondary"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </section>

    </main>

</div>

</body>

</html>