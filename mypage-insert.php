<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>
<?php
$pdo = new PDO($connect, USER, PASS);

$nickname = htmlspecialchars(trim($_POST['nickname']));
$self_introduction = htmlspecialchars(trim($_POST['textarea']));
$name = htmlspecialchars(trim($_POST['name']));
$address = htmlspecialchars(trim($_POST['address']));
$user_id = $_SESSION['user']['user_id'];

// ユーザー情報取得
$sql = $pdo->prepare('select * from user_info where user_id=?');
$sql->execute([$user_id]);
$user = $sql->fetch(PDO::FETCH_ASSOC);

// テキスト系の更新
if ($user_id) {
    $fields = [];
    $params = [];
    if (!empty($nickname)) {
        $fields[] = 'nickname=?';
        $params[] = $nickname;
    }
    if (!empty($self_introduction)) {
        $fields[] = 'self_introduction=?';
        $params[] = $self_introduction;
    }
    if (!empty($name)) {
        $fields[] = 'name=?';
        $params[] = $name;
    }
    if (!empty($address)) {
        $fields[] = 'address=?';
        $params[] = $address;
    }

    // 画像アップロード
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['resume']['tmp_name'];
        $file_name = basename($_FILES['resume']['name']);
        $target_dir = 'user-icon/';
        $new_file_name = uniqid() . '_' . $file_name;
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($tmp_name, $target_file)) {
            // 古い画像削除
            if (!empty($user['profile_image']) && file_exists($target_dir . $user['profile_image'])) {
                unlink($target_dir . $user['profile_image']);
            }

            // 画像ファイルをサーバーに保存
            $fields[] = 'profile_image=?';
            $params[] = $new_file_name;
        }
    }

    // sql更新
    if ($fields) {
        $params[] = $user_id;
        $sql = $pdo->prepare('update user_info set ' . implode(', ', $fields) . ' where user_id=?');
        $sql->execute($params);
        header('Location: mypage.php');
        exit;
    }
}
?>