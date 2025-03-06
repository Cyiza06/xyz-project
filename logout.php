<?php
session_start();

session_unset();

session_destroy();

if (isset($_COOKIE['adminname'])) {
    setcookie('adminname', '', time() - 3600, '/');
}


header("Location: ./register/login.php");
exit;
?>
