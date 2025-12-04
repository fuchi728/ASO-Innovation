<?php
session_start();
require_once 'db-connect.php';

// ログインチェック
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
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
