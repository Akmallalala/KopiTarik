<?php
require __DIR__ . '/config/app.php';

// Only registered customers can access checkout.
if (!logged()) {
    flash('Please sign in to complete your order.');
    go('login.php?redirect=checkout.php');
}

$title='Checkout';$err='';$success=false;$orderId=0;$orderTotal=0;$discount=0;$code='';$preview=[];
if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['placed'],$_SESSION['last_receipt'])) {
    $receipt=$_SESSION['last_receipt'];$success=true;$orderId=$receipt['id'];$orderTotal=$receipt['total'];$discount=$receipt['discount'];
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $code = strtoupper(trim($_POST['promo'] ?? ''));
    if (empty($_SESSION['cart'])) $err='Your cart is empty.';
    
    elseif ($code!=='' && $code!=='BREW10') $err='This promotional code is not valid.';
    else try {
        $pdo->beginTransaction();
        
        $rows = cart_rows(true);
$cid = (int) $_SESSION['customer_id'];

// Always use the registered customer's database details.
$st = $pdo->prepare(
    'SELECT name, email, phone
     FROM customer
     WHERE customer_id = ?'
);
$st->execute([$cid]);
$customer = $st->fetch();

if (!$customer) {
    throw new RuntimeException(
        'Your account could not be found. Please sign out and sign in again.'
    );
}

$name = $customer['name'];
$email = $customer['email'];
$phone = $customer['phone'];

        $gross=0;$net=0;
        foreach ($rows as &$line) {
            $gross+=$line['unit_cents']*$line['quantity'];
            $line['charged_cents']=$code==='BREW10' ? intdiv($line['unit_cents']*90+50,100) : $line['unit_cents'];
            $net+=$line['charged_cents']*$line['quantity'];
        } unset($line);
        $orderTotal=$net/100;$discount=($gross-$net)/100;
        $st=$pdo->prepare("INSERT INTO orders (customer_id,customer_name,customer_email,customer_phone,total_amount,status) VALUES (?,?,?,?,?,'Pending')");
        $st->execute([$cid,$name,$email,$phone,$orderTotal]);$orderId=(int)$pdo->lastInsertId();
        $insert=$pdo->prepare('INSERT INTO order_items (order_id,menu_id,menu_name,quantity,unit_price,subtotal,drink_size,milk_type,add_ins,espresso_shots,syrup_pumps,topping,barista_note) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $counts=[];
        foreach ($rows as $line) {
            $c=$line['customization'];$unit=$line['charged_cents']/100;
            $insert->execute([$orderId,$line['menu_id'],trim($line['menu_name']),$line['quantity'],$unit,$line['charged_cents']*$line['quantity']/100,$c['size'],$c['milk'],$c['extra'],$c['shots'],$c['pumps'],$c['topping'],$c['note']]);
            $counts[$line['menu_id']]=($counts[$line['menu_id']] ?? 0)+$line['quantity'];
        }
        foreach ($counts as $id=>$qty) {
            $st=$pdo->prepare('UPDATE menu_item SET availability=CASE WHEN stock_quantity-?=0 THEN 0 ELSE availability END, stock_quantity=stock_quantity-? WHERE menu_id=? AND availability=1 AND stock_quantity>=?');
            $st->execute([$qty,$qty,$id,$qty]);
            if ($st->rowCount()!==1) throw new RuntimeException('Stock changed. Please update your cart.');
        }
        $pdo->commit();$_SESSION['cart']=[];unset($_SESSION['custom']);
        $_SESSION['last_receipt']=['id'=>$orderId,'total'=>$orderTotal,'discount'=>$discount];
        go('checkout.php?placed=1');
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $err=$e instanceof PDOException ? 'Unable to place your order. Please try again.' : $e->getMessage();
    }
}
if (!$success && !empty($_SESSION['cart'])) { try { $preview=cart_rows(); } catch (RuntimeException $e) { if (!$err) $err=$e->getMessage(); } }
include __DIR__ . '/includes/header.php';

?>


<!-- 10. PAGE HEADER -->

<div class="pagehead">

    <div class="wrap">

        <h1>
            Checkout
        </h1>

    </div>

</div>


<!-- 11. CHECKOUT SECTION -->

<section class="section wrap">

    <div class="form-card">


        <!-- ORDER SUCCESS MESSAGE -->

        <?php if ($success): ?>

            <h2>
                Order placed!
            </h2>

            <p>
                Your order
                <strong>#<?= $orderId ?></strong>
                has been recorded as Pending.
            </p>


            <!-- ORDER TOTAL -->
            <p>

                Total:
                <strong>
                    <?= money($orderTotal) ?>
                </strong>

                <?php if ($discount): ?>

                    (including <?= money($discount) ?> discount)

                <?php endif; ?>

            </p>


            <!-- PAYMENT INFORMATION -->
            <p>
                Please keep your order number for reference.
                Payment is not processed online.
            </p>


            <!-- BACK TO MENU -->
            <a class="btn dark" href="menu.php">
                Back to menu
            </a>


        <!-- CHECKOUT FORM -->

        <?php else: ?>

            <h2>
                Confirm your order
            </h2>


            <!-- ERROR MESSAGE -->
            <?php if ($err): ?>

                <p class="error" role="alert">
                    <?= h($err) ?>
                </p>

            <?php endif; ?>


            <!-- CHECKOUT INFORMATION -->
            <p>
    Your pickup order will use the contact details saved in your account.
    No online payment is collected.
</p>


            <!-- 12. CUSTOMER LOGIN STATUS -->

            <p>
    Signed in as
    <strong>
        <?= h($_SESSION['customer_name'] ?? 'Customer') ?>
    </strong>.
</p>


            <!-- 13. CHECKOUT FORM FIELDS -->

            <?php if ($preview): ?><div class="checkout-summary"><h3>Order summary</h3><?php foreach ($preview as $line): ?><p><?= (int)$line['quantity'] ?> × <?= h(trim($line['menu_name'])) ?> (<?= h($line['customization']['size']) ?>, <?= h($line['customization']['milk']) ?>) — <?= money($line['subtotal']) ?></p><?php endforeach; ?><p><strong>Before any promo: <?= money(array_sum(array_column($preview,'subtotal'))) ?></strong></p></div><?php endif; ?>
            <form method="post">

                <!-- CSRF Token -->
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= csrf() ?>"
                >


                


                <!-- PROMOTIONAL CODE -->

                <label for="promo">
                    Promo code (optional)
                </label>

                <input
                    id="promo"
                    name="promo"
                    value="<?= h($_POST['promo'] ?? '') ?>"
                    placeholder="BREW10"
                >


                <!-- PROMOTIONAL INFORMATION -->
                <p class="small">
                    BREW10 gives 10% off each drink.
                    The discount is applied to each drink, rounded to the nearest cent.
                </p>


                <!-- PLACE ORDER BUTTON -->

                <button
                    class="btn dark"
                    <?= empty($_SESSION['cart']) ? 'disabled' : '' ?>
                >
                    Place pickup order
                </button>

            </form>

        <?php endif; ?>

    </div>

</section>


<!-- 14. FOOTER -->

<?php include __DIR__ . '/includes/footer.php'; ?>
