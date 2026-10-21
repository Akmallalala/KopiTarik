<?php
require __DIR__ . '/config/app.php';

if (!logged()) {
    flash('Please sign in to complete your demo payment.');
    go('login.php?redirect=checkout.php');
}

$title = 'Demo Checkout';
$error = '';
$code = '';
$rows = [];
$receipt = null;
$customerId = (int) $_SESSION['customer_id'];

if (empty($_SESSION['checkout_token'])) {
    $_SESSION['checkout_token'] = bin2hex(random_bytes(32));
}

/*
 * Display a receipt only when it belongs to the signed-in customer.
 * Read the current payment status from the database.
 */
$receiptId = filter_var(
    $_GET['order'] ?? null,
    FILTER_VALIDATE_INT
);

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $receiptId > 0) {
    $stmt = $pdo->prepare(
        'SELECT order_id, total_amount, status,
                payment_status, payment_method, paid_at
         FROM orders
         WHERE order_id = ? AND customer_id = ?'
    );
    $stmt->execute([$receiptId, $customerId]);
    $receipt = $stmt->fetch();

    if (!$receipt) {
        $error = 'Order not found.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $code = strtoupper(trim($_POST['promo'] ?? ''));
    $token = $_POST['checkout_token'] ?? '';

    try {
        if (!preg_match('/^[a-f0-9]{64}$/D', $token)) {
            throw new RuntimeException(
                'Invalid checkout. Reload this page and try again.'
            );
        }

        /*
         * A repeated submission returns the existing receipt.
         * It does not create another order or deduct stock again.
         */
        $stmt = $pdo->prepare(
            'SELECT order_id
             FROM orders
             WHERE checkout_token = ? AND customer_id = ?'
        );
        $stmt->execute([$token, $customerId]);
        $existingOrderId = $stmt->fetchColumn();

        if ($existingOrderId) {
            go('checkout.php?order=' . (int) $existingOrderId);
        }

        if (!hash_equals($_SESSION['checkout_token'], $token)) {
            throw new RuntimeException(
                'This checkout has expired. Reload the page.'
            );
        }

        if (empty($_SESSION['cart'])) {
            throw new RuntimeException('Your cart is empty.');
        }

        if ($code !== '' && $code !== 'BREW10') {
            throw new RuntimeException(
                'This promotional code is not valid.'
            );
        }

        $pdo->beginTransaction();

        // Recheck prices and lock stock before creating the order.
        $rows = cart_rows(true);

        $stmt = $pdo->prepare(
            'SELECT name, email, phone
             FROM customer
             WHERE customer_id = ?'
        );
        $stmt->execute([$customerId]);
        $customer = $stmt->fetch();

        if (!$customer) {
            throw new RuntimeException(
                'Your account could not be found. Please sign in again.'
            );
        }

        $totalCents = 0;

        foreach ($rows as &$line) {
            $line['charged_cents'] = $code === 'BREW10'
                ? intdiv($line['unit_cents'] * 90 + 50, 100)
                : $line['unit_cents'];

            $totalCents +=
                $line['charged_cents'] * $line['quantity'];
        }
        unset($line);

        if ($totalCents > 9999999999) {
            throw new RuntimeException(
                'This order exceeds the supported total.'
            );
        }

        /*
         * This is explicitly a simulation.
         * No card details or payment provider are involved.
         */
        $stmt = $pdo->prepare(
            "INSERT INTO orders (
                customer_id,
                customer_name,
                customer_email,
                customer_phone,
                total_amount,
                status,
                payment_status,
                payment_method,
                paid_at,
                checkout_token
            ) VALUES (
                ?, ?, ?, ?, ?,
                'Pending', 'Paid', 'Demo',
                CURRENT_TIMESTAMP, ?
            )"
        );

        $stmt->execute([
            $customerId,
            $customer['name'],
            $customer['email'],
            $customer['phone'],
            number_format($totalCents / 100, 2, '.', ''),
            $token
        ]);

        $orderId = (int) $pdo->lastInsertId();

        $insertItem = $pdo->prepare(
            'INSERT INTO order_items (
                order_id,
                menu_id,
                menu_name,
                quantity,
                unit_price,
                subtotal,
                drink_size,
                milk_type,
                add_ins,
                espresso_shots,
                syrup_pumps,
                topping,
                barista_note
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $quantities = [];

        foreach ($rows as $line) {
            $custom = $line['customization'];

            $insertItem->execute([
                $orderId,
                $line['menu_id'],
                trim($line['menu_name']),
                $line['quantity'],
                number_format(
                    $line['charged_cents'] / 100,
                    2,
                    '.',
                    ''
                ),
                number_format(
                    $line['charged_cents'] * $line['quantity'] / 100,
                    2,
                    '.',
                    ''
                ),
                $custom['size'],
                $custom['milk'],
                $custom['extra'],
                $custom['shots'],
                $custom['pumps'],
                $custom['topping'],
                $custom['note']
            ]);

            $menuId = (int) $line['menu_id'];

            $quantities[$menuId] =
                ($quantities[$menuId] ?? 0) + $line['quantity'];
        }

        $deductStock = $pdo->prepare(
            'UPDATE menu_item
             SET availability =
                    CASE
                        WHEN stock_quantity - ? = 0 THEN 0
                        ELSE availability
                    END,
                 stock_quantity = stock_quantity - ?
             WHERE menu_id = ?
               AND availability = 1
               AND stock_quantity >= ?'
        );

        foreach ($quantities as $menuId => $quantity) {
            $deductStock->execute([
                $quantity,
                $quantity,
                $menuId,
                $quantity
            ]);

            if ($deductStock->rowCount() !== 1) {
                throw new RuntimeException(
                    'Stock changed. Please update your cart.'
                );
            }
        }

        $pdo->commit();

        $_SESSION['cart'] = [];

        unset(
            $_SESSION['checkout_token'],
            $_SESSION['custom'],
            $_SESSION['last_receipt']
        );

        go('checkout.php?order=' . $orderId);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $error = $e instanceof PDOException
            ? 'Unable to complete demo checkout. Please try again.'
            : $e->getMessage();
    }
}

$rows = [];
$subtotalCents = 0;

if (!$receipt && !empty($_SESSION['cart'])) {
    try {
        $rows = cart_rows();

        foreach ($rows as $line) {
            $subtotalCents +=
                $line['unit_cents'] * $line['quantity'];
        }
    } catch (RuntimeException $e) {
        if ($error === '') {
            $error = $e->getMessage();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="pagehead">
    <div class="wrap">
        <h1>Demo Checkout</h1>
    </div>
</div>

<section class="section wrap">
    <div class="form-card">

        <?php if ($error !== ''): ?>
            <p class="error" role="alert">
                <?= h($error) ?>
            </p>
        <?php endif; ?>

        <?php if ($receipt): ?>

            <h2>Order receipt</h2>

            <p>
                Order:
                <strong>#<?= (int) $receipt['order_id'] ?></strong>
            </p>

            <p>
                Total:
                <strong><?= money($receipt['total_amount']) ?></strong>
            </p>

            <p>
                Payment:
                <strong>
                    <?= h($receipt['payment_status']) ?>
                </strong>
                — <?= h($receipt['payment_method']) ?>
            </p>

            <p>
                Order status:
                <strong><?= h($receipt['status']) ?></strong>
            </p>

            <p class="small">
                Demo payments and refunds are simulated.
                No real money is charged or returned.
            </p>

            <p>
                <a class="btn dark" href="orders.php">
                    View My Orders
                </a>
            </p>

            <a href="menu.php">Back to menu</a>

        <?php elseif (!$rows): ?>

            <h2>No drinks ready for checkout</h2>
            <p>Check your cart before continuing.</p>
            <a class="btn dark" href="cart.php">Return to cart</a>

        <?php else: ?>

            <h2>Review your pickup order</h2>

            <p>
                Signed in as
                <strong>
                    <?= h($_SESSION['customer_name'] ?? 'Customer') ?>
                </strong>.
            </p>

            <div class="checkout-summary">
                <?php foreach ($rows as $line): ?>
                    <p>
                        <?= (int) $line['quantity'] ?> ×
                        <?= h(trim($line['menu_name'])) ?>

                        (<?= h($line['customization']['size']) ?>,
                        <?= h($line['customization']['milk']) ?>)

                        — <?= money($line['subtotal']) ?>
                    </p>
                <?php endforeach; ?>

                <p>
                    Subtotal:
                    <strong><?= money($subtotalCents / 100) ?></strong>
                </p>
            </div>

            <form method="post" id="demo-checkout-form">
                <?= csrf_field() ?>

                <input
                    type="hidden"
                    name="checkout_token"
                    value="<?= h($_SESSION['checkout_token']) ?>"
                >

                <label for="promo">Promo code (optional)</label>

                <input
                    id="promo"
                    name="promo"
                    value="<?= h($code) ?>"
                    placeholder="BREW10"
                >

                <p class="small">
                    BREW10 gives 10% off each drink,
                    rounded to the nearest cent per unit.
                </p>

                <p id="promo-message" class="small" role="status"></p>

                <p>
                    Demo payment total:
                    <strong id="demo-total">
                        <?= money($subtotalCents / 100) ?>
                    </strong>
                </p>

                <div class="panel">
                    <h3>Demo payment only</h3>
                    <p>
                        No actual charge will be made.
                    </p>
                    <p>
                        Simulating payment places your order
                        and sends it for preparation.
                    </p>
                </div>

                <p>
                    <button
                        type="submit"
                        class="btn dark"
                        id="demo-pay-button"
                    >
                        Pay
                    </button>
                </p>
            </form>

            <a href="cart.php">← Back to cart</a>

            <script>
                const checkoutLines = <?= json_encode(
                    array_map(
                        fn($line) => [
                            'cents' => (int) $line['unit_cents'],
                            'quantity' => (int) $line['quantity']
                        ],
                        $rows
                    )
                ) ?>;

                const demoForm =
                    document.getElementById('demo-checkout-form');
                const promoInput = document.getElementById('promo');
                const promoMessage =
                    document.getElementById('promo-message');
                const totalOutput =
                    document.getElementById('demo-total');
                const payButton =
                    document.getElementById('demo-pay-button');

                let submitting = false;

                function updateDemoTotal() {
                    const code = promoInput.value.trim().toUpperCase();
                    const valid = code === '' || code === 'BREW10';

                    const cents = checkoutLines.reduce((total, line) => {
                        const unit = code === 'BREW10'
                            ? Math.floor((line.cents * 90 + 50) / 100)
                            : line.cents;

                        return total + unit * line.quantity;
                    }, 0);

                    totalOutput.textContent =
                        'BND ' + (cents / 100).toFixed(2);

                    promoMessage.textContent = !valid
                        ? 'Invalid promo code. Use BREW10 or leave it empty.'
                        : code === 'BREW10'
                            ? '10% demo discount applied.'
                            : '';

                    payButton.disabled = submitting || !valid;
                    return valid;
                }

                promoInput.addEventListener('input', updateDemoTotal);

                demoForm.addEventListener('submit', event => {
                    if (submitting || !updateDemoTotal()) {
                        event.preventDefault();
                        return;
                    }

                    if (!window.confirm(
                        'Simulate payment and place this pickup order? ' +
                        'No real money will be charged.'
                    )) {
                        event.preventDefault();
                        return;
                    }

                    submitting = true;
                    payButton.disabled = true;
                    payButton.textContent = 'Processing demo payment…';
                });

                window.addEventListener('pageshow', () => {
                    submitting = false;
                    payButton.textContent = 'Simulate Successful Payment';
                    updateDemoTotal();
                });

                updateDemoTotal();
            </script>

        <?php endif; ?>

    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>