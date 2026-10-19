<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// SETTINGS
// ==========================================

// Number of products shown per page
$productsPerPage = 8;


// ==========================================
// GET CATEGORY FILTER
// ==========================================

$selectedCategory = filter_input(
    INPUT_GET,
    'category',
    FILTER_VALIDATE_INT
);

if (
    $selectedCategory === false ||
    $selectedCategory === null ||
    $selectedCategory < 1
) {
    $selectedCategory = null;
}


// ==========================================
// GET SORT OPTION
// ==========================================

$sort = $_GET['sort'] ?? 'id_asc';


// Only allow these sorting options
$sortOptions = [

    'id_asc' =>
        'menu_item.menu_id ASC',

    'id_desc' =>
        'menu_item.menu_id DESC',

    'category_asc' =>
        'category.category_name ASC,
         menu_item.menu_name ASC',

    'name_asc' =>
        'menu_item.menu_name ASC',

    'name_desc' =>
        'menu_item.menu_name DESC',

    'price_asc' =>
        'menu_item.price ASC',

    'price_desc' =>
        'menu_item.price DESC',

    'stock_asc' =>
        'menu_item.stock_quantity ASC',

    'stock_desc' =>
        'menu_item.stock_quantity DESC'
];


// Prevent invalid sort values
if (!array_key_exists($sort, $sortOptions)) {
    $sort = 'id_asc';
}

$orderBy = $sortOptions[$sort];


// ==========================================
// GET CURRENT PAGE
// ==========================================

$page = filter_input(
    INPUT_GET,
    'page',
    FILTER_VALIDATE_INT
);

if (!$page || $page < 1) {
    $page = 1;
}


// ==========================================
// GET ALL CATEGORIES
// Used for filter dropdown
// ==========================================

$stmt = $pdo->query("
    SELECT
        category_id,
        category_name
    FROM category
    ORDER BY category_name ASC
");

$categories = $stmt->fetchAll();


// ==========================================
// BUILD FILTER CONDITION
// ==========================================

$whereSql = '';

$params = [];

if ($selectedCategory !== null) {

    $whereSql = "
        WHERE menu_item.category_id = :category_id
    ";

    $params['category_id'] = $selectedCategory;
}


// ==========================================
// COUNT FILTERED PRODUCTS
// Needed for pagination
// ==========================================

$countSql = "
    SELECT COUNT(*)
    FROM menu_item
    $whereSql
";

$stmt = $pdo->prepare($countSql);

$stmt->execute($params);

$totalProducts = (int)$stmt->fetchColumn();


// ==========================================
// CALCULATE TOTAL PAGES
// ==========================================

$totalPages = max(
    1,
    (int)ceil(
        $totalProducts / $productsPerPage
    )
);


// Prevent invalid high page number
if ($page > $totalPages) {
    $page = $totalPages;
}


// ==========================================
// CALCULATE OFFSET
// ==========================================

$offset =
    ($page - 1)
    * $productsPerPage;


// ==========================================
// GET PRODUCTS
// ==========================================

$sql = "
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
        ON menu_item.category_id
        = category.category_id

    $whereSql

    ORDER BY $orderBy

    LIMIT $productsPerPage
    OFFSET $offset
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$products = $stmt->fetchAll();


// ==========================================
// RESULT RANGE
// Example: Showing 1 - 8 of 20
// ==========================================

if ($totalProducts > 0) {

    $startProduct =
        $offset + 1;

    $endProduct = min(
        $offset + $productsPerPage,
        $totalProducts
    );

} else {

    $startProduct = 0;

    $endProduct = 0;
}


// ==========================================
// PAGINATION URL HELPER
// Keeps filter and sorting selected
// ==========================================

function productPageUrl(
    int $pageNumber,
    ?int $categoryId,
    string $sort
): string {

    $query = [
        'page' => $pageNumber,
        'sort' => $sort
    ];

    if ($categoryId !== null) {

        $query['category'] =
            $categoryId;
    }

    return
        'products.php?'
        . http_build_query($query);
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

    <title>
        Manage Products | KopiTarikBn
    </title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>


<body class="admin-body products-body">


<div
    class="admin-layout
           products-layout
           dashboard-layout
           admin-theme"
>


    <?php include 'include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- ==================================
             PAGE HEADER
        =================================== -->

        <header
            class="admin-page-header
                   products-page-header"
        >

            <div>

                <span class="products-eyebrow">
                    THE MENU
                </span>

                <h1>
                    Manage Products
                </h1>

                <p>
                    Keep your café menu fresh
                    and up to date.
                </p>

            </div>


            <a
                href="add_product.php"
                class="btn-primary"
            >
                <span aria-hidden="true">
                    +
                </span>

                Add Product
            </a>

        </header>



        <!-- ==================================
             SUCCESS MESSAGE
        =================================== -->

        <?php if (isset($_GET['success'])): ?>

            <div class="alert-success">

                <?php

                if (
                    $_GET['success']
                    === 'added'
                ) {

                    echo
                    "Product added successfully.";

                } elseif (
                    $_GET['success']
                    === 'updated'
                ) {

                    echo
                    "Product updated successfully.";

                } elseif (
                    $_GET['success']
                    === 'deleted'
                ) {

                    echo
                    "Product deleted successfully.";

                }

                ?>

            </div>

        <?php endif; ?>



        <!-- ==================================
             ERROR MESSAGE
        =================================== -->

        <?php if (isset($_GET['error'])): ?>

            <div class="alert-error">

                <?php

                if (
                    $_GET['error']
                    === 'invalid'
                ) {

                    echo "Invalid product.";

                } elseif (
                    $_GET['error']
                    === 'notfound'
                ) {

                    echo "Product not found.";

                } elseif (
                    $_GET['error']
                    === 'delete'
                ) {

                    echo
                    "Unable to delete product.";

                } else {

                    echo
                    "Something went wrong.";

                }

                ?>

            </div>

        <?php endif; ?>



        <!-- ==================================
             PRODUCTS SECTION
        =================================== -->

        <section
            class="admin-section
                   products-section"
        >


            <!-- ==================================
                 FILTER + SORT
            =================================== -->

            <form
                method="GET"
                action="products.php"
                class="products-filter-bar"
            >


                <!-- CATEGORY -->

                <div class="filter-control">

                    <label for="category">
                        Category
                    </label>

                    <select
                        name="category"
                        id="category"
                    >

                        <option value="">
                            All Categories
                        </option>


                        <?php foreach (
                            $categories
                            as $category
                        ): ?>

                            <option
                                value="<?= (int)$category['category_id'] ?>"
                                <?= $selectedCategory ==
                                    $category['category_id']
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= htmlspecialchars(
                                    $category['category_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>



                <!-- SORT -->

                <div class="filter-control">

                    <label for="sort">
                        Sort By
                    </label>

                    <select
                        name="sort"
                        id="sort"
                    >


                        <option
                            value="id_asc"
                            <?= $sort === 'id_asc'
                                ? 'selected'
                                : '' ?>
                        >
                            ID: Low to High
                        </option>


                        <option
                            value="id_desc"
                            <?= $sort === 'id_desc'
                                ? 'selected'
                                : '' ?>
                        >
                            ID: High to Low
                        </option>


                        <option
                            value="category_asc"
                            <?= $sort ===
                                'category_asc'
                                ? 'selected'
                                : '' ?>
                        >
                            Category A–Z
                        </option>


                        <option
                            value="name_asc"
                            <?= $sort === 'name_asc'
                                ? 'selected'
                                : '' ?>
                        >
                            Product Name A–Z
                        </option>


                        <option
                            value="name_desc"
                            <?= $sort === 'name_desc'
                                ? 'selected'
                                : '' ?>
                        >
                            Product Name Z–A
                        </option>


                        <option
                            value="price_asc"
                            <?= $sort ===
                                'price_asc'
                                ? 'selected'
                                : '' ?>
                        >
                            Price: Low to High
                        </option>


                        <option
                            value="price_desc"
                            <?= $sort ===
                                'price_desc'
                                ? 'selected'
                                : '' ?>
                        >
                            Price: High to Low
                        </option>


                        <option
                            value="stock_asc"
                            <?= $sort ===
                                'stock_asc'
                                ? 'selected'
                                : '' ?>
                        >
                            Stock: Low to High
                        </option>


                        <option
                            value="stock_desc"
                            <?= $sort ===
                                'stock_desc'
                                ? 'selected'
                                : '' ?>
                        >
                            Stock: High to Low
                        </option>


                    </select>

                </div>



                <!-- FILTER BUTTONS -->

                <div class="filter-actions">

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Apply
                    </button>


                    <a
                        href="products.php"
                        class="btn-secondary"
                    >
                        Reset
                    </a>

                </div>


            </form>



            <!-- ==================================
                 TABLE HEADER
            =================================== -->

            <div
                class="section-header
                       products-section-header"
            >

                <div>

                    <h2>
                        Product catalogue
                    </h2>

                    <p>
                        All the good things
                        on your menu.
                    </p>

                </div>


                <span class="products-count">

                    <?= $totalProducts ?>

                    <?= $totalProducts === 1
                        ? 'item'
                        : 'items' ?>

                </span>

            </div>



            <!-- ==================================
                 PRODUCT TABLE
            =================================== -->

            <div class="table-container">

                <table class="admin-table">


                    <thead>

                    <tr>

                        <th>
                            ID
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Stock
                        </th>

                        <th>
                            Availability
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                    </thead>



                    <tbody>


                    <?php if (!empty($products)): ?>


                        <?php foreach (
                            $products
                            as $product
                        ): ?>


                            <tr>


                                <!-- PRODUCT ID -->

                                <td>

                                    <span class="product-id">

                                        #<?= (int)$product['menu_id'] ?>

                                    </span>

                                </td>



                                <!-- PRODUCT -->

                                <td>

                                    <div class="product-identity">


                                        <?php if (
                                            !empty(
                                                $product['image']
                                            )
                                        ): ?>

                                            <img
                                                class="product-thumbnail"
                                                src="../assets/images/products/<?= htmlspecialchars(
                                                    $product['image'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                alt="<?= htmlspecialchars(
                                                    $product['menu_name'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                loading="lazy"
                                            >


                                        <?php else: ?>


                                            <span
                                                class="
                                                    product-thumbnail
                                                    product-thumbnail-placeholder
                                                "
                                                aria-hidden="true"
                                            >
                                                ☕
                                            </span>


                                        <?php endif; ?>


                                        <strong>

                                            <?= htmlspecialchars(
                                                $product['menu_name'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </strong>


                                    </div>

                                </td>



                                <!-- CATEGORY -->

                                <td>

                                    <span class="product-category">

                                        <?= htmlspecialchars(
                                            $product['category_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                </td>



                                <!-- PRICE -->

                                <td>

                                    <strong class="product-price">

                                        $<?= number_format(
                                            (float)$product['price'],
                                            2
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- STOCK -->

                                <td>

                                    <span class="product-stock">

                                        <?= (int)$product['stock_quantity'] ?>

                                    </span>

                                </td>



                                <!-- AVAILABILITY -->

                                <td>


                                    <?php if (
                                        (int)$product['availability']
                                        === 1
                                    ): ?>


                                        <span
                                            class="
                                                availability
                                                available
                                            "
                                        >
                                            Available
                                        </span>


                                    <?php else: ?>


                                        <span
                                            class="
                                                availability
                                                unavailable
                                            "
                                        >
                                            Unavailable
                                        </span>


                                    <?php endif; ?>


                                </td>



                                <!-- ACTIONS -->

                                <td>

                                    <div class="action-buttons">


                                        <!-- EDIT -->

                                        <a
                                            href="edit_product.php?id=<?= (int)$product['menu_id'] ?>"
                                            class="btn-edit"
                                        >
                                            Edit
                                        </a>



                                        <!-- DELETE -->

                                        <form
                                            action="delete_product.php"
                                            method="POST"
                                            class="delete-form"
                                            onsubmit="return confirmDelete();"
                                        >

                                            <input
                                                type="hidden"
                                                name="product_id"
                                                value="<?= (int)$product['menu_id'] ?>"
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



            <!-- ==================================
                 PAGINATION
            =================================== -->

            <?php if ($totalProducts > 0): ?>


                <div class="products-pagination">


                    <!-- RESULT INFORMATION -->

                    <div class="pagination-info">

                        Showing

                        <strong>
                            <?= $startProduct ?>
                        </strong>

                        –

                        <strong>
                            <?= $endProduct ?>
                        </strong>

                        of

                        <strong>
                            <?= $totalProducts ?>
                        </strong>

                        products

                    </div>



                    <!-- PAGE BUTTONS -->

                    <?php if (
                        $totalPages > 1
                    ): ?>


                        <nav
                            class="pagination-links"
                            aria-label="Product pages"
                        >


                            <!-- PREVIOUS -->

                            <?php if (
                                $page > 1
                            ): ?>

                                <a
                                    href="<?= htmlspecialchars(
                                        productPageUrl(
                                            $page - 1,
                                            $selectedCategory,
                                            $sort
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    ← Previous
                                </a>

                            <?php endif; ?>



                            <?php

                            $startPage = max(
                                1,
                                $page - 2
                            );

                            $endPage = min(
                                $totalPages,
                                $page + 2
                            );

                            ?>



                            <!-- FIRST PAGE -->

                            <?php if (
                                $startPage > 1
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        productPageUrl(
                                            1,
                                            $selectedCategory,
                                            $sort
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    1
                                </a>


                                <?php if (
                                    $startPage > 2
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                            <?php endif; ?>



                            <!-- PAGE NUMBERS -->

                            <?php for (
                                $i = $startPage;
                                $i <= $endPage;
                                $i++
                            ): ?>


                                <?php if (
                                    $i === $page
                                ): ?>


                                    <span
                                        class="
                                            pagination-button
                                            active
                                        "
                                        aria-current="page"
                                    >
                                        <?= $i ?>
                                    </span>


                                <?php else: ?>


                                    <a
                                        href="<?= htmlspecialchars(
                                            productPageUrl(
                                                $i,
                                                $selectedCategory,
                                                $sort
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        class="pagination-button"
                                    >
                                        <?= $i ?>
                                    </a>


                                <?php endif; ?>


                            <?php endfor; ?>



                            <!-- LAST PAGE -->

                            <?php if (
                                $endPage
                                < $totalPages
                            ): ?>


                                <?php if (
                                    $endPage
                                    < $totalPages - 1
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        productPageUrl(
                                            $totalPages,
                                            $selectedCategory,
                                            $sort
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    <?= $totalPages ?>
                                </a>


                            <?php endif; ?>



                            <!-- NEXT -->

                            <?php if (
                                $page
                                < $totalPages
                            ): ?>


                                <a
                                    href="<?= htmlspecialchars(
                                        productPageUrl(
                                            $page + 1,
                                            $selectedCategory,
                                            $sort
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    class="pagination-button"
                                >
                                    Next →
                                </a>


                            <?php endif; ?>


                        </nav>


                    <?php endif; ?>


                </div>


            <?php endif; ?>


        </section>


    </main>


</div>



<!-- ==========================================
     DELETE CONFIRMATION
=========================================== -->

<script>

function confirmDelete() {

    return confirm(
        "Are you sure you want to delete this product?"
    );

}

</script>


</body>

</html>