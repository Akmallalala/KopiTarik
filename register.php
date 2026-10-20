<?php
require __DIR__ . '/config/app.php';
if (logged()) go('orders.php');
$title='Register';$err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $name=trim($_POST['name'] ?? '');$email=trim($_POST['email'] ?? '');$phone=trim($_POST['phone'] ?? '');$pass=$_POST['password'] ?? '';
    $confirm_pass=$_POST['confirm_password'] ?? '';
    // Check each field separately so users know exactly what to correct.
    if (mb_strlen($name)<2 || mb_strlen($name)>100) {
        $err='Full name must be between 2 and 100 characters.';
    } elseif (strlen($email)>100 || !filter_var($email,FILTER_VALIDATE_EMAIL)) {
        $err='Please enter a valid email address (up to 100 characters).';
    } elseif (!preg_match('/^[0-9+() -]{7,20}$/D',$phone)) {
        $err='Please enter a valid phone number (7 to 20 characters).';
    } elseif (strlen($pass)<8 || strlen($pass)>72) {
        $err='Password must be between 8 and 72 bytes.';
    } elseif ($pass !== $confirm_pass) {
        $err='Passwords do not match. Please try again.';
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

            <p class="error" role="alert">
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

        <label for="register-name">
            Full name
        </label>

        <input
            id="register-name"
            name="name"
            autocomplete="name"
            required
            maxlength="100"
            value="<?= h($_POST['name'] ?? '') ?>"
        >


        <!-- EMAIL ADDRESS -->

        <label for="register-email">
            Email
        </label>

        <input
            type="email"
            id="register-email"
            name="email"
            autocomplete="email"
            inputmode="email"
            autocapitalize="none"
            spellcheck="false"
            required
            maxlength="100"
            value="<?= h($_POST['email'] ?? '') ?>"
        >


        <!-- PHONE NUMBER -->

        <label for="register-phone">
            Phone number
        </label>

        <input
            id="register-phone"
            name="phone"
            type="tel"
            autocomplete="tel"
            required
            maxlength="20"
            value="<?= h($_POST['phone'] ?? '') ?>"
        >


        <!-- PASSWORD -->

        <label for="register-password">
            Password (8–72 bytes)
        </label>

        <input
            type="password"
            id="register-password"
            name="password"
            autocomplete="new-password"
            required
            minlength="8"
            maxlength="72"
        >
        <button
            type="button"
            class="register-password-toggle"
            data-password-target="register-password"
            aria-controls="register-password"
            aria-pressed="false"
            style="display:block; margin:8px 0 0 auto; padding:4px 0; border:0; background:transparent; color:#a35f54; font:700 12px Montserrat,Arial,sans-serif; text-decoration:underline; text-underline-offset:3px; cursor:pointer;"
        >Show password</button>


        <!-- CONFIRM PASSWORD -->

        <label for="register-confirm-password">
            Confirm password
        </label>

        <input
            type="password"
            id="register-confirm-password"
            name="confirm_password"
            autocomplete="new-password"
            required
            minlength="8"
            maxlength="72"
        >
        <button
            type="button"
            class="register-password-toggle"
            data-password-target="register-confirm-password"
            aria-controls="register-confirm-password"
            aria-pressed="false"
            style="display:block; margin:8px 0 0 auto; padding:4px 0; border:0; background:transparent; color:#a35f54; font:700 12px Montserrat,Arial,sans-serif; text-decoration:underline; text-underline-offset:3px; cursor:pointer;"
        >Show password</button>


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

<script>
    // Each Show/Hide button controls only its matching password field.
    document.querySelectorAll('.register-password-toggle').forEach((button) => {
        const input = document.getElementById(button.dataset.passwordTarget);
        if (!input) return;

        button.addEventListener('click', () => {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? 'Hide password' : 'Show password';
            button.setAttribute('aria-pressed', String(show));
        });
    });
</script>


<!-- 5. FOOTER -->

<?php include __DIR__ . '/includes/footer.php'; ?>
