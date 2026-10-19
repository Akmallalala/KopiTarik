<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// TOTAL ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM orders
");

$totalOrders =
    (int)$stmt->fetch()['total'];


// ==========================================
// COMPLETED ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Completed'
");

$completedOrders =
    (int)$stmt->fetch()['total'];


// ==========================================
// TOTAL REVENUE
// Completed orders only
// ==========================================

$stmt = $pdo->query("
    SELECT
        COALESCE(
            SUM(total_amount),
            0
        ) AS revenue
    FROM orders
    WHERE status = 'Completed'
");

$totalRevenue =
    $stmt->fetch()['revenue'];


// ==========================================
// TOTAL PRODUCTS SOLD
// Completed orders only
// ==========================================

$stmt = $pdo->query("
    SELECT
        COALESCE(
            SUM(order_items.quantity),
            0
        ) AS total

    FROM order_items

    INNER JOIN orders
        ON order_items.order_id
        = orders.order_id

    WHERE orders.status = 'Completed'
");

$totalProductsSold =
    (int)$stmt->fetch()['total'];


// ==========================================
// BEST SELLING PRODUCTS PAGINATION
// ==========================================

$productsPerPage = 5;


// ==========================================
// GET CURRENT BEST-SELLER PAGE
// ==========================================

$bestPage = filter_input(
    INPUT_GET,
    'best_page',
    FILTER_VALIDATE_INT
);

if (!$bestPage || $bestPage < 1) {
    $bestPage = 1;
}


// ==========================================
// COUNT BEST-SELLING PRODUCTS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*)

    FROM
    (
        SELECT
            order_items.menu_name

        FROM order_items

        INNER JOIN orders
            ON order_items.order_id
            = orders.order_id

        WHERE
            orders.status
            = 'Completed'

        GROUP BY
            order_items.menu_name

    ) AS best_selling_products
");

$totalBestProducts =
    (int)$stmt->fetchColumn();


// ==========================================
// TOTAL BEST-SELLER PAGES
// ==========================================

$totalBestPages = max(
    1,
    (int)ceil(
        $totalBestProducts
        / $productsPerPage
    )
);


// Prevent invalid page
if ($bestPage > $totalBestPages) {
    $bestPage = $totalBestPages;
}


// ==========================================
// BEST-SELLER OFFSET
// ==========================================

$bestOffset =
    ($bestPage - 1)
    * $productsPerPage;


// ==========================================
// GET BEST-SELLING PRODUCTS
// ==========================================

$stmt = $pdo->prepare("
    SELECT
        order_items.menu_name,

        SUM(
            order_items.quantity
        ) AS quantity_sold,

        SUM(
            order_items.subtotal
        ) AS revenue

    FROM order_items

    INNER JOIN orders
        ON order_items.order_id
        = orders.order_id

    WHERE
        orders.status
        = 'Completed'

    GROUP BY
        order_items.menu_name

    ORDER BY
        quantity_sold DESC,
        revenue DESC,
        order_items.menu_name ASC

    LIMIT :limit
    OFFSET :offset
");


$stmt->bindValue(
    ':limit',
    $productsPerPage,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':offset',
    $bestOffset,
    PDO::PARAM_INT
);

$stmt->execute();

$productSales =
    $stmt->fetchAll();


// ==========================================
// BEST PRODUCT DISPLAY RANGE
// ==========================================

if ($totalBestProducts > 0) {

    $startBestProduct =
        $bestOffset + 1;

    $endBestProduct = min(
        $bestOffset + $productsPerPage,
        $totalBestProducts
    );

} else {

    $startBestProduct = 0;

    $endBestProduct = 0;
}


// ==========================================
// RECENT COMPLETED SALES
// Keep latest 5 only
// ==========================================

$stmt = $pdo->query("
    SELECT
        order_id,
        customer_name,
        order_date,
        total_amount

    FROM orders

    WHERE status = 'Completed'

    ORDER BY
        order_date DESC,
        order_id DESC

    LIMIT 5
");

$recentSales =
    $stmt->fetchAll();

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
        Sales Report | KopiTarikBn
    </title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>


<body>


<div class="admin-layout">


    <?php include 'include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- ==================================
             PAGE HEADER
        =================================== -->

        <div class="admin-page-header">

            <div>

                <h1>
                    Sales Report
                </h1>

                <p>
                    Overview of completed orders
                    and product sales.
                </p>

            </div>

        </div>



        <!-- ==================================
             REPORT SUMMARY
        =================================== -->

        <section class="dashboard-cards">


            <!-- TOTAL ORDERS -->

            <div class="dashboard-card">


                <span class="card-label">
                    Total Orders
                </span>


                <h2>
                    <?= $totalOrders ?>
                </h2>


                <span class="report-note">
                    All order statuses
                </span>


            </div>



            <!-- COMPLETED ORDERS -->

            <div class="dashboard-card">


                <span class="card-label">
                    Completed Orders
                </span>


                <h2>
                    <?= $completedOrders ?>
                </h2>


                <span class="report-note">
                    Successful sales
                </span>


            </div>



            <!-- TOTAL PRODUCTS SOLD -->

            <div class="dashboard-card">


                <span class="card-label">
                    Products Sold
                </span>


                <h2>
                    <?= $totalProductsSold ?>
                </h2>


                <span class="report-note">
                    Completed orders only
                </span>


            </div>



            <!-- TOTAL REVENUE -->

            <div class="dashboard-card">


                <span class="card-label">
                    Total Revenue
                </span>


                <h2>

                    $<?= number_format(
                        $totalRevenue,
                        2
                    ) ?>

                </h2>


                <span class="report-note">
                    Completed orders only
                </span>


            </div>


        </section>



        <!-- ==================================
             BEST SELLING PRODUCTS
        =================================== -->

        <section
            class="
                admin-section
                report-section
            "
        >


            <div class="section-header">


                <div>

                    <h2>
                        Best-Selling Products
                    </h2>


                    <p class="section-description">

                        Product performance
                        from completed orders.

                    </p>

                </div>


            </div>



            <div class="table-container">


                <table class="admin-table">


                    <thead>


                    <tr>

                        <th>
                            Rank
                        </th>

                        <th>
                            Product
                        </th>

                        <th>
                            Quantity Sold
                        </th>

                        <th>
                            Revenue
                        </th>

                    </tr>


                    </thead>



                    <tbody>


                    <?php if (
                        !empty($productSales)
                    ): ?>


                        <?php

                        // Continue rank across pages
                        $rank =
                            $bestOffset + 1;

                        ?>


                        <?php foreach (
                            $productSales
                            as $product
                        ): ?>


                            <tr>


                                <!-- RANK -->

                                <td>

                                    <?= $rank ?>

                                </td>



                                <!-- PRODUCT -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $product['menu_name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- QUANTITY -->

                                <td>

                                    <?= (int)$product[
                                        'quantity_sold'
                                    ] ?>

                                </td>



                                <!-- REVENUE -->

                                <td>

                                    $<?= number_format(
                                        $product['revenue'],
                                        2
                                    ) ?>

                                </td>


                            </tr>


                            <?php $rank++; ?>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="4"
                                class="empty-table"
                            >

                                No completed sales found.

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>



            <!-- ==================================
                 BEST SELLER PAGINATION
            =================================== -->

            <?php if (
                $totalBestProducts > 0
            ): ?>


                <div class="products-pagination">


                    <!-- RESULT INFO -->

                    <div class="pagination-info">

                        Showing

                        <strong>
                            <?= $startBestProduct ?>
                        </strong>

                        –

                        <strong>
                            <?= $endBestProduct ?>
                        </strong>

                        of

                        <strong>
                            <?= $totalBestProducts ?>
                        </strong>

                        products

                    </div>



                    <?php if (
                        $totalBestPages > 1
                    ): ?>


                        <nav
                            class="pagination-links"
                            aria-label="
                                Best-selling
                                product pages
                            "
                        >


                            <!-- PREVIOUS -->

                            <?php if (
                                $bestPage > 1
                            ): ?>


                                <a
                                    href="report.php?best_page=<?= $bestPage - 1 ?>"
                                    class="pagination-button"
                                >
                                    ← Previous
                                </a>


                            <?php endif; ?>



                            <?php

                            $startPage = max(
                                1,
                                $bestPage - 2
                            );

                            $endPage = min(
                                $totalBestPages,
                                $bestPage + 2
                            );

                            ?>



                            <!-- FIRST PAGE -->

                            <?php if (
                                $startPage > 1
                            ): ?>


                                <a
                                    href="report.php?best_page=1"
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
                                    $i === $bestPage
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
                                        href="report.php?best_page=<?= $i ?>"
                                        class="pagination-button"
                                    >
                                        <?= $i ?>
                                    </a>


                                <?php endif; ?>


                            <?php endfor; ?>



                            <!-- LAST PAGE -->

                            <?php if (
                                $endPage
                                < $totalBestPages
                            ): ?>


                                <?php if (
                                    $endPage
                                    < $totalBestPages - 1
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                                <a
                                    href="report.php?best_page=<?= $totalBestPages ?>"
                                    class="pagination-button"
                                >
                                    <?= $totalBestPages ?>
                                </a>


                            <?php endif; ?>



                            <!-- NEXT -->

                            <?php if (
                                $bestPage
                                < $totalBestPages
                            ): ?>


                                <a
                                    href="report.php?best_page=<?= $bestPage + 1 ?>"
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



        <!-- ==================================
             RECENT COMPLETED SALES
        =================================== -->

        <section
            class="
                admin-section
                report-section
            "
        >


            <div class="section-header">


                <div>

                    <h2>
                        Recent Completed Sales
                    </h2>


                    <p class="section-description">

                        Latest completed
                        customer orders.

                    </p>

                </div>


            </div>



            <div class="table-container">


                <table class="admin-table">


                    <thead>


                    <tr>

                        <th>
                            Order ID
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>


                    </thead>



                    <tbody>


                    <?php if (
                        !empty($recentSales)
                    ): ?>


                        <?php foreach (
                            $recentSales
                            as $sale
                        ): ?>


                            <tr>


                                <!-- ORDER ID -->

                                <td>

                                    <strong>

                                        ORD-<?= str_pad(
                                            $sale['order_id'],
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- CUSTOMER -->

                                <td>

                                    <?= htmlspecialchars(
                                        $sale['customer_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </td>



                                <!-- DATE -->

                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime(
                                            $sale[
                                                'order_date'
                                            ]
                                        )
                                    ) ?>

                                </td>



                                <!-- TOTAL -->

                                <td>

                                    $<?= number_format(
                                        $sale[
                                            'total_amount'
                                        ],
                                        2
                                    ) ?>

                                </td>



                                <!-- VIEW -->

                                <td>

                                    <a
                                        href="view_order.php?id=<?= (int)$sale['order_id'] ?>"
                                        class="btn-view"
                                    >
                                        View
                                    </a>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="5"
                                class="empty-table"
                            >

                                No completed sales found.

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </section>


    </main>


</div>


</body>

</html>