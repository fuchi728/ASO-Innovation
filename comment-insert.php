<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>
<?php
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}
$pdo = new PDO($connect, USER, PASS);
$user_id = $_SESSION['user']['user_id'];
$item_id = $_POST['item_id'] ?? null;
$main_text = $_POST['main_text'] ?? null;
$sql = $pdo->prepare("insert into comment (main_text, item_id, user_id) values (?, ?, ?)");
$sql->execute([$main_text, $item_id, $user_id]);
header("Location: item-detail.php?item_id=" . urlencode($item_id). '&from='. urlencode($_POST['from']));
exit;