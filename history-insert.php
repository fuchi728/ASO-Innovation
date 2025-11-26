<?php
session_start();
require_once 'db-connect.php';

$item_id = $_GET['item_id'] ?? null;
$from = $_GET['from'] ?? null;

if (!isset($_SESSION['user']['user_id'])) {
    header("Location: item-detail.php?item_id=" . urlencode($item_id));
    exit;
}

$pdo = new PDO($connect, USER, PASS);

$user_id = $_SESSION['user']['user_id'];

// 履歴に挿入（重複は更新）
$sql = $pdo->prepare("
    INSERT INTO view_history (user_id, item_id) VALUES (?, ?)
    ON DUPLICATE KEY UPDATE view_time = CURRENT_TIMESTAMP
");
$sql->execute([$user_id, $item_id]);

// 詳細ページにリダイレクト
header("Location: item-detail.php?item_id=" . urlencode($item_id) . '&from=' . $from);
exit;
