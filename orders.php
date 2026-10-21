<?php

require __DIR__ . '/config/app.php';

// Ensure the customer is logged in
require_user();

// Set page title
$title = 'My Orders';

// Get logged-in customer ID
$cid = $_SESSION['customer_id'];


// =========================================
// 1. FETCH CUSTOMER ORDERS
// =========================================

$st = $pdo->prepare(
    'SELECT *
     FROM orders
     WHERE customer_id = ?
     ORDER BY order_date DESC'
);

$st->execute([$cid]);

$orders = $st->fetchAll(PDO::FETCH_ASSOC);


// =========================================
// 2. INCLUDE HEADER
// =========================================

include __DIR__ . '/includes/header.php';

?>


<!-- =========================================
     3. PAGE HEADER
========================================= -->

<div class="pagehead">

    <div class="wrap">

        <h1>
            My Orders
        </h1>

    </div>

</div>


<!-- =========================================
     4. CUSTOMER ORDERS SECTION
========================================= -->

<section class="section wrap">


    <!-- CUSTOMER WELCOME MESSAGE -->
    <p>
        Welcome, <?= h($_SESSION['customer_name']) ?>.

        <a href="reviews.php">
            <u>Write a review</u>
        </a>
    </p>


    <!-- =====================================
         5. ORDERS TABLE / EMPTY STATE
    ===================================== -->

    <?php if (empty($orders)): ?>

        <p>You have not placed any orders yet.</p>
        <p><a href="menu.php"><u>Browse drinks to place your first order</u></a></p>

    <?php else: ?>

        <p>You have <?= count($orders) ?> order<?= count($orders) === 1 ? '' : 's' ?> in your history.</p>

    <div class="table-scroll">

        <table>

            <!-- TABLE HEADER -->
            <thead>

                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Items</th>
                </tr>

            </thead>


            <!-- TABLE BODY -->
            <tbody>

                <?php foreach ($orders as $o): ?>

                    <tr>

                        <!-- ORDER ID -->
                        <td>
                            #<?= (int) $o['order_id'] ?>
                        </td>


                        <!-- ORDER DATE -->
                        <td>
                            <?= h(date('d M Y, h:i A', strtotime($o['order_date']))) ?>
                        </td>


                        <!-- TOTAL AMOUNT -->
                        <td>
                            <?= money($o['total_amount']) ?>
                        </td>


                        <!-- ORDER STATUS -->
                        <td>
                            <?= h($o['status']) ?>
                        </td>


                        <!-- ORDER ITEMS -->
                        <td>

                            <?php

                            // Fetch items belonging to this order
                            $st2 = $pdo->prepare(
                                'SELECT menu_name, quantity, drink_size, milk_type, add_ins,
                                        espresso_shots, syrup_pumps, topping, barista_note
                                 FROM order_items WHERE order_id = ? ORDER BY order_item_id'
                            );

                            $st2->execute([$o['order_id']]);

                            // Show the drink customisations saved when the order was placed.
                            foreach ($st2 as $it) {
                                echo '<div style="margin-bottom: 10px;">';
                                echo '<strong>' . h($it['menu_name']) . '</strong> × ' . (int) $it['quantity'];

                                $details = [];
                                foreach ([
                                    'drink_size' => 'Size',
                                    'milk_type' => 'Milk',
                                    'add_ins' => 'Add-ins',
                                    'espresso_shots' => 'Espresso shots',
                                    'syrup_pumps' => 'Syrup pumps',
                                    'topping' => 'Topping'
                                ] as $field => $label) {
                                    $value = trim((string) ($it[$field] ?? ''));
                                    if ($value !== '' && strcasecmp($value, 'None') !== 0) {
                                        $details[] = h($label) . ': ' . h($value);
                                    }
                                }

                                if ($details) {
                                    echo '<div><small>' . implode(' · ', $details) . '</small></div>';
                                }

                                $note = trim((string) ($it['barista_note'] ?? ''));
                                if ($note !== '') {
                                    echo '<div><small>Barista note: ' . nl2br(h($note)) . '</small></div>';
                                }
                                echo '</div>';
                            }

                            ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

    <?php endif; ?>

</section>


<!-- =========================================
     6. FOOTER
========================================= -->

<?php include __DIR__ . '/includes/footer.php'; ?>
