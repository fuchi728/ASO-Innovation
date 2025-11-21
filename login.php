<?php session_start(); ?>
<?php require 'header.php'; ?>
<style>
    body {
        font-family: 'Noto Sans JP', sans-serif;
        background-color: #fff4e0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-container {
        background: #fff4e0;
        text-align: center;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        width: 320px;
    }

    .logo {
        font-size: 32px;
        font-weight: bold;
        color: #333;
        margin-bottom: 30px;
    }

    .logo span {
        color: #f3b233;
    }

    h2 {
        font-size: 22px;
        margin-bottom: 20px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    input[type="email"],
    input[type="password"] {
        width: 90%;
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 8px;
        margin: 8px 0;
        font-size: 14px;
    }

    .login-btn {
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

    .login-btn:hover {
        background-color: #d8a628;
    }

    .note {
        font-size: 12px;
        color: #7b6d5b;
        margin-top: 10px;
    }

    .signup {
        margin-top: 30px;
        font-size: 14px;
    }

    .signup a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }

    .error {
        color: red;
        font-size: 14px;
        margin-bottom: 10px;
    }
</style>
<div class="login-container">
    <div class="container">
        <img src="logo-image/Vanikaロゴ.png">
    </div>

    <?php
    if (isset($_SESSION['login_error'])) {
        echo '<p class="error">' . $_SESSION['login_error'] . '</p>';
        unset($_SESSION['login_error']);
    }
    ?>

    <form method="post" action="login-auth.php">
        <input type="email" name="email" placeholder="メールアドレス" required><br>
        <input type="password" name="password" placeholder="パスワード" required><br>
        <button type="submit" class="login-btn">ログイン</button>
    </form>

    <p class="note">利用規約およびプライバシーポリシーに同意の上、ログインへお進みください。</p>

    <div class="signup">
        <a href="signup.php">新規アカウントを作成はこちらから ➜</a>
    </div>
</div>
<?php require 'footer.php'; ?>