<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['help_id'])) {
    $help_id = $_POST['help_id'];

    $sql = $pdo->prepare("UPDATE help SET is_deal = 1 WHERE help_id = ?");
    $sql->execute([$help_id]);

    // 詳細ページにリダイレクトしてボタン無効を反映
    header("Location: admin-contact2.php?help_id=" . urlencode($help_id));
    exit;
}

// POST 以外でアクセスされた場合は一覧へ
header("Location: admin-contact1.php");
exit;
