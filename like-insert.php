<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'db-connect.php';

// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}

// PDO接続
$pdo = new PDO($connect, USER, PASS);

// POSTで送られたJSONを取得
$data = json_decode(file_get_contents("php://input"), true);
$item_id = $data['item_id'] ?? null;
$liked   = $data['liked'] ?? null;
$user_id = $_SESSION['user']['user_id'];

if (!$item_id || !is_bool($liked)) {
    echo json_encode(["success" => false, "error" => "不正なリクエスト"]);
    exit;
}

// いいねテーブル確認
$sql = $pdo->prepare("SELECT * FROM good WHERE user_id=? AND item_id=?");
$sql->execute([$user_id, $item_id]);
$good = $sql->fetch(PDO::FETCH_ASSOC);

// like → remove
if ($liked && $good) {
    $sql = $pdo->prepare("UPDATE good SET is_delete = 1 WHERE user_id=? AND item_id=?");
    $sql->execute([$user_id, $item_id]);
    echo json_encode(["success" => true, "liked" => false]);
    exit;
}

// remove → like
if (!$good) {
    $sql = $pdo->prepare("INSERT INTO good (user_id, item_id) VALUES (?, ?)");
    $sql->execute([$user_id, $item_id]);
} else {
    $sql = $pdo->prepare("UPDATE good SET is_delete = 0 WHERE user_id=? AND item_id=?");
    $sql->execute([$user_id, $item_id]);
}

echo json_encode(["success" => true, "liked" => true]);