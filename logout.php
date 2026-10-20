<?php
require_once __DIR__ . '/include/security.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
verify_csrf();
destroy_login_session();
header('Location: login.php');
exit;
