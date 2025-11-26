<?php require_once 'db-connect.php'; ?>
<?php
$pdo = new PDO($connect, USER, PASS);

$email = htmlspecialchars(trim($_POST['email']));
$content = htmlspecialchars(trim($_POST['textarea']));

$sql1 = $pdo->prepare('select user_id from user_info where email=?');
$sql1->execute([$email]);
$user_id = $sql1->fetchColumn();

if ($user_id) {
    if ($_POST['help'] === 'delete') {
        $sql2 = $pdo->prepare('insert into delete_request (user_id,reason) values(?,?)');
        $sql2->execute([$user_id, $content]);
        echo "<script>
                alert('送信が完了しました。')
                window.location.href = 'help.php';
              </script>";
    } else {
        $sql2 = $pdo->prepare('insert into help (user_id,content) values(?,?)');
        $sql2->execute([$user_id, $content]);
        echo "<script>
                alert('送信が完了しました。')
                window.location.href = 'help.php';
              </script>";
    }
} else {
    echo "<script>
            alert('入力されたメールアドレスは登録されていません。')
            window.location.href = 'help.php';
          </script>";
}
?>