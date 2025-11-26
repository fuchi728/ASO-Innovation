<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>
<?php
// ログインチェック
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}
$pdo = new PDO($connect, USER, PASS);

// 送信データの取得
$sender_id = $_SESSION['user']['user_id'];
$item_id   = $_POST['item_id'] ?? null;
$partner_id = $_POST['partner_id'] ?? null; 
$main_text = $_POST['main_text'] ?? null;


// DBにメッセージを追加
$sql = $pdo->prepare("insert into DM (main_text, sender_id, receiver_id, item_id) values (?, ?, ?, ?)");
$sql->execute([$main_text, $sender_id, $partner_id, $item_id]);

// メッセージ画面にリダイレクト
header("Location: dm-detail.php?item_id=" . urlencode($item_id) . '&partner_id=' . urlencode($sender_id));
exit;