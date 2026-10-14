<?php
session_start();

// If already logged in as admin
if (isset($_SESSION['admin_id'])) {
    header("Location: admin/dashboard.php");
    exit;
}

// If already logged in as customer
if (isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="assets/style.css"
    >
</head>

<body class="login-body">

<?php include 'include/navbar.php'; ?>

<main class="login-page">
    <div class="login-layout">
        <section class="login-welcome" aria-labelledby="welcome-title">
            <div class="welcome-copy">
                <span class="welcome-kicker">GOOD COFFEE. GOOD COMPANY.</span>
                <h1 id="welcome-title">Your favorite<br>little coffee corner.</h1>
                <p>Come on in, get comfortable, and pick up right where your KopiTarikBn story left off.</p>
            </div>

            <div class="coffee-art" aria-hidden="true">
                <span class="coffee-steam steam-one"></span>
                <span class="coffee-steam steam-two"></span>
                <span class="coffee-steam steam-three"></span>
                <div class="coffee-cup">
                    <span class="coffee-heart"></span>
                </div>
                <span class="coffee-saucer"></span>
                <span class="coffee-bean bean-one"></span>
                <span class="coffee-bean bean-two"></span>
            </div>

            <p class="welcome-note"><span aria-hidden="true">✳</span> Made with care, served with a smile.</p>
        </section>

        <section class="login-container" aria-labelledby="login-title">
            <div class="login-heading">
                <span class="login-eyebrow">WELCOME BACK</span>
                <h2 id="login-title">Let’s get you logged in</h2>
                <p class="login-subtitle">Your next cup is just around the corner.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="login-error" role="alert">
                    <?php
                    if ($_GET['error'] === 'empty') {
                        echo "Please enter your email and password.";
                    } else {
                        echo "Invalid email or password.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form
                action="actions/login_process.php"
                method="POST"
                class="login-form"
            >
                <div class="form-group">
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

                <div class="form-group">
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

                <button type="submit" class="login-btn">
                    Login <span aria-hidden="true">→</span>
                </button>
            </form>

            <div class="login-options">
                <p>New to KopiTarikBn? <a href="register.php">Create an account</a></p>
                <div class="login-divider"><span>OR</span></div>
                <a href="index.php" class="guest-btn">Continue as Guest</a>
            </div>
        </section>
    </div>
</main>

<?php include 'include/footer.php'; ?>

</body>
</html>