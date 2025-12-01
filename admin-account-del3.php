<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $sql = $pdo->prepare("UPDATE delete_request SET is_deal = 1 WHERE delete_id = ?");
    $sql->execute([$delete_id]);

    // 詳細ページにリダイレクトしてボタン無効を反映
    header("Location: admin-account-del2.php?delete_id=" . urlencode($delete_id));
    exit;
}

// POST 以外でアクセスされた場合は一覧へ
header("Location: admin-account-del1.php");
exit;
