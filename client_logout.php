<?php
require_once 'db.php';
unset($_SESSION['client_user']);
session_destroy();
header('Location: client_login.php');
exit;
