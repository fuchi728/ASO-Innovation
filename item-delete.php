<?php
session_start();
require_once 'db-connect.php';

// 管理者チェック
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 1) {
    header("Location: index.php");
    exit();
}

// POSTチェック
if (!isset($_POST['item_id']) || empty($_POST['item_id'])) {
    header("Location: index.php");
    exit();
}

$item_id = intval($_POST['item_id']);

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- トランザクション開始 ---
    $pdo->beginTransaction();

    // 子テーブル削除
    $pdo->prepare("DELETE FROM good WHERE item_id=?")->execute([$item_id]);
    $pdo->prepare("DELETE FROM comment WHERE item_id=?")->execute([$item_id]);
    $pdo->prepare("DELETE FROM buy WHERE item_id=?")->execute([$item_id]);
    $pdo->prepare("DELETE FROM sell WHERE item_id=?")->execute([$item_id]);
    $pdo->prepare("DELETE FROM view_history WHERE item_id=?")->execute([$item_id]);
    $pdo->prepare("DELETE FROM DM WHERE item_id=?")->execute([$item_id]);
    $pdo->prepare("DELETE FROM item_image WHERE item_id=?")->execute([$item_id]);

    // 親テーブル削除
    $pdo->prepare("DELETE FROM item WHERE item_id=?")->execute([$item_id]);

    // --- 成功したらコミット ---
    $pdo->commit();

    echo "<script>
            alert('商品を削除しました');
            window.location.href='item-list.php';
          </script>";
    exit();
} catch (PDOException $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack(); // 元に戻す
    }

    echo "削除に失敗しました: " . htmlspecialchars($e->getMessage());
}
