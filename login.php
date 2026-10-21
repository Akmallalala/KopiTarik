<?php
require __DIR__ . '/config/app.php';

// Only allow the known checkout destination.
$redirect = (
    isset($_GET['redirect'])
    && is_string($_GET['redirect'])
    && $_GET['redirect'] === 'checkout.php'
) ? 'checkout.php' : '';

if (isset($_SESSION['admin_id'])) {
    go('admin/dashboard.php');
}

if (logged()) {
    go($redirect === 'checkout.php' ? 'checkout.php' : 'index.php');
}

$title = 'Login';
include __DIR__ . '/includes/header.php';
?>

<section class="login-stage">
    <div class="login-glow" aria-hidden="true"></div>

    <div class="wrap login-layout">
        <section class="login-story" aria-labelledby="welcome-title">
            <span class="login-kicker"><span aria-hidden="true">✳</span> YOUR DAILY DOSE OF BREW</span>
            <h1 id="welcome-title">GOOD TO<br>HAVE YOU <span>BACK.</span></h1>
            <p>Sign in to pick up where you left off and find your next favourite sip.</p>

            <!-- <div class="login-art">
                <img src="assets/img/home-coffee.png" alt="A freshly brewed cup of coffee">
                <span class="login-art-note">MADE WITH CARE, SERVED WITH JOY</span>
            </div> -->
        </section>

        <section class="login-card" aria-labelledby="login-title">
            <div class="login-heading">
                <span class="login-eyebrow">WELCOME BACK</span>
                <h2 id="login-title">Let’s get you logged in</h2>
                <p>Your next cup is just around the corner.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="login-error" role="alert">
                    <?php
                    if ($_GET['error'] === 'empty') {
                        echo 'Please enter your email and password.';
                    } else {
                        echo 'Invalid email or password.';
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="actions/login_process.php" method="POST" class="login-form">
                <?= csrf_field() ?>

                <input
    type="hidden"
    name="redirect"
    value="<?= h($redirect) ?>"
>

                <div class="login-field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="login-submit">
                    Login <span aria-hidden="true">↗</span>
                </button>
            </form>

            <?php if (is_file(__DIR__ . '/register.php')): ?>
                <p class="login-register">New to KopiTarikBn? <a href="register.php">Create an account</a></p>
            <?php endif; ?>
        </section>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
