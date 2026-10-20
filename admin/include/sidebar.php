<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<aside class="admin-sidebar">

    <div class="admin-sidebar-header">
        <div class="admin-logo">
            <h2>KopiTarikBn</h2>
            <span>Admin Panel</span>
        </div>

        <button
            class="admin-menu-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="admin-navigation"
        >
            <span class="menu-toggle-label">Menu</span>
            <span class="menu-toggle-icon" aria-hidden="true"></span>
        </button>
    </div>

    <nav class="admin-nav" id="admin-navigation">

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

        <form action="logout.php" method="POST"><?= csrf_field() ?><button class="logout-link" type="submit">Logout</button></form>

    </nav>

</aside>

<script>
    const adminMenuToggle = document.querySelector('.admin-menu-toggle');
    const adminNavigation = document.getElementById('admin-navigation');

    adminMenuToggle.addEventListener('click', () => {
        const isExpanded = adminMenuToggle.getAttribute('aria-expanded') === 'true';
        adminMenuToggle.setAttribute('aria-expanded', String(!isExpanded));
        adminNavigation.classList.toggle('is-open', !isExpanded);
    });
</script>