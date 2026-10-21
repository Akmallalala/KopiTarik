<?php
require __DIR__ . '/config/app.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

if (isset($_SESSION['customer_id'])) {
    header('Location: index.php');
    exit;
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

            <style>
                /* Keep the visibility control aligned inside the password field. */
                .login-password-wrapper {
                    position: relative;
                }

                .login-field .login-password-wrapper input {
                    padding-right: 55px;
                }

                .login-password-toggle {
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

                .login-password-toggle:hover {
                    background: #f4e7df;
                }

                .login-password-toggle:focus-visible {
                    outline: 2px solid #a35f54;
                    outline-offset: 2px;
                }

                .login-password-toggle svg {
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

            <form action="actions/login_process.php" method="POST" class="login-form">
                <?= csrf_field() ?>
                <div class="login-field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        autocomplete="email"
                        inputmode="email"
                        autocapitalize="none"
                        spellcheck="false"
                        maxlength="100"
                        required
                    >
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="login-password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >
                        <button
                            type="button"
                            id="toggle-password"
                            class="login-password-toggle"
                            aria-label="Show password"
                            aria-controls="password"
                            aria-pressed="false"
                            title="Show password"
                        >
                            <!-- Eye icon: password is hidden; click to reveal. -->
                            <svg class="icon-eye" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <!-- Eye-off icon: password is visible; click to hide. -->
                            <svg class="icon-eye-off" viewBox="0 0 24 24" aria-hidden="true" style="display: none;">
                                <path d="M10.733 5.076A10.74 10.74 0 0 1 12 5c4.995 0 8.27 3.073 9.938 6.652a1 1 0 0 1 0 .696 11.12 11.12 0 0 1-3.433 4.467" />
                                <path d="M6.61 6.61A11.12 11.12 0 0 0 2.062 11.65a1 1 0 0 0 0 .696C3.73 15.927 7.005 19 12 19a10.74 10.74 0 0 0 5.39-1.39" />
                                <path d="m2 2 20 20" />
                                <path d="M9.879 9.878a3 3 0 0 0 4.242 4.243" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="login-submit">
                    Login <span aria-hidden="true">↗</span>
                </button>
            </form>

            <script>
                // Toggle password visibility without changing the input value.
                const passwordInput = document.getElementById('password');
                const togglePassword = document.getElementById('toggle-password');
                const eyeIcon = togglePassword.querySelector('.icon-eye');
                const eyeOffIcon = togglePassword.querySelector('.icon-eye-off');

                togglePassword.addEventListener('click', () => {
                    const isHidden = passwordInput.type === 'password';
                    passwordInput.type = isHidden ? 'text' : 'password';
                    const action = isHidden ? 'Hide password' : 'Show password';
                    togglePassword.setAttribute('aria-label', action);
                    togglePassword.setAttribute('title', action);
                    togglePassword.setAttribute('aria-pressed', String(isHidden));
                    eyeIcon.style.display = isHidden ? 'none' : 'block';
                    eyeOffIcon.style.display = isHidden ? 'block' : 'none';
                });
            </script>

            <?php if (is_file(__DIR__ . '/register.php')): ?>
                <p class="login-register">New to KopiTarikBn? <a href="register.php">Create an account</a></p>
            <?php endif; ?>
        </section>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
