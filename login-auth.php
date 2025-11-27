<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>
<?php
if (isset($_POST['email']) && isset($_POST['password'])) {
    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare("
        select 
            user_info.user_id,
            user_info.nickname,
            user_info.role,
            login.password
        from user_info
        join login on user_info.email = login.email
        where user_info.email = ?
    ");
    $sql->execute([$_POST['email']]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'nickname' => $user['nickname'],
            'role' => $user['role']
        ];
        header('Location: item-list.php');
        exit;
    } else {
        $_SESSION['login_error'] = "ユーザー名またはパスワードが違います。";
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>