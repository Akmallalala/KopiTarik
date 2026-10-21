<?php
require __DIR__ . '/../include/admin_auth.php';
require __DIR__ . '/../include/db_connect.php';

$totalOrders = (int) $pdo->query(
    'SELECT COUNT(*) FROM orders'
)->fetchColumn();

// This remains an operational count, separate from payment.
$completedOrders = (int) $pdo->query(
    "SELECT COUNT(*) FROM orders WHERE status = 'Completed'"
)->fetchColumn();

$totalRevenue = $pdo->query(
    "SELECT COALESCE(SUM(total_amount), 0)
     FROM orders
     WHERE payment_status = 'Paid'
       AND payment_method = 'Demo'"
)->fetchColumn();

$totalProductsSold = (int) $pdo->query(
    "SELECT COALESCE(SUM(oi.quantity), 0)
     FROM order_items oi
     JOIN orders o ON o.order_id = oi.order_id
     WHERE o.payment_status = 'Paid'
       AND o.payment_method = 'Demo'"
)->fetchColumn();

$productsPerPage = 5;

$bestPage = filter_input(
    INPUT_GET,
    'best_page',
    FILTER_VALIDATE_INT
);

if (!$bestPage || $bestPage < 1) {
    $bestPage = 1;
}

$totalBestProducts = (int) $pdo->query(
    "SELECT COUNT(*)
     FROM (
         SELECT oi.menu_name
         FROM order_items oi
         JOIN orders o ON o.order_id = oi.order_id
         WHERE o.payment_status = 'Paid'
           AND o.payment_method = 'Demo'
         GROUP BY oi.menu_name
     ) AS paid_products"
)->fetchColumn();

$totalBestPages = max(
    1,
    (int) ceil($totalBestProducts / $productsPerPage)
);

$bestPage = min($bestPage, $totalBestPages);
$bestOffset = ($bestPage - 1) * $productsPerPage;

$stmt = $pdo->prepare(
    "SELECT
         oi.menu_name,
         SUM(oi.quantity) AS quantity_sold,
         SUM(oi.subtotal) AS revenue
     FROM order_items oi
     JOIN orders o ON o.order_id = oi.order_id
     WHERE o.payment_status = 'Paid'
       AND o.payment_method = 'Demo'
     GROUP BY oi.menu_name
     ORDER BY quantity_sold DESC, revenue DESC, oi.menu_name ASC
     LIMIT :limit OFFSET :offset"
);

$stmt->bindValue(':limit', $productsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $bestOffset, PDO::PARAM_INT);
$stmt->execute();

$productSales = $stmt->fetchAll();

$startBestProduct = $totalBestProducts > 0
    ? $bestOffset + 1
    : 0;

$endBestProduct = $totalBestProducts > 0
    ? min($bestOffset + $productsPerPage, $totalBestProducts)
    : 0;

/*
 * Alias paid_at as order_date so the existing table markup works.
 * This table now displays payment dates.
 */
$recentSales = $pdo->query(
    "SELECT
         order_id,
         customer_name,
         paid_at AS order_date,
         total_amount
     FROM orders
     WHERE payment_status = 'Paid'
       AND payment_method = 'Demo'
     ORDER BY paid_at DESC, order_id DESC
     LIMIT 5"
)->fetchAll();
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


<body class="admin-body">


<div class="admin-layout dashboard-layout admin-theme">


    <?php include __DIR__ . '/include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- ==================================
             PAGE HEADER
        =================================== -->

        <div class="admin-page-header">

            <div>

                <span class="products-eyebrow">CAFÉ PERFORMANCE</span>

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

            <div class="dashboard-card dashboard-card-orders">

                <div class="dashboard-card-top">
                    <span class="card-label">Total Orders</span>
                    <span class="dashboard-card-icon" aria-hidden="true">≡</span>
                </div>

                <h2>
                    <?= $totalOrders ?>
                </h2>


                <span class="report-note">
                    All order statuses
                </span>


            </div>



            <!-- COMPLETED ORDERS -->

            <div class="dashboard-card dashboard-card-categories">

                <div class="dashboard-card-top">
                    <span class="card-label">Completed Orders</span>
                    <span class="dashboard-card-icon" aria-hidden="true">✓</span>
                </div>

                <h2>
                    <?= $completedOrders ?>
                </h2>


                <span class="report-note">
                    Successful sales
                </span>


            </div>



            <!-- TOTAL PRODUCTS SOLD -->

            <div class="dashboard-card dashboard-card-products">

                <div class="dashboard-card-top">
                    <span class="card-label">Products Sold</span>
                    <span class="dashboard-card-icon" aria-hidden="true">+</span>
                </div>

                <h2>
                    <?= $totalProductsSold ?>
                </h2>


                <span class="report-note">
                    Completed orders only
                </span>


            </div>



            <!-- TOTAL REVENUE -->

            <div class="dashboard-card dashboard-card-revenue">

                <div class="dashboard-card-top">
                    <span class="card-label">Total Revenue</span>
                    <span class="dashboard-card-icon" aria-hidden="true">$</span>
                </div>

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