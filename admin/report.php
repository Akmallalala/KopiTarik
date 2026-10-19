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

$totalOrders = $stmt->fetch()['total'];


// ==========================================
// COMPLETED ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Completed'
");

$completedOrders = $stmt->fetch()['total'];


// ==========================================
// TOTAL REVENUE
// Completed orders only
// ==========================================

$stmt = $pdo->query("
    SELECT COALESCE(SUM(total_amount), 0) AS revenue
    FROM orders
    WHERE status = 'Completed'
");

$totalRevenue = $stmt->fetch()['revenue'];


// ==========================================
// TOTAL PRODUCTS SOLD
// Completed orders only
// ==========================================

$stmt = $pdo->query("
    SELECT COALESCE(SUM(order_items.quantity), 0) AS total
    FROM order_items

    INNER JOIN orders
        ON order_items.order_id = orders.order_id

    WHERE orders.status = 'Completed'
");

$totalProductsSold = $stmt->fetch()['total'];


// ==========================================
// BEST SELLING PRODUCTS
//
// We use order_items.menu_name because it is
// saved as an order snapshot. This means old
// sales still appear even if a product is
// later deleted from menu_item.
// ==========================================

$stmt = $pdo->query("
    SELECT
        order_items.menu_name,
        SUM(order_items.quantity) AS quantity_sold,
        SUM(order_items.subtotal) AS revenue

    FROM order_items

    INNER JOIN orders
        ON order_items.order_id = orders.order_id

    WHERE orders.status = 'Completed'

    GROUP BY order_items.menu_name

    ORDER BY quantity_sold DESC
");

$productSales = $stmt->fetchAll();


// ==========================================
// RECENT COMPLETED SALES
// ==========================================

$stmt = $pdo->query("
    SELECT
        order_id,
        customer_name,
        order_date,
        total_amount
    FROM orders
    WHERE status = 'Completed'
    ORDER BY order_date DESC
    LIMIT 5
");

$recentSales = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Sales Report | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>

<body class="admin-body">

<div class="admin-layout dashboard-layout admin-theme">

    <?php include 'include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- ==================================
             PAGE HEADER
        =================================== -->

        <div class="admin-page-header">

            <div>

                <h1>Sales Report</h1>

                <p>
                    Overview of completed orders and product sales.
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

        <section class="admin-section report-section">

            <div class="section-header">

                <div>

                    <h2>
                        Best-Selling Products
                    </h2>

                    <p class="section-description">
                        Product performance from completed orders.
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


                    <?php if (!empty($productSales)): ?>

                        <?php
                        $rank = 1;
                        ?>

                        <?php foreach ($productSales as $product): ?>

                            <tr>

                                <td>

                                    <?= $rank ?>

                                </td>


                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $product['menu_name']
                                        ) ?>

                                    </strong>

                                </td>


                                <td>

                                    <?= $product['quantity_sold'] ?>

                                </td>


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

        </section>


        <!-- ==================================
             RECENT COMPLETED SALES
        =================================== -->

        <section class="admin-section report-section">

            <div class="section-header">

                <div>

                    <h2>
                        Recent Completed Sales
                    </h2>

                    <p class="section-description">
                        Latest completed customer orders.
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


                    <?php if (!empty($recentSales)): ?>


                        <?php foreach ($recentSales as $sale): ?>

                            <tr>


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


                                <td>

                                    <?= htmlspecialchars(
                                        $sale['customer_name']
                                    ) ?>

                                </td>


                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime(
                                            $sale['order_date']
                                        )
                                    ) ?>

                                </td>


                                <td>

                                    $<?= number_format(
                                        $sale['total_amount'],
                                        2
                                    ) ?>

                                </td>


                                <td>

                                    <a
                                        href="view_order.php?id=<?= $sale['order_id'] ?>"
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