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

$orders = $st;


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
         5. ORDERS TABLE
    ===================================== -->

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
                            #<?= $o['order_id'] ?>
                        </td>


                        <!-- ORDER DATE -->
                        <td>
                            <?= h($o['order_date']) ?>
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
                                'SELECT menu_name, quantity, drink_size, milk_type, barista_note
                                 FROM order_items WHERE order_id = ? ORDER BY order_item_id'
                            );

                            $st2->execute([$o['order_id']]);

                            // Display each ordered item
                            foreach ($st2 as $it) {

                                echo h($it['menu_name'])
                                    . ' × '
                                    . (int) $it['quantity']
                                    . ' (' . h($it['drink_size']) . ', ' . h($it['milk_type']) . ')<br>';

                            }

                            ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</section>


<!-- =========================================
     6. FOOTER
========================================= -->

<?php include __DIR__ . '/includes/footer.php'; ?>
