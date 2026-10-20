<?php
require __DIR__ . '/config/app.php';
if (logged()) go('orders.php');
$title='Register';$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $name=trim($_POST['name'] ?? '');$email=trim($_POST['email'] ?? '');$phone=trim($_POST['phone'] ?? '');$pass=$_POST['password'] ?? '';
    if (mb_strlen($name)<2 || mb_strlen($name)>100 || strlen($email)>100 || !filter_var($email,FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9+() -]{7,20}$/D',$phone) || strlen($pass)<8 || strlen($pass)>72) {
        $err='Enter a name, valid email and phone, and a password of 8 to 72 bytes.';
    } else {
        $st=$pdo->prepare('SELECT customer_id FROM customer WHERE email=?');$st->execute([$email]);
        if ($st->fetchColumn()) $err='This email is already registered.';
        else try {
            $st=$pdo->prepare('INSERT INTO customer (name,email,phone,password) VALUES (?,?,?,?)');
            $st->execute([$name,$email,$phone,password_hash($pass,PASSWORD_DEFAULT)]);
            $id=(int)$pdo->lastInsertId();
            session_regenerate_id(true);unset($_SESSION['admin_id'],$_SESSION['admin_username'],$_SESSION['last_receipt']);
            $_SESSION['csrf_token']=bin2hex(random_bytes(32));$_SESSION['customer_id']=$id;$_SESSION['customer_name']=$name;
            go('menu.php');
        } catch (PDOException $e) {
            $err=$e->getCode()==='23000' ? 'This email is already registered.' : 'Unable to create an account. Please try again.';
        }
    }
}
include __DIR__ . '/includes/header.php';

?>


<!-- 3. PAGE HEADER -->

<div class="pagehead">

    <div class="wrap">

        <h1>
            Create Account
        </h1>

    </div>

</div>


<!-- 4. CUSTOMER REGISTRATION FORM -->

<section class="section wrap">

    <form class="form-card" method="post">


        <!-- REGISTRATION FORM HEADING -->

        <h2>
            Join KopiTarikBn
        </h2>


        <!-- ERROR MESSAGE -->

        <?php if ($err): ?>

            <p class="error">
                <?= h($err) ?>
            </p>

        <?php endif; ?>


        <!-- CSRF TOKEN -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= csrf() ?>"
        >


        <!-- FULL NAME -->

        <label>
            Full name
        </label>

        <input
            name="name"
            required
            maxlength="100"
            value="<?= h($_POST['name'] ?? '') ?>"
        >


        <!-- EMAIL ADDRESS -->

        <label>
            Email
        </label>

        <input
            type="email"
            name="email"
            required
            maxlength="100"
            value="<?= h($_POST['email'] ?? '') ?>"
        >


        <!-- PHONE NUMBER -->

        <label>
            Phone number
        </label>

        <input
            name="phone"
            required
            maxlength="20"
            value="<?= h($_POST['phone'] ?? '') ?>"
        >


        <!-- PASSWORD -->

        <label>
            Password (8–72 bytes)
        </label>

        <input
            type="password"
            name="password"
            required
            minlength="8"
            maxlength="72"
        >


        <!-- REGISTER BUTTON -->

        <p>

            <button class="btn dark">
                Register
            </button>

        </p>


        <!-- LOGIN LINK -->

        <p>
            Already registered?

            <a href="login.php">
                <u>Sign in</u>
            </a>
        </p>

    </form>

</section>


<!-- 5. FOOTER -->

<?php include __DIR__ . '/includes/footer.php'; ?>
