<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// GET ALL ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT
        order_id,
        customer_id,
        customer_name,
        customer_email,
        customer_phone,
        order_date,
        total_amount,
        status
    FROM orders
    ORDER BY order_date DESC
");

$orders = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manage Orders | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>

<body class="admin-body">

<div class="admin-layout dashboard-layout admin-theme">

    <?php include 'include/sidebar.php'; ?>


    <main class="admin-main">


        <!-- PAGE HEADER -->

        <div class="admin-page-header">

            <div>

                <h1>Manage Orders</h1>

                <p>
                    View and manage customer orders.
                </p>

            </div>

        </div>


        <!-- SUCCESS MESSAGE -->

        <?php if (isset($_GET['success'])): ?>

            <div class="alert-success">

                <?php

                if ($_GET['success'] === 'updated') {
                    echo "Order status updated successfully.";
                }

                ?>

            </div>

        <?php endif; ?>


        <!-- ORDERS TABLE -->

        <section class="admin-section">

            <div class="section-header">

                <h2>
                    All Orders
                </h2>

                <span>
                    <?= count($orders) ?> total
                </span>

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
                                Date
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (!empty($orders)): ?>


                        <?php foreach ($orders as $order): ?>

                            <tr>


                                <!-- ORDER ID -->

                                <td>

                                    <strong>

                                        ORD-<?= str_pad(
                                            $order['order_id'],
                                            5,
                                            '0',
                                            STR_PAD_LEFT
                                        ) ?>

                                    </strong>

                                </td>


                                <!-- CUSTOMER -->

                                <td>

                                    <?= htmlspecialchars(
                                        $order['customer_name']
                                    ) ?>

                                    <div class="table-subtext">

                                        <?= htmlspecialchars(
                                            $order['customer_email']
                                        ) ?>

                                    </div>

                                </td>


                                <!-- CUSTOMER TYPE -->

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


                                <!-- DATE -->

                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime($order['order_date'])
                                    ) ?>

                                    <div class="table-subtext">

                                        <?= date(
                                            'h:i A',
                                            strtotime($order['order_date'])
                                        ) ?>

                                    </div>

                                </td>


                                <!-- TOTAL -->

                                <td>

                                    $<?= number_format(
                                        $order['total_amount'],
                                        2
                                    ) ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="status status-<?= strtolower(
                                            $order['status']
                                        ) ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $order['status']
                                        ) ?>

                                    </span>

                                </td>


                                <!-- VIEW -->

                                <td>

                                    <a
                                        href="view_order.php?id=<?= $order['order_id'] ?>"
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
                                colspan="7"
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