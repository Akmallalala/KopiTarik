<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';


// ==========================================
// PAGINATION SETTINGS
// ==========================================

$ordersPerPage = 10;


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
// COUNT ALL ORDERS
// ==========================================

$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM orders
");

$totalOrders = (int)$stmt->fetchColumn();


// ==========================================
// TOTAL PAGES
// ==========================================

$totalPages = max(
    1,
    (int)ceil(
        $totalOrders / $ordersPerPage
    )
);


// Prevent invalid page numbers
if ($page > $totalPages) {
    $page = $totalPages;
}


// ==========================================
// CALCULATE OFFSET
// ==========================================

$offset =
    ($page - 1)
    * $ordersPerPage;


// ==========================================
// GET ORDERS FOR CURRENT PAGE
// ==========================================

$stmt = $pdo->prepare("
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
    ORDER BY
        order_date DESC,
        order_id DESC
    LIMIT :limit
    OFFSET :offset
");


$stmt->bindValue(
    ':limit',
    $ordersPerPage,
    PDO::PARAM_INT
);

$stmt->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);

$stmt->execute();

$orders = $stmt->fetchAll();


// ==========================================
// DISPLAY RANGE
// ==========================================

if ($totalOrders > 0) {

    $startOrder =
        $offset + 1;

    $endOrder = min(
        $offset + $ordersPerPage,
        $totalOrders
    );

} else {

    $startOrder = 0;
    $endOrder = 0;
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
        Manage Orders | KopiTarikBn
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
                    Manage Orders
                </h1>

                <p>
                    View customer orders and transaction details.
                </p>

            </div>

        </div>



        <!-- ==================================
             ORDERS TABLE
        =================================== -->

        <section class="admin-section">


            <div class="section-header">

                <div>

                    <h2>
                        All Orders
                    </h2>

                    <p class="section-description">
                        Customer orders and completed transactions.
                    </p>

                </div>


                <span>
                    <?= $totalOrders ?> total
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


                        <?php foreach (
                            $orders as $order
                        ): ?>


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
                                        $order['customer_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>


                                    <div class="table-subtext">

                                        <?= htmlspecialchars(
                                            $order['customer_email'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                </td>



                                <!-- CUSTOMER TYPE -->

                                <td>


                                    <?php if (
                                        $order['customer_id']
                                        === null
                                    ): ?>


                                        <span
                                            class="
                                                customer-type
                                                guest
                                            "
                                        >
                                            Guest
                                        </span>


                                    <?php else: ?>


                                        <span
                                            class="
                                                customer-type
                                                registered
                                            "
                                        >
                                            Registered
                                        </span>


                                    <?php endif; ?>


                                </td>



                                <!-- DATE -->

                                <td>

                                    <?= date(
                                        'd M Y',
                                        strtotime(
                                            $order['order_date']
                                        )
                                    ) ?>


                                    <div class="table-subtext">

                                        <?= date(
                                            'h:i A',
                                            strtotime(
                                                $order['order_date']
                                            )
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
                                        class="
                                            status
                                            status-<?= strtolower(
                                                htmlspecialchars(
                                                    $order['status'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                )
                                            ) ?>
                                        "
                                    >

                                        <?= htmlspecialchars(
                                            $order['status'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                </td>



                                <!-- VIEW -->

                                <td>

                                    <a
                                        href="view_order.php?id=<?= (int)$order['order_id'] ?>"
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



            <!-- ==================================
                 PAGINATION
            =================================== -->

            <?php if ($totalOrders > 0): ?>


                <div class="products-pagination">


                    <!-- RESULT INFO -->

                    <div class="pagination-info">

                        Showing

                        <strong>
                            <?= $startOrder ?>
                        </strong>

                        –

                        <strong>
                            <?= $endOrder ?>
                        </strong>

                        of

                        <strong>
                            <?= $totalOrders ?>
                        </strong>

                        orders

                    </div>



                    <?php if ($totalPages > 1): ?>


                        <nav
                            class="pagination-links"
                            aria-label="Order pages"
                        >


                            <!-- PREVIOUS -->

                            <?php if ($page > 1): ?>


                                <a
                                    href="orders.php?page=<?= $page - 1 ?>"
                                    class="pagination-button"
                                >
                                    ← Previous
                                </a>


                            <?php endif; ?>



                            <!-- PAGE NUMBERS -->

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
                                    href="orders.php?page=1"
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
                                        href="orders.php?page=<?= $i ?>"
                                        class="pagination-button"
                                    >
                                        <?= $i ?>
                                    </a>


                                <?php endif; ?>


                            <?php endfor; ?>



                            <!-- LAST PAGE -->

                            <?php if (
                                $endPage < $totalPages
                            ): ?>


                                <?php if (
                                    $endPage <
                                    $totalPages - 1
                                ): ?>

                                    <span
                                        class="pagination-dots"
                                    >
                                        ...
                                    </span>

                                <?php endif; ?>


                                <a
                                    href="orders.php?page=<?= $totalPages ?>"
                                    class="pagination-button"
                                >
                                    <?= $totalPages ?>
                                </a>


                            <?php endif; ?>



                            <!-- NEXT -->

                            <?php if (
                                $page < $totalPages
                            ): ?>


                                <a
                                    href="orders.php?page=<?= $page + 1 ?>"
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


</body>

</html>