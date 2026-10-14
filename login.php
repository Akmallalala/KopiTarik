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

<body>

<?php include 'include/navbar.php'; ?>

<main class="login-page">

    <div class="login-container">

        <h1>Welcome Back</h1>

        <p class="login-subtitle">
            Login to your KopiTarikBn account
        </p>


        <?php if (isset($_GET['error'])): ?>

    <div class="login-error">

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

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-btn"
            >
                Login
            </button>

        </form>


        <div class="login-options">

            <p>
                Don't have an account?
                <a href="register.php">
                    Register
                </a>
            </p>


            <div class="login-divider">
                <span>OR</span>
            </div>


            <a
                href="index.php"
                class="guest-btn"
            >
                Continue as Guest
            </a>

        </div>

    </div>

</main>

<?php include 'include/footer.php'; ?>

</body>
</html>