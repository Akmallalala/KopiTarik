<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// TOTAL PRODUCTS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM menu_item
");

$totalProducts = $stmt->fetch()['total'];


// ==========================================
// TOTAL CATEGORIES
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM category
");

$totalCategories = $stmt->fetch()['total'];


// ==========================================
// TOTAL ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*) AS total
    FROM orders
");

$totalOrders = $stmt->fetch()['total'];


// ==========================================
// TOTAL REVENUE
// Only completed orders count as revenue
// ==========================================

$stmt = $pdo->query("
    SELECT COALESCE(SUM(total_amount), 0) AS total
    FROM orders
    WHERE status = 'Completed'
");

$totalRevenue = $stmt->fetch()['total'];


// ==========================================
// RECENT ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT
        order_id,
        customer_id,
        customer_name,
        total_amount,
        status,
        order_date
    FROM orders
    ORDER BY order_date DESC
    LIMIT 5
");

$recentOrders = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard | KopiTarikBn</title>

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

        <div class="admin-header">

            <div>

                <h1>Dashboard</h1>

                <p>
                    Welcome back,
                    <?= htmlspecialchars(
                        $_SESSION['admin_username']
                    ) ?>
                </p>

            </div>

        </div>


        <!-- DASHBOARD CARDS -->

        <section class="dashboard-cards">


            <div class="dashboard-card">

                <span class="card-label">
                    Total Products
                </span>

                <h2>
                    <?= $totalProducts ?>
                </h2>

                <a href="products.php">
                    View Products
                </a>

            </div>


            <div class="dashboard-card">

                <span class="card-label">
                    Total Categories
                </span>

                <h2>
                    <?= $totalCategories ?>
                </h2>

                <a href="categories.php">
                    View Categories
                </a>

            </div>


            <div class="dashboard-card">

                <span class="card-label">
                    Total Orders
                </span>

                <h2>
                    <?= $totalOrders ?>
                </h2>

                <a href="orders.php">
                    View Orders
                </a>

            </div>


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

                <a href="report.php">
                    View Report
                </a>

            </div>


        </section>


        <!-- RECENT ORDERS -->

        <section class="admin-section">

            <div class="section-header">

                <h2>Recent Orders</h2>

                <a href="orders.php">
                    View All Orders
                </a>

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
                                Type
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Date
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (!empty($recentOrders)): ?>

                        <?php foreach ($recentOrders as $order): ?>

                            <tr>

                                <td>

                                    ORD-<?= str_pad(
                                        $order['order_id'],
                                        5,
                                        '0',
                                        STR_PAD_LEFT
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $order['customer_name']
                                    ) ?>

                                </td>


                                <td>

                                    <?php if ($order['customer_id'] === null): ?>

                                        <span class="customer-type guest">
                                            Guest
                                        </span>

                                    <?php else: ?>

                                        <span class="customer-type registered">
                                            Registered
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    $<?= number_format(
                                        $order['total_amount'],
                                        2
                                    ) ?>

                                </td>


                                <td>

                                    <span class="status
                                        status-<?= strtolower(
                                            $order['status']
                                        ) ?>">

                                        <?= htmlspecialchars(
                                            $order['status']
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime(
                                            $order['order_date']
                                        )
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="empty-table"
                            >
                                No orders found.
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