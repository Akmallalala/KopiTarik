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


<style>
    /* Align each password visibility icon inside its own input. */
    .register-password-wrapper {
        position: relative;
    }

    .form-card .register-password-wrapper input {
        padding-right: 55px;
    }

    .register-password-toggle {
        position: absolute;
        top: 50%;
        right: 7px;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        padding: 0;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #8d5b50;
        cursor: pointer;
    }

    .register-password-toggle:hover {
        background: #f4e7df;
    }

    .register-password-toggle:focus-visible {
        outline: 2px solid #a35f54;
        outline-offset: 2px;
    }

    .register-password-toggle svg {
        width: 20px;
        height: 20px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        pointer-events: none;
    }
</style>

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

        <div class="register-password-wrapper">
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
                aria-label="Show password"
                aria-controls="register-password"
                aria-pressed="false"
                title="Show password"
            >
                <!-- Eye: password hidden. -->
                <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <!-- Eye with slash: password visible. -->
                <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                    <path d="M10.733 5.076A10.74 10.74 0 0 1 12 5c4.995 0 8.27 3.073 9.938 6.652a1 1 0 0 1 0 .696 11.12 11.12 0 0 1-3.433 4.467" />
                    <path d="M6.61 6.61A11.12 11.12 0 0 0 2.062 11.65a1 1 0 0 0 0 .696C3.73 15.927 7.005 19 12 19a10.74 10.74 0 0 0 5.39-1.39" />
                    <path d="m2 2 20 20" />
                    <path d="M9.879 9.878a3 3 0 0 0 4.242 4.243" />
                </svg>
            </button>
        </div>


        <!-- CONFIRM PASSWORD -->

        <label for="register-confirm-password">
            Confirm password
        </label>

        <div class="register-password-wrapper">
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
                aria-label="Show password"
                aria-controls="register-confirm-password"
                aria-pressed="false"
                title="Show password"
            >
                <!-- Eye: password hidden. -->
                <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <!-- Eye with slash: password visible. -->
                <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                    <path d="M10.733 5.076A10.74 10.74 0 0 1 12 5c4.995 0 8.27 3.073 9.938 6.652a1 1 0 0 1 0 .696 11.12 11.12 0 0 1-3.433 4.467" />
                    <path d="M6.61 6.61A11.12 11.12 0 0 0 2.062 11.65a1 1 0 0 0 0 .696C3.73 15.927 7.005 19 12 19a10.74 10.74 0 0 0 5.39-1.39" />
                    <path d="m2 2 20 20" />
                    <path d="M9.879 9.878a3 3 0 0 0 4.242 4.243" />
                </svg>
            </button>
        </div>


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
    // Each eye icon controls its own password field.
    document.querySelectorAll('.register-password-toggle').forEach((button) => {
        const input = document.getElementById(button.dataset.passwordTarget);
        if (!input) return;

        const eyeIcon = button.querySelector('.icon-eye');
        const eyeOffIcon = button.querySelector('.icon-eye-off');

        button.addEventListener('click', () => {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            const action = show ? 'Hide password' : 'Show password';
            button.setAttribute('aria-label', action);
            button.setAttribute('title', action);
            button.setAttribute('aria-pressed', String(show));
            eyeIcon.style.display = show ? 'none' : 'block';
            eyeOffIcon.style.display = show ? 'block' : 'none';
        });
    });
</script>


<!-- 5. FOOTER -->

<?php include __DIR__ . '/includes/footer.php'; ?>
