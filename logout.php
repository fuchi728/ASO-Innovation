<?php session_start(); ?>
<?php
    unset($_SESSION['customer']);
    header('Location: login.php');
    exit;
?>