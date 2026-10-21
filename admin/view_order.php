<?php

require __DIR__ . '/../include/admin_auth.php';
require __DIR__ . '/../include/db_connect.php';

// Allowed order-status changes.
$statusTransitions = [
    'Pending'   => ['Preparing', 'Cancelled'],
    'Preparing' => ['Ready'],
    'Ready'     => ['Completed'],
    'Completed' => [],
    'Cancelled' => []
];

// Validate the order ID.
$orderId = filter_var(
    $_GET['id'] ?? null,
    FILTER_VALIDATE_INT
);

if (!$orderId || $orderId < 1) {
    header('Location: orders.php');
    exit;
}

$error = '';

// Process status updates.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $newStatus = $_POST['status'] ?? '';

    try {
        if (
            !is_string($newStatus)
            || !array_key_exists($newStatus, $statusTransitions)
        ) {
            throw new RuntimeException('Invalid order status.');
        }

        $pdo->beginTransaction();

        // Lock the order to prevent duplicate cancellation/refunds.
        $stmt = $pdo->prepare(
            'SELECT status, payment_status, payment_method
             FROM orders
             WHERE order_id = ?
             FOR UPDATE'
        );

        $stmt->execute([$orderId]);
        $currentOrder = $stmt->fetch();

        if (!$currentOrder) {
            throw new RuntimeException('Order not found.');
        }

        $currentStatus = $currentOrder['status'];

        // Selecting the current status does not change stock or payment.
        if ($newStatus !== $currentStatus) {
            $allowedStatuses =
                $statusTransitions[$currentStatus] ?? [];

            if (!in_array($newStatus, $allowedStatuses, true)) {
                throw new RuntimeException(
                    'This status change is not allowed. '
                    . 'Only Pending orders can be cancelled. '
                    . 'Completed and Cancelled orders are final.'
                );
            }

            if ($newStatus === 'Cancelled') {
                // This application can only simulate refunds.
                if (
                    $currentOrder['payment_status'] === 'Paid'
                    && $currentOrder['payment_method'] !== 'Demo'
                ) {
                    throw new RuntimeException(
                        'This payment cannot be refunded by the demo system.'
                    );
                }

                // Combine quantities for customised versions of one drink.
                $stmt = $pdo->prepare(
                    'SELECT menu_id, SUM(quantity) AS total_quantity
                     FROM order_items
                     WHERE order_id = ?
                       AND menu_id IS NOT NULL
                     GROUP BY menu_id
                     ORDER BY menu_id ASC'
                );

                $stmt->execute([$orderId]);
                $itemsToRestore = $stmt->fetchAll();

                $lockProduct = $pdo->prepare(
                    'SELECT stock_quantity
                     FROM menu_item
                     WHERE menu_id = ?
                     FOR UPDATE'
                );

                $restoreStock = $pdo->prepare(
                    'UPDATE menu_item
                     SET stock_quantity = stock_quantity + ?
                     WHERE menu_id = ?'
                );

                foreach ($itemsToRestore as $item) {
                    $menuId = (int) $item['menu_id'];
                    $quantity = (int) $item['total_quantity'];

                    if ($quantity < 1) {
                        throw new RuntimeException(
                            'This order contains an invalid quantity.'
                        );
                    }

                    $lockProduct->execute([$menuId]);
                    $product = $lockProduct->fetch();

                    // Deleted products cannot be restocked.
                    if (!$product) {
                        continue;
                    }

                    if (
                        (int) $product['stock_quantity']
                        > 2147483647 - $quantity
                    ) {
                        throw new RuntimeException(
                            'Restoring stock would exceed the stock limit.'
                        );
                    }

                    // Keep the product's existing availability setting.
                    $restoreStock->execute([$quantity, $menuId]);
                }

                if ($currentOrder['payment_status'] === 'Paid') {
                    $stmt = $pdo->prepare(
                        "UPDATE orders
                         SET status = 'Cancelled',
                             payment_status = 'Refunded',
                             refunded_at = CURRENT_TIMESTAMP
                         WHERE order_id = ?"
                    );
                } else {
                    // Older unpaid orders have no payment to refund.
                    $stmt = $pdo->prepare(
                        "UPDATE orders
                         SET status = 'Cancelled'
                         WHERE order_id = ?"
                    );
                }

                $stmt->execute([$orderId]);
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE orders
                     SET status = ?
                     WHERE order_id = ?'
                );

                $stmt->execute([$newStatus, $orderId]);
            }
        }

        $pdo->commit();

        header(
            'Location: view_order.php?id='
            . $orderId
            . '&success=updated'
        );
        exit;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $error = $exception instanceof PDOException
            ? 'Unable to update the order. Please try again.'
            : $exception->getMessage();
    }
}

// Load the latest order details.
$stmt = $pdo->prepare(
    'SELECT *
     FROM orders
     WHERE order_id = ?'
);

$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

// Load order items.
$stmt = $pdo->prepare(
    'SELECT *
     FROM order_items
     WHERE order_id = ?
     ORDER BY order_item_id ASC'
);

$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();

$validStatuses = array_merge(
    [$order['status']],
    $statusTransitions[$order['status']] ?? []
);

$isFinal = in_array(
    $order['status'],
    ['Completed', 'Cancelled'],
    true
);

$orderReference = 'ORD-' . str_pad(
    (string) $order['order_id'],
    5,
    '0',
    STR_PAD_LEFT
);

$customisationFields = [
    'drink_size'     => 'Size',
    'milk_type'      => 'Milk',
    'add_ins'        => 'Add-ins',
    'espresso_shots' => 'Espresso shots',
    'syrup_pumps'    => 'Syrup pumps',
    'topping'       => 'Topping',
    'barista_note'   => 'Barista note'
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>View Order | KopiTarikBn</title>

    <link rel="stylesheet" href="../assets/admin.css">
</head>

<body class="admin-body">

<div class="admin-layout dashboard-layout admin-theme">

    <?php include __DIR__ . '/include/sidebar.php'; ?>

    <main class="admin-main">

        <!-- PAGE HEADER -->
        <div class="admin-page-header">
            <div>
                <h1>Order <?= e($orderReference) ?></h1>
                <p>View order details, payment and preparation status.</p>
            </div>

            <a href="orders.php" class="btn-secondary">
                ← Back to Orders
            </a>
        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if (($_GET['success'] ?? '') === 'updated'): ?>
            <div class="alert-success" role="status">
                Order updated successfully.

                <?php if ($order['status'] === 'Cancelled'): ?>
                    Stock restoration has been processed.

                    <?php if ($order['payment_status'] === 'Refunded'): ?>
                        The demo payment has been marked as refunded.
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if ($error !== ''): ?>
            <div class="alert-error" role="alert">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <div class="order-details-grid">

            <!-- CUSTOMER INFORMATION -->
            <section class="order-info-card">
                <h2>Customer Information</h2>

                <div class="order-info-row">
                    <span>Name</span>
                    <strong><?= e($order['customer_name']) ?></strong>
                </div>

                <div class="order-info-row">
                    <span>Email</span>
                    <strong><?= e($order['customer_email']) ?></strong>
                </div>

                <div class="order-info-row">
                    <span>Phone</span>
                    <strong><?= e($order['customer_phone']) ?></strong>
                </div>

                <div class="order-info-row">
                    <span>Customer Type</span>
                    <strong>
                        <span class="customer-type registered">
                            Registered
                        </span>
                    </strong>
                </div>
            </section>

            <!-- ORDER INFORMATION -->
            <section class="order-info-card">
                <h2>Order Information</h2>

                <div class="order-info-row">
                    <span>Order ID</span>
                    <strong><?= e($orderReference) ?></strong>
                </div>

                <div class="order-info-row">
                    <span>Date</span>
                    <strong>
                        <?= e(date(
                            'd M Y, h:i A',
                            strtotime($order['order_date'])
                        )) ?>
                    </strong>
                </div>

                <div class="order-info-row">
                    <span>Total</span>
                    <strong>
                        BND <?= number_format(
                            (float) $order['total_amount'],
                            2
                        ) ?>
                    </strong>
                </div>

                <div class="order-info-row">
                    <span>Order Status</span>
                    <strong>
                        <span
                            class="status status-<?= e(
                                strtolower($order['status'])
                            ) ?>"
                        >
                            <?= e($order['status']) ?>
                        </span>
                    </strong>
                </div>
            </section>

            <!-- PAYMENT INFORMATION -->
            <section class="order-info-card">
                <h2>Payment Information</h2>

                <div class="order-info-row">
                    <span>Payment Status</span>
                    <strong><?= e($order['payment_status']) ?></strong>
                </div>

                <div class="order-info-row">
                    <span>Payment Method</span>
                    <strong>
                        <?= $order['payment_method'] === 'Demo'
                            ? 'Credit Card'
                            : 'No payment recorded' ?>
                    </strong>
                </div>

                <?php if (!empty($order['paid_at'])): ?>
                    <div class="order-info-row">
                        <span>Payment Recorded</span>
                        <strong>
                            <?= e(date(
                                'd M Y, h:i A',
                                strtotime($order['paid_at'])
                            )) ?>
                        </strong>
                    </div>
                <?php endif; ?>

                <?php if (!empty($order['refunded_at'])): ?>
                    <div class="order-info-row">
                        <span>Refund Recorded</span>
                        <strong>
                            <?= e(date(
                                'd M Y, h:i A',
                                strtotime($order['refunded_at'])
                            )) ?>
                        </strong>
                    </div>
                <?php endif; ?>
            </section>

        </div>

        <!-- ORDER ITEMS -->
        <section class="admin-section order-items-section">
            <div class="section-header">
                <h2>Order Items</h2>
                <span><?= count($orderItems) ?> item line(s)</span>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($orderItems): ?>
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?= e($item['menu_name']) ?>
                                        </strong>

                                        <dl class="order-customisations">
                                            <?php
                                            foreach (
                                                $customisationFields
                                                as $key => $label
                                            ):
                                                $value = trim(
                                                    (string) ($item[$key] ?? '')
                                                );

                                                if ($value === '') {
                                                    continue;
                                                }
                                            ?>
                                                <dt><?= e($label) ?></dt>
                                                <dd><?= nl2br(e($value)) ?></dd>
                                            <?php endforeach; ?>
                                        </dl>
                                    </td>

                                    <td>
                                        BND <?= number_format(
                                            (float) $item['unit_price'],
                                            2
                                        ) ?>
                                    </td>

                                    <td><?= (int) $item['quantity'] ?></td>

                                    <td>
                                        BND <?= number_format(
                                            (float) $item['subtotal'],
                                            2
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="empty-table">
                                    No items found for this order.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <?php if ($orderItems): ?>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <strong>Order Total</strong>
                                </td>
                                <td>
                                    <strong>
                                        BND <?= number_format(
                                            (float) $order['total_amount'],
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
            <h2>Update Order Status</h2>

            <?php if ($isFinal): ?>
                <p>
                    This order is
                    <strong><?= e($order['status']) ?></strong>
                    and cannot be changed.
                </p>
            <?php else: ?>
                <p>
                    Normal order flow:
                    Pending → Preparing → Ready → Completed.
                </p>

                <?php if ($order['status'] === 'Pending'): ?>
                    <p>
                        Cancelling restores stock.
                        Paid demo orders are also marked as Refunded.
                    </p>
                <?php else: ?>
                    <p>
                        This order can no longer be cancelled because
                        preparation has started.
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <form
                method="POST"
                class="status-form"
                id="order-status-form"
            >
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="status">Order Status</label>

                    <select
                        name="status"
                        id="status"
                        required
                        <?= $isFinal ? 'disabled' : '' ?>
                    >
                        <?php foreach ($validStatuses as $status): ?>
                            <option
                                value="<?= e($status) ?>"
                                <?= $order['status'] === $status
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button
                    type="submit"
                    class="btn-primary"
                    <?= $isFinal ? 'disabled' : '' ?>
                >
                    Update Status
                </button>
            </form>

            <p>
                Restored stock keeps the product’s availability setting.
                If necessary, enable the product through Edit Product.
            </p>
        </section>

    </main>

</div>

<script>
    const statusForm = document.getElementById('order-status-form');
    const statusSelect = document.getElementById('status');

    statusForm.addEventListener('submit', event => {
        if (statusSelect.value !== 'Cancelled') {
            return;
        }

        const confirmed = window.confirm(
            'Cancel this order? Stock will be restored. ' +
            'Any paid demo payment will be marked as refunded. ' +
            'This cancellation cannot be reversed.'
        );

        if (!confirmed) {
            event.preventDefault();
        }
    });
</script>

</body>
</html>