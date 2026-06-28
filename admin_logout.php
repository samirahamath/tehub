<?php
require_once 'db.php';
unset($_SESSION['admin_user']);
session_destroy();
header('Location: admin_login.php');
exit;
