<?php
session_start();
require_once 'db-connect.php';

// 管理者チェック
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) {
    header("Location: item-list.php");
    exit();
}

// POSTチェック
if (!isset($_POST['item_id']) || empty($_POST['item_id'])) {
    header("Location: item-list.php");
    exit();
}

$item_id = intval($_POST['item_id']);

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("UPDATE item SET is_deleted = 1 WHERE item_id = ?");
    $stmt->execute([$item_id]);

    if ($_POST['from'] == 'Product Listing Form') {
        echo "<script>
            alert('商品を削除しました');
            window.location.href='sell-list.php';
          </script>";
        exit();
    } else {
        echo "<script>
            alert('商品を削除しました');
            window.location.href='admin-home.php';
          </script>";
        exit();
    }
} catch (PDOException $e) {
    echo "削除に失敗しました: " . htmlspecialchars($e->getMessage());
}
