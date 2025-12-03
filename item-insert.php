<?php
require_once 'db-connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $itemId = isset($_POST['item_id']) ? intval($_POST['item_id']) : null;
    if (!$itemId) {
        echo "<script>alert('更新が失敗しました'); location.href='sell-list.php';</script>";
        exit;
    }

    $pdo = new PDO($connect, USER, PASS);

    // 更新するフィールドを配列で作成
    $fields = [];
    $params = [];

    if (!empty($_POST['product_name'])) {
        $fields[] = 'item_name=?';
        $params[] = htmlspecialchars($_POST['product_name']);
    }
    if (!empty($_POST['product_description'])) {
        $fields[] = 'detail=?';
        $params[] = htmlspecialchars($_POST['product_description']);
    }
    if (!empty($_POST['product_category'])) {
        $fields[] = 'category_id=?';
        $params[] = intval($_POST['product_category']);
    }
    if (!empty($_POST['product_price'])) {
        $fields[] = 'price=?';
        $params[] = intval($_POST['product_price']);
    }

    // 更新時間
    $fields[] = 'update_time=NOW()';

    if (!empty($_FILES['product_images']['name'][0])) {
        $stmtOld = $pdo->prepare("SELECT image_path FROM item_image WHERE item_id=?");
        $stmtOld->execute([$itemId]);
        $oldImages = $stmtOld->fetchAll(PDO::FETCH_COLUMN);

        foreach ($oldImages as $old) {
            $path = "item-image/" . $old;
            if (file_exists($path)) unlink($path);
        }

        // item_imageテーブルからも削除
        $stmtDel = $pdo->prepare("DELETE FROM item_image WHERE item_id=?");
        $stmtDel->execute([$itemId]);

        // 新しい画像アップロード
        if (!empty($_FILES['product_images']['name'][0])) {
            $images = $_FILES['product_images'];
            $uploadDir = "item-image/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            for ($i = 0; $i < count($images['name']); $i++) {
                if ($images['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp_name = $images['tmp_name'][$i];
                    $file_name = basename($images['name'][$i]);
                    $new_file_name = uniqid() . '_' . $file_name;
                    $target_file = $uploadDir . $new_file_name;

                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $show_home = $i === 0 ? 1 : 0;
                        $stmt = $pdo->prepare("INSERT INTO item_image (item_id, image_path, show_home) VALUES (?, ?, ?)");
                        $stmt->execute([$itemId, $new_file_name, $show_home]);
                    }
                }
            }
        }
    }

    // DB更新
    if ($fields) {
        $params[] = $itemId;
        $stmt = $pdo->prepare('UPDATE item SET ' . implode(', ', $fields) . ' WHERE item_id=?');
        $stmt->execute($params);
    }

    header("Location: sell-list.php");
    exit;
} else {
    echo "<script>alert('更新が失敗しました'); location.href='sell-list.php';</script>";
    exit;
}
