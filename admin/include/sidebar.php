<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="admin-sidebar">

    <div class="admin-logo">
        <h2>KopiTarikBn</h2>
        <span>Admin Panel</span>
    </div>

    <nav class="admin-nav">

        <a
            href="dashboard.php"
            class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>"
        >
            Dashboard
        </a>

        <a
            href="products.php"
            class="<?= in_array($currentPage, [
                'products.php',
                'add_product.php',
                'edit_product.php'
            ]) ? 'active' : '' ?>"
        >
            Products
        </a>

        <a
            href="categories.php"
            class="<?= $currentPage === 'categories.php' ? 'active' : '' ?>"
        >
            Categories
        </a>

        <a
            href="orders.php"
            class="<?= in_array($currentPage, [
                'orders.php',
                'view_order.php'
            ]) ? 'active' : '' ?>"
        >
            Orders
        </a>

        <a
            href="report.php"
            class="<?= $currentPage === 'report.php' ? 'active' : '' ?>"
        >
            Reports
        </a>

        <a
            href="logout.php"
            class="logout-link"
        >
            Logout
        </a>

    </nav>

</aside>