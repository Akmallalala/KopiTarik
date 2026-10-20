<?php require_once __DIR__ . '/security.php'; ?>
<nav class="shop-nav" aria-label="Main navigation"><a href="index.php"><strong>KopiTarikBn</strong></a><div><a href="menu.php">Menu</a><a href="cart.php">Cart</a>
<?php if (isset($_SESSION['admin_id'])): ?><a href="admin/dashboard.php">Admin dashboard</a><?php endif; ?>
<?php if (isset($_SESSION['admin_id']) || isset($_SESSION['customer_id'])): ?><form method="POST" action="logout.php"><?= csrf_field() ?><button type="submit">Logout</button></form><?php else: ?><a href="login.php">Login</a><?php endif; ?>
</div></nav>
