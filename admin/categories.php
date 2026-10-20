<?php

require __DIR__ . '/../include/admin_auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') verify_csrf();
require __DIR__ . '/../include/db_connect.php';

$errors = [];
$categoryName = '';
$editCategoryId = null;


// ==========================================
// HANDLE FORM ACTIONS
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach (['action','category_id','category_name'] as $field) {
        if (isset($_POST[$field]) && !is_string($_POST[$field])) { http_response_code(400); exit('Invalid form value.'); }
    }
    $action = $_POST['action'] ?? '';


    // ======================================
    // ADD CATEGORY
    // ======================================

    if ($action === 'add') {

        $categoryName = trim($_POST['category_name'] ?? '');

        if ($categoryName === '') {
            $errors[] = "Category name is required.";
        }

        if (strlen($categoryName) > 100) {
            $errors[] = "Category name must be 100 characters or less.";
        }


        // Check duplicate
        if (empty($errors)) {

            $stmt = $pdo->prepare("
                SELECT category_id
                FROM category
                WHERE category_name = ?
            ");

            $stmt->execute([$categoryName]);

            if ($stmt->fetch()) {
                $errors[] = "This category already exists.";
            }
        }


        // Insert category
        if (empty($errors)) {

            $stmt = $pdo->prepare("
                INSERT INTO category (category_name)
                VALUES (?)
            ");

            $stmt->execute([$categoryName]);

            header("Location: categories.php?success=added");
            exit;
        }
    }


    // ======================================
    // UPDATE CATEGORY
    // ======================================

    elseif ($action === 'update') {

        $editCategoryId = $_POST['category_id'] ?? null;
        $categoryName = trim($_POST['category_name'] ?? '');

        if (
            !$editCategoryId ||
            !filter_var($editCategoryId, FILTER_VALIDATE_INT)
        ) {
            $errors[] = "Invalid category.";
        }

        if ($categoryName === '') {
            $errors[] = "Category name is required.";
        }

        if (strlen($categoryName) > 100) {
            $errors[] = "Category name must be 100 characters or less.";
        }


        // Check duplicate but ignore current category
        if (empty($errors)) {

            $stmt = $pdo->prepare("
                SELECT category_id
                FROM category
                WHERE category_name = ?
                AND category_id != ?
            ");

            $stmt->execute([
                $categoryName,
                $editCategoryId
            ]);

            if ($stmt->fetch()) {
                $errors[] = "This category already exists.";
            }
        }


        if (empty($errors)) {

            $stmt = $pdo->prepare("
                UPDATE category
                SET category_name = ?
                WHERE category_id = ?
            ");

            $stmt->execute([
                $categoryName,
                $editCategoryId
            ]);

            header("Location: categories.php?success=updated");
            exit;
        }
    }


    // ======================================
    // DELETE CATEGORY
    // ======================================

    elseif ($action === 'delete') {

        $categoryId = $_POST['category_id'] ?? null;

        if (
            !$categoryId ||
            !filter_var($categoryId, FILTER_VALIDATE_INT)
        ) {
            header("Location: categories.php?error=invalid");
            exit;
        }


        // Check if category is being used
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS total
            FROM menu_item
            WHERE category_id = ?
        ");

        $stmt->execute([$categoryId]);

        $productCount = $stmt->fetch()['total'];


        if ($productCount > 0) {

            header("Location: categories.php?error=inuse");
            exit;
        }


        $stmt = $pdo->prepare("
            DELETE FROM category
            WHERE category_id = ?
        ");

        $stmt->execute([$categoryId]);

        header("Location: categories.php?success=deleted");
        exit;
    }
}


// ==========================================
// LOAD CATEGORY FOR EDITING
// ==========================================

if (
    isset($_GET['edit']) &&
    filter_var($_GET['edit'], FILTER_VALIDATE_INT)
) {

    $editCategoryId = $_GET['edit'];

    $stmt = $pdo->prepare("
        SELECT category_id, category_name
        FROM category
        WHERE category_id = ?
    ");

    $stmt->execute([$editCategoryId]);

    $editCategory = $stmt->fetch();

    if ($editCategory) {

        $categoryName = $editCategory['category_name'];

    } else {

        header("Location: categories.php?error=notfound");
        exit;
    }
}


// ==========================================
// GET ALL CATEGORIES
// ==========================================

$stmt = $pdo->query("
    SELECT
        category.category_id,
        category.category_name,
        COUNT(menu_item.menu_id) AS total_products
    FROM category

    LEFT JOIN menu_item
        ON category.category_id = menu_item.category_id

    GROUP BY
        category.category_id,
        category.category_name

    ORDER BY category.category_id ASC
");

$categories = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Categories | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>

<body class="admin-body">

<div class="admin-layout dashboard-layout admin-theme">

    <?php include __DIR__ . '/include/sidebar.php'; ?>


    <main class="admin-main">

        <!-- PAGE HEADER -->

        <div class="admin-page-header">

            <div>

                <h1>Manage Categories</h1>

                <p>
                    Add and manage product categories.
                </p>

            </div>

        </div>


        <!-- SUCCESS MESSAGE -->

        <?php if (isset($_GET['success'])): ?>

            <div class="alert-success">

                <?php

                if ($_GET['success'] === 'added') {
                    echo "Category added successfully.";
                }

                elseif ($_GET['success'] === 'updated') {
                    echo "Category updated successfully.";
                }

                elseif ($_GET['success'] === 'deleted') {
                    echo "Category deleted successfully.";
                }

                ?>

            </div>

        <?php endif; ?>


        <!-- ERROR FROM REDIRECT -->

        <?php if (isset($_GET['error'])): ?>

            <div class="alert-error">

                <?php

                if ($_GET['error'] === 'inuse') {

                    echo "This category cannot be deleted because products are using it.";

                }

                elseif ($_GET['error'] === 'notfound') {

                    echo "Category not found.";

                }

                else {

                    echo "Invalid category.";

                }

                ?>

            </div>

        <?php endif; ?>


        <!-- VALIDATION ERRORS -->

        <?php if (!empty($errors)): ?>

            <div class="alert-error">

                <ul>

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <div class="category-layout">


            <!-- ADD / EDIT FORM -->

            <section class="category-form-box">

                <?php if ($editCategoryId): ?>

                    <h2>Edit Category</h2>

                    <p>
                        Update the selected category.
                    </p>

                <?php else: ?>

                    <h2>Add Category</h2>

                    <p>
                        Create a new product category.
                    </p>

                <?php endif; ?>


                <form method="POST" class="admin-form">
<?= csrf_field() ?>

                    <?php if ($editCategoryId): ?>

                        <input
                            type="hidden"
                            name="action"
                            value="update"
                        >

                        <input
                            type="hidden"
                            name="category_id"
                            value="<?= $editCategoryId ?>"
                        >

                    <?php else: ?>

                        <input
                            type="hidden"
                            name="action"
                            value="add"
                        >

                    <?php endif; ?>


                    <div class="form-group">

                        <label for="category_name">
                            Category Name *
                        </label>

                        <input
                            type="text"
                            id="category_name"
                            name="category_name"
                            value="<?= htmlspecialchars($categoryName) ?>"
                            maxlength="100"
                            placeholder="e.g. Coffee Beans"
                            required
                        >

                    </div>


                    <div class="form-actions">

                        <?php if ($editCategoryId): ?>

                            <button
                                type="submit"
                                class="btn-primary"
                            >
                                Save Changes
                            </button>

                            <a
                                href="categories.php"
                                class="btn-secondary"
                            >
                                Cancel
                            </a>

                        <?php else: ?>

                            <button
                                type="submit"
                                class="btn-primary"
                            >
                                Add Category
                            </button>

                        <?php endif; ?>

                    </div>

                </form>

            </section>


            <!-- CATEGORY TABLE -->

            <section class="admin-section category-table-section">

                <div class="section-header">

                    <h2>Categories</h2>

                    <span>
                        <?= count($categories) ?> total
                    </span>

                </div>


                <div class="table-container">

                    <table class="admin-table">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Category</th>

                                <th>Products</th>

                                <th>Actions</th>

                            </tr>

                        </thead>


                        <tbody>

                        <?php if (!empty($categories)): ?>


                            <?php foreach ($categories as $category): ?>

                                <tr>

                                    <td>
                                        <?= $category['category_id'] ?>
                                    </td>


                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $category['category_name']
                                            ) ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?= $category['total_products'] ?>

                                    </td>


                                    <td>

                                        <div class="action-buttons">


                                            <!-- EDIT -->

                                            <a
                                                href="categories.php?edit=<?= $category['category_id'] ?>"
                                                class="btn-edit"
                                            >
                                                Edit
                                            </a>


                                            <!-- DELETE -->

                                            <form
                                                method="POST"
                                                class="delete-form"
                                                onsubmit="return confirmCategoryDelete();"
                                            >
<?= csrf_field() ?>

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="delete"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="category_id"
                                                    value="<?= $category['category_id'] ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn-delete"
                                                >
                                                    Delete
                                                </button>

                                            </form>


                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>


                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="4"
                                    class="empty-table"
                                >
                                    No categories found.
                                </td>

                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>


        </div>

    </main>

</div>


<script>

function confirmCategoryDelete() {

    return confirm(
        "Are you sure you want to delete this category?"
    );

}

</script>

</body>

</html>