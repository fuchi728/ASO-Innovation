<?php
require 'db-connect.php'; 
$css_files = ['signup.css'];
require 'header.php';

$message = "";

// POST送信されたとき
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $pdo = new PDO($connect, USER, PASS);

    // 既に登録済みメールアドレスをチェック
    $sql = $pdo->prepare("SELECT * FROM login WHERE email = ?");
    $sql->execute([$email]);

    if ($sql->fetch()) {
        $message = "このメールアドレスは既に登録されています。";
    } else {

        // user_info へ最低限の情報を入れる（名前などは後で編集可能）
        $insert_user = $pdo->prepare("
            INSERT INTO user_info (name, nickname, email)
            VALUES ('未設定', '未設定', ?)
        ");
        $insert_user->execute([$email]);

        // ここで自動採番の user_id を取得
        $user_id = $pdo->lastInsertId();

        // パスワードをハッシュ化
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // login テーブルへ登録（外部キー user_id が必要）
        $insert_login = $pdo->prepare("
            INSERT INTO login (user_id, email, password)
            VALUES (?, ?, ?)
        ");
        $insert_login->execute([$user_id, $email, $hashed]);

        // 完了したらログイン画面へ
        header("Location: login.php");
        exit();
    }
}
?>


<style>
    body {
        font-family: 'Noto Sans JP', sans-serif;
        background-color: #fff4e0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .signup-container {
        background: #fff4e0;
        text-align: center;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        width: 320px;
    }
    .logo-img {
        width: 180px;
        margin-bottom: 20px;
    }
    h2 {
        font-size: 22px;
        margin-bottom: 20px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }
    input[type="email"], input[type="password"] {
        width: 90%;
        padding: 10px;
        border: 2px solid #fff4e0;
        border-radius: 8px;
        margin: 8px 0;
        font-size: 14px;
    }
    .signup-btn {
        width: 90%;
        padding: 10px;
        background-color: #e9b72f;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 15px;
    }
    .signup-btn:hover {
        background-color: #d8a628;
    }
    .error-msg {
        color: red;
        margin-bottom: 10px;
    }
</style>

<div class="signup-container">

    <img src="logo-image/Vanikaロゴ.png" class="logo-img">

    <h2>新規会員登録</h2>

    <?php if ($message): ?>
        <div class="error-msg"><?= $message ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <input type="email" name="email" placeholder="メールアドレス" required>
        <br>
        <input type="password" name="password" placeholder="パスワード" required>
        <br>
        <button type="submit" class="signup-btn">登録</button>
    </form>
</div>

<?php require 'footer.php'; ?>
