<?php
session_start();
require_once 'db-connect.php';

// ログインチェック
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $pdo = new PDO($connect, USER, PASS);

    $item_name  = htmlspecialchars($_POST['product_name']);
    $detail     = htmlspecialchars($_POST['product_description']);
    $category   = intval($_POST['product_category']);
    $price      = intval($_POST['product_price']);
    $user_id    = $_SESSION['user']['user_id'];

    // --- itemテーブルへ商品登録 ---
    $stmt = $pdo->prepare("
        INSERT INTO item (item_name, detail, category_id, price) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$item_name, $detail, $category, $price]);

    // 作成された item_id を取得
    $item_id = $pdo->lastInsertId();

    // --- item_image へ画像保存 ---
    if (!empty($_FILES['product_images']['name'][0])) {

        $uploadDir = "item-image/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $images = $_FILES['product_images'];

        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images['error'][$i] === UPLOAD_ERR_OK) {

                $tmp_name = $images['tmp_name'][$i];
                $file_name = basename($images['name'][$i]);

                // ファイル名重複対策
                $new_name = uniqid() . "_" . $file_name;
                $target_file = $uploadDir . $new_name;

                if (move_uploaded_file($tmp_name, $target_file)) {

                    // 最初の画像だけ show_home=1
                    $show_home = $i === 0 ? 1 : 0;

                    $stmtImg = $pdo->prepare("
                        INSERT INTO item_image (item_id, image_path, show_home)
                        VALUES (?, ?, ?)
                    ");
                    $stmtImg->execute([$item_id, $new_name, $show_home]);
                }
            }
        }
    }


    // --- 出品者登録 ---
    $stmtSell = $pdo->prepare("
        INSERT INTO sell (item_id, user_id, sell_time)
        VALUES (?, ?, NOW())
    ");
    $stmtSell->execute([$item_id, $user_id]);

    header("Location: sell-list.php");
    exit;
}
?>
