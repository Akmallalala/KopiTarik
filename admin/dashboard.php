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
</head>

<body>

    <h1>
        Welcome Admin,
        <?= htmlspecialchars($_SESSION['admin_username']) ?>
    </h1>

    <p>
        Admin login successful.
    </p>

    <a href="logout.php">
        Logout
    </a>

</body>

</html>