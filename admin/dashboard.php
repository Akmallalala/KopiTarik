<?php

require '../include/admin_auth.php';
require '../include/db_connect.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard | KopiTarikBn</title>

    <link
        rel="stylesheet"
        href="../assets/admin.css"
    >

</head>

<body>

<div class="admin-layout">

    <?php include 'include/sidebar.php'; ?>

    <main class="admin-main">

        <div class="admin-header">

            <h1>Dashboard</h1>

            <p>
                Welcome back,
                <?= htmlspecialchars($_SESSION['admin_username']) ?>
            </p>

        </div>

        <p>
            Admin dashboard content will go here.
        </p>

    </main>

</div>

</body>

</html>