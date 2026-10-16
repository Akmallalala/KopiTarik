<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// GET ALL PRODUCTS
// ==========================================

$stmt = $pdo->query("
    SELECT
        menu_item.menu_id,
        menu_item.menu_name,
        menu_item.price,
        menu_item.stock_quantity,
        menu_item.availability,
        menu_item.image,
        category.category_name
    FROM menu_item
    INNER JOIN category
        ON menu_item.category_id = category.category_id
    ORDER BY menu_item.menu_id ASC
");

$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Products | KopiTarikBn</title>

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

                <h1>Manage Products</h1>

                <p>
                    View and manage coffee shop products.
                </p>

            </div>


            <a
                href="add_product.php"
                class="btn-primary"
            >
                + Add Product
            </a>

        </div>



        <!-- SUCCESS MESSAGE -->

        <?php if (isset($_GET['success'])): ?>

            <div class="alert-success">

                <?php

                if ($_GET['success'] === 'added') {
                    echo "Product added successfully.";
                }

                elseif ($_GET['success'] === 'updated') {
                    echo "Product updated successfully.";
                }

                elseif ($_GET['success'] === 'deleted') {
                    echo "Product deleted successfully.";
                }

                ?>

            </div>

        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>

    <div class="alert-error">

        <?php

        if ($_GET['error'] === 'invalid') {

            echo "Invalid product.";

        } elseif ($_GET['error'] === 'notfound') {

            echo "Product not found.";

        } elseif ($_GET['error'] === 'delete') {

            echo "Unable to delete product.";

        } else {

            echo "Something went wrong.";

        }

        ?>

    </div>

<?php endif; ?>



        <!-- PRODUCTS TABLE -->

        <section class="admin-section">

            <div class="table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Product</th>

                            <th>Category</th>

                            <th>Price</th>

                            <th>Stock</th>

                            <th>Availability</th>

                            <th>Actions</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (!empty($products)): ?>


                        <?php foreach ($products as $product): ?>

                            <tr>


                                <!-- PRODUCT ID -->

                                <td>

                                    <?= $product['menu_id'] ?>

                                </td>



                                <!-- PRODUCT NAME -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $product['menu_name']
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- CATEGORY -->

                                <td>

                                    <?= htmlspecialchars(
                                        $product['category_name']
                                    ) ?>

                                </td>



                                <!-- PRICE -->

                                <td>

                                    $<?= number_format(
                                        $product['price'],
                                        2
                                    ) ?>

                                </td>



                                <!-- STOCK -->

                                <td>

                                    <?= $product['stock_quantity'] ?>

                                </td>



                                <!-- AVAILABILITY -->

                                <td>

                                    <?php if ($product['availability'] == 1): ?>

                                        <span class="availability available">

                                            Available

                                        </span>

                                    <?php else: ?>

                                        <span class="availability unavailable">

                                            Unavailable

                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- ACTIONS -->

                                <td>

                                    <div class="action-buttons">

                                        <a
                                            href="edit_product.php?id=<?= $product['menu_id'] ?>"
                                            class="btn-edit"
                                        >
                                            Edit
                                        </a>


                                        <form
    action="delete_product.php"
    method="POST"
    class="delete-form"
    onsubmit="return confirmDelete();"
>

    <input
        type="hidden"
        name="product_id"
        value="<?= $product['menu_id'] ?>"
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
                                colspan="7"
                                class="empty-table"
                            >

                                No products found.

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </section>


    </main>

</div>

<script>

function confirmDelete() {

    return confirm(
        "Are you sure you want to delete this product?"
    );

}

</script>

</body>

</html>