<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


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

$menuName = '';
$categoryId = '';
$description = '';
$price = '';
$stockQuantity = '';
$availability = 1;

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
    // IMAGE UPLOAD
    // ======================================

    $imageName = null;

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

                $imageName =
                    bin2hex(random_bytes(8))
                    . '.'
                    . $extension;

                $uploadPath =
                    '../assets/images/products/'
                    . $imageName;
            }
        }
    }


    // ======================================
    // INSERT PRODUCT
    // ======================================

    if (empty($errors)) {

        // Upload image if one was provided
        if ($imageName !== null) {

            if (!move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadPath
            )) {

                $errors[] = "Failed to save the product image.";
            }
        }
    }


    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO menu_item
                (
                    category_id,
                    menu_name,
                    description,
                    price,
                    stock_quantity,
                    availability,
                    image
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $categoryId,
                $menuName,
                $description,
                $price,
                $stockQuantity,
                $availability,
                $imageName
            ]);


            header(
                "Location: products.php?success=added"
            );

            exit;


        } catch (PDOException $e) {

            // Remove uploaded image if database insert fails
            if (
                $imageName !== null &&
                isset($uploadPath) &&
                file_exists($uploadPath)
            ) {
                unlink($uploadPath);
            }

            $errors[] = "Unable to add product.";
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

    <title>Add Product | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>

<body>

<div class="admin-layout">

    <?php include 'include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- PAGE HEADER -->

        <div class="admin-page-header">

            <div>

                <h1>Add Product</h1>

                <p>
                    Add a new product to the coffee shop.
                </p>

            </div>


            <a
                href="products.php"
                class="btn-secondary"
            >
                ← Back to Products
            </a>

        </div>


        <!-- ERROR MESSAGES -->

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


        <!-- PRODUCT FORM -->

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
                    ><?= htmlspecialchars($description) ?></textarea>

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


                <!-- IMAGE -->

                <div class="form-group">

                    <label for="image">
                        Product Image
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small>
                        JPG, JPEG, PNG or WEBP.
                        Maximum size: 2MB.
                    </small>

                </div>


                <!-- BUTTONS -->

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Add Product
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