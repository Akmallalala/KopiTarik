<?php
require __DIR__ . '/config/app.php';
$title='Your Cart'; $cartError='';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $action=$_POST['action'] ?? ''; $ok=true; $msg='Cart updated.';
    if ($action==='add') {
        $id=filter_var($_POST['menu_id'] ?? null,FILTER_VALIDATE_INT);
        $st=$pdo->prepare('SELECT * FROM menu_item WHERE menu_id=?');$st->execute([$id ?: 0]);$m=$st->fetch();
        if (!$m || !$m['availability'] || $m['stock_quantity'] < 1) { $ok=false;$msg='This drink is unavailable.'; }
        else {
            $custom=drink_customization($_POST,$m['menu_name']);
            $key=hash('sha256',json_encode([$id,$custom]));$count=0;
            foreach ($_SESSION['cart'] as $line) if ($line['menu_id']==$id) $count+=$line['quantity'];
            if ($count >= $m['stock_quantity'] || ($_SESSION['cart'][$key]['quantity'] ?? 0) >= 99) { $ok=false;$msg='Maximum available quantity reached.'; }
            else { $_SESSION['cart'][$key]=['menu_id'=>$id,'quantity'=>($_SESSION['cart'][$key]['quantity'] ?? 0)+1,'customization'=>$custom];$msg=trim($m['menu_name']).' added to your cart'; }
        }
    } elseif (in_array($action,['update','remove'],true)) {
        $key=$_POST['line_key'] ?? '';
        if (!isset($_SESSION['cart'][$key])) { $ok=false;$msg='Cart item not found.'; }
        elseif ($action==='remove') unset($_SESSION['cart'][$key]);
        else {
            $qty=filter_var($_POST['quantity'] ?? null,FILTER_VALIDATE_INT);
            if ($qty === false || $qty < 0 || $qty > 99) { $ok=false;$msg='Quantity must be between 0 and 99.'; }
            elseif ($qty===0) unset($_SESSION['cart'][$key]);
            else {
                $id=$_SESSION['cart'][$key]['menu_id'];$others=0;
                foreach ($_SESSION['cart'] as $k=>$line) if ($k!==$key && $line['menu_id']==$id) $others+=$line['quantity'];
                $st=$pdo->prepare('SELECT stock_quantity,availability FROM menu_item WHERE menu_id=?');$st->execute([$id]);$m=$st->fetch();
                if (!$m || !$m['availability'] || $qty+$others>$m['stock_quantity']) { $ok=false;$msg='Requested quantity exceeds available stock.'; }
                else $_SESSION['cart'][$key]['quantity']=$qty;
            }
        }
    } else { $ok=false;$msg='Invalid cart action.'; }
    if (($_POST['ajax'] ?? '')==='1') { header('Content-Type: application/json');echo json_encode(['ok'=>$ok,'message'=>$msg,'count'=>cart_count()]);exit; }
    flash($msg);go($action==='add' ? 'menu.php' : 'cart.php');
}
$rows=[];$total=0;
// Retain unavailable lines so customers can remove them after stock changes.
foreach ($_SESSION['cart'] as $key=>$line) {
    $st=$pdo->prepare('SELECT * FROM menu_item WHERE menu_id=?');$st->execute([$line['menu_id']]);$m=$st->fetch();
    if (!$m) { unset($_SESSION['cart'][$key]);continue; }
    $m['line_key']=$key;$m['quantity']=$line['quantity'];$m['customization']=drink_customization($line['customization'],$m['menu_name']);
    $m['unit_price']=drink_unit_cents($m['price'],$m['customization'])/100;$m['subtotal']=round($m['unit_price']*$m['quantity'],2);$total+=$m['subtotal'];
    if (!$m['availability'] || $m['stock_quantity'] < $m['quantity']) $cartError='Some drinks are unavailable or exceed stock. Update your cart before checkout.';
    $rows[]=$m;
}
try { cart_rows(); } catch (RuntimeException $e) { $cartError=$e->getMessage(); }
if ($cartError) flash($cartError);
include __DIR__ . '/includes/header.php';

?>


<!-- PAGE HEADER -->

<div class="pagehead">

    <div class="wrap">

        <h1>
            Your Cart
        </h1>

    </div>

</div>


<!-- SHOPPING CART SECTION -->

<section class="section wrap">


    <!-- EMPTY CART MESSAGE -->

    <?php if (!$rows): ?>

        <div class="panel center">

            <h2>
                Your cart is empty
            </h2>

            <a class="btn dark" href="menu.php">
                Browse menu
            </a>

        </div>


    <!-- CART WITH ITEMS -->

    <?php else: ?>

        <div class="cart-layout">


            <!-- CART ITEMS -->

            <div class="cart-items">

                <?php foreach ($rows as $m): ?>

                    <?php
                        $c = $m['customization'];
                    ?>

                    <article class="cart-line">


                        <!-- DRINK IMAGE -->
                        <img
                            src="<?= h(item_picture($m)) ?>"
                            alt="<?= h($m['menu_name']) ?>"
                        >


                        <!-- DRINK INFORMATION -->
                        <div class="cart-info">

                            <h3>
                                <?= h($m['menu_name']) ?>
                            </h3>


                            <!-- DRINK CUSTOMIZATION -->
                            <p>
                                <?= h(
                                    implode(
                                        ' · ',
                                        array_filter([
                                            $c['size'] ?? 'Medium',
                                            $c['milk'] ?? null,
                                            !empty($c['note']) ? 'Note: ' . $c['note'] : null,

                                            isset($c['shots'])
                                                ? $c['shots'] . ' shots'
                                                : null,

                                            isset($c['pumps'])
                                                ? $c['pumps'] . ' pumps'
                                                : null,

                                            ($c['extra'] ?? 'None') !== 'None'
                                                ? $c['extra']
                                                : null,

                                            ($c['topping'] ?? 'None') !== 'None'
                                                ? $c['topping']
                                                : null
                                        ], function ($value) {
                                            return $value !== null
                                                && $value !== '';
                                        })
                                    )
                                ) ?>
                            </p>


                            <!-- UNIT PRICE -->
                            <small>
                                <?= money($m['unit_price']) ?> each
                            </small>


                            <!-- UPDATE QUANTITY -->

                            <form class="qty-form" method="post">

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= csrf() ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="update"
                                >

                                <input
                                    type="hidden"
                                    name="line_key"
                                    value="<?= h($m['line_key']) ?>"
                                >


                                <!-- Decrease Quantity -->
                                <button
                                    type="button"
                                    class="qty-change"
                                    data-step="-1"
                                >
                                    −
                                </button>


                                <!-- Quantity Input -->
                                <input
                                    type="number"
                                    name="quantity"
                                    min="0"
                                    max="99"
                                    value="<?= (int) $m['quantity'] ?>"
                                    aria-label="Quantity"
                                >


                                <!-- Increase Quantity -->
                                <button
                                    type="button"
                                    class="qty-change"
                                    data-step="1"
                                >
                                    +
                                </button>


                                <!-- Update Button -->
                                <button class="qty-save" type="submit">
                                    Update
                                </button>

                            </form>

                        </div>


                        <!-- SUBTOTAL AND REMOVE -->

                        <div class="cart-line-end">

                            <!-- Item Subtotal -->
                            <b>
                                <?= money($m['subtotal']) ?>
                            </b>


                            <!-- Remove Item Form -->
                            <form method="post">

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= csrf() ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="remove"
                                >

                                <input
                                    type="hidden"
                                    name="line_key"
                                    value="<?= h($m['line_key']) ?>"
                                >

                                <button
                                    class="text-button"
                                    type="submit"
                                >
                                    Remove
                                </button>

                            </form>

                        </div>

                    </article>

                <?php endforeach; ?>


                <!-- CONTINUE SHOPPING -->
                <a
                    href="menu.php"
                    class="continue-shopping"
                >
                    ← Continue choosing drinks
                </a>

            </div>


            <!-- PICKUP ORDER SUMMARY -->

            <aside class="pickup-summary">

                <h2>
                    Pickup Order
                </h2>


                <!-- PICKUP TIME -->

                <label for="pickup-time">
                    Pickup time
                </label>

                <select id="pickup-time">

                    <option>As soon as possible</option>
                    <option>In 15 minutes</option>
                    <option>In 30 minutes</option>
                    <option>In 1 hour</option>

                </select>


                <!-- ORDER SUBTOTAL -->

                <div class="summary-row">

                    <span>
                        Subtotal
                    </span>

                    <span>
                        <?= money($total) ?>
                    </span>

                </div>


                <!-- ORDER TOTAL -->

                <div class="summary-row total">

                    <strong>
                        Total
                    </strong>

                    <strong>
                        <?= money($total) ?>
                    </strong>

                </div>


                
                <!-- CHECKOUT OPTIONS -->

                <div class="checkout-options">

                    <?php if (isset($_SESSION['customer_id'])): ?>

                        <!-- Logged-in Customer -->
                        <a class="btn checkout-btn"
                        href="checkout.php">
                            Place Pickup Order →
                        </a>

                    <?php else: ?>

                        <!-- Customer Login -->
                        <a class="btn login-btn"
                        href="login.php?redirect=checkout.php">
                            Login to Continue →
                        </a>

                    <?php endif; ?>

                </div>



            </aside>

        </div>

    <?php endif; ?>

</section>


<!-- =========================================
     10. FOOTER
========================================= -->

<?php include __DIR__ . '/includes/footer.php'; ?>
