<?php

require __DIR__ . '/../include/admin_auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') verify_csrf();
require __DIR__ . '/../include/db_connect.php';


// ==========================================
// VALID ORDER STATUSES
// ==========================================

$validStatuses = [
    'Pending',
    'Preparing',
    'Ready',
    'Completed',
    'Cancelled'
];


// ==========================================
// CREATE CSRF TOKEN
// ==========================================

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// ==========================================
// GET ORDER ID
// ==========================================

$orderId = $_GET['id'] ?? null;

if (
    !$orderId ||
    !filter_var($orderId, FILTER_VALIDATE_INT)
) {
    header("Location: orders.php");
    exit;
}


// ==========================================
// GET ORDER
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
    WHERE order_id = ?
");

$stmt->execute([$orderId]);

$order = $stmt->fetch();


if (!$order) {
    header("Location: orders.php");
    exit;
}


// ==========================================
// UPDATE ORDER STATUS
// ==========================================

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status = $_POST['status'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';


    // Check CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {

        $error = "Invalid request. Please try again.";

    } elseif (!in_array($status, $validStatuses, true)) {

        $error = "Invalid order status.";

    } else {

        $stmt = $pdo->prepare("
            UPDATE orders
            SET status = ?
            WHERE order_id = ?
        ");

        $stmt->execute([
            $status,
            $orderId
        ]);


        header(
            "Location: view_order.php?id="
            . $orderId
            . "&success=updated"
        );

        exit;
    }
}


// ==========================================
// GET ORDER ITEMS
// ==========================================

$stmt = $pdo->prepare("
    SELECT
        order_item_id,
        menu_id,
        menu_name,
        quantity,
        unit_price,
        subtotal, drink_size, milk_type, add_ins, espresso_shots, syrup_pumps, topping, barista_note
    FROM order_items
    WHERE order_id = ?
    ORDER BY order_item_id ASC
");

$stmt->execute([$orderId]);

$orderItems = $stmt->fetchAll();

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
        View Order | KopiTarikBn
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


        <!-- PAGE HEADER -->

        <div class="admin-page-header">

            <div>

                <h1>
                    Order
                    ORD-<?= str_pad(
                        $order['order_id'],
                        5,
                        '0',
                        STR_PAD_LEFT
                    ) ?>
                </h1>

                <p>
                    View order details and update its status.
                </p>

            </div>


            <a
                href="orders.php"
                class="btn-secondary"
            >
                ← Back to Orders
            </a>

        </div>


        <!-- SUCCESS -->

        <?php if (
            isset($_GET['success']) &&
            $_GET['success'] === 'updated'
        ): ?>

            <div class="alert-success">
                Order status updated successfully.
            </div>

        <?php endif; ?>


        <!-- ERROR -->

        <?php if ($error !== ''): ?>

            <div class="alert-error">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <div class="order-details-grid">


            <!-- CUSTOMER DETAILS -->

            <section class="order-info-card">

                <h2>
                    Customer Information
                </h2>


                <div class="order-info-row">

                    <span>Name</span>

                    <strong>

                        <?= htmlspecialchars(
                            $order['customer_name']
                        ) ?>

                    </strong>

                </div>


                <div class="order-info-row">

                    <span>Email</span>

                    <strong>

                        <?= htmlspecialchars(
                            $order['customer_email']
                        ) ?>

                    </strong>

                </div>


                <div class="order-info-row">

                    <span>Phone</span>

                    <strong>

                        <?= htmlspecialchars(
                            $order['customer_phone']
                        ) ?>

                    </strong>

                </div>


                <div class="order-info-row">

                    <span>Customer Type</span>

                    <strong>

                        <?php if ($order['customer_id'] === null): ?>

                            <span class="customer-type guest">
                                Guest
                            </span>

                        <?php else: ?>

                            <span class="customer-type registered">
                                Registered
                            </span>

                        <?php endif; ?>

                    </strong>

                </div>

            </section>


            <!-- ORDER INFORMATION -->

            <section class="order-info-card">

                <h2>
                    Order Information
                </h2>


                <div class="order-info-row">

                    <span>Order ID</span>

                    <strong>

                        ORD-<?= str_pad(
                            $order['order_id'],
                            5,
                            '0',
                            STR_PAD_LEFT
                        ) ?>

                    </strong>

                </div>


                <div class="order-info-row">

                    <span>Date</span>

                    <strong>

                        <?= date(
                            'd M Y, h:i A',
                            strtotime($order['order_date'])
                        ) ?>

                    </strong>

                </div>


                <div class="order-info-row">

                    <span>Total</span>

                    <strong>

                        $<?= number_format(
                            $order['total_amount'],
                            2
                        ) ?>

                    </strong>

                </div>


                <div class="order-info-row">

                    <span>Status</span>

                    <strong>

                        <span
                            class="status status-<?= strtolower(
                                $order['status']
                            ) ?>"
                        >

                            <?= htmlspecialchars(
                                $order['status']
                            ) ?>

                        </span>

                    </strong>

                </div>

            </section>

        </div>


        <!-- ORDER ITEMS -->

        <section class="admin-section order-items-section">

            <div class="section-header">

                <h2>
                    Order Items
                </h2>

                <span>
                    <?= count($orderItems) ?> item(s)
                </span>

            </div>


            <div class="table-container">

                <table class="admin-table">

                    <thead>

                        <tr>

                            <th>
                                Product
                            </th>

                            <th>
                                Unit Price
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Subtotal
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (!empty($orderItems)): ?>


                        <?php foreach ($orderItems as $item): ?>

                            <tr>

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $item['menu_name']
                                        ) ?>

                                    </strong>
                                    <dl class="order-customisations">
                                    <?php foreach (['drink_size'=>'Size','milk_type'=>'Milk','add_ins'=>'Add-ins','espresso_shots'=>'Espresso shots','syrup_pumps'=>'Syrup pumps','topping'=>'Topping','barista_note'=>'Barista note'] as $key=>$label): ?>
                                        <?php if (isset($item[$key]) && $item[$key] !== ''): ?><dt><?= e($label) ?></dt><dd><?= nl2br(e($item[$key])) ?></dd><?php endif; ?>
                                    <?php endforeach; ?>
                                    </dl>

                                </td>


                                <td>

                                    $<?= number_format(
                                        $item['unit_price'],
                                        2
                                    ) ?>

                                </td>


                                <td>

                                    <?= $item['quantity'] ?>

                                </td>


                                <td>

                                    $<?= number_format(
                                        $item['subtotal'],
                                        2
                                    ) ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>


                    <?php else: ?>

                        <tr>

                            <td
                                colspan="4"
                                class="empty-table"
                            >
                                No items found for this order.
                            </td>

                        </tr>

                    <?php endif; ?>


                    </tbody>


                    <?php if (!empty($orderItems)): ?>

                        <tfoot>

                            <tr>

                                <td colspan="3">

                                    <strong>
                                        Order Total
                                    </strong>

                                </td>

                                <td>

                                    <strong>

                                        $<?= number_format(
                                            $order['total_amount'],
                                            2
                                        ) ?>

                                    </strong>

                                </td>

                            </tr>

                        </tfoot>

                    <?php endif; ?>

                </table>

            </div>

        </section>


        <!-- UPDATE STATUS -->

        <section class="order-status-card">

            <h2>
                Update Order Status
            </h2>

            <p>
                Change the current processing status of this order.
            </p>


            <form method="POST" class="status-form">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        $_SESSION['csrf_token']
                    ) ?>"
                >


                <div class="form-group">

                    <label for="status">
                        Order Status
                    </label>

                    <select
                        name="status"
                        id="status"
                        required
                    >

                        <?php foreach ($validStatuses as $status): ?>

                            <option
                                value="<?= htmlspecialchars($status) ?>"
                                <?= $order['status'] === $status
                                    ? 'selected'
                                    : '' ?>
                            >

                                <?= htmlspecialchars($status) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn-primary"
                >
                    Update Status
                </button>

            </form>

        </section>


    </main>

</div>

</body>

</html>