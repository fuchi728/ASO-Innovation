<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>

<?php
$css_files = ['main-style.css', 'dm-list-style.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>

<div class="tabs is-fullwidth">
  <ul>
    <li><a href="dm-list.php">取引一覧</a></li>
  </ul>
</div>

<?php
// ログインチェック
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

// ログイン中ユーザーIDを取得
$user_id = $_SESSION['user']['user_id'];

$pdo = new PDO($connect, USER, PASS);

// DMテーブルからログインユーザーに関係するメッセージを商品ごとにまとめる。
// 各DMについて、最新の送信日時、送信者・受信者、相手のニックネーム、商品名を取得
$sql = $pdo->prepare("
    SELECT DM.item_id,
           MAX(DM.send_time) AS latest_time,
           DM.sender_id,
           DM.receiver_id,
           ui.nickname AS partner_nickname,
           i.item_name
    FROM DM
    LEFT JOIN user_info AS ui ON ui.user_id = CASE
        WHEN DM.sender_id = :user_id THEN DM.receiver_id
        ELSE DM.sender_id
    END
    LEFT JOIN item AS i ON i.item_id = DM.item_id
    WHERE (DM.sender_id = :user_id OR DM.receiver_id = :user_id)
      AND DM.is_delete = 0
    GROUP BY DM.item_id
    ORDER BY latest_time DESC
");
// :user_id にログイン中のIDをバインドして実行
$sql->execute(['user_id' => $user_id]);
// 取得したDMリストを配列で取得
$dm_list = $sql->fetchAll(PDO::FETCH_ASSOC);

foreach ($dm_list as $row) {
  echo '<section class="section py-4">';
  echo '<div class="container">';
  // dm-detail.phpへのリンク
  // GETパラメータに item_id, sender_id, receiver_id を渡す
  echo '<a href="dm-detail.php?item_id=', $row['item_id'], '&sender_id=', $row['sender_id'], '&receiver_id=', $row['receiver_id'], '" class="box notice-box">';

  // 相手ユーザー名と商品名を横並び
  echo '<div class="is-flex is-align-items-center is-justify-content-start mb-1">';
  echo '<p class="mr-4"><strong>相手：</strong>', htmlspecialchars($row['partner_nickname']), '</p>';
  echo '<p><strong>商品：</strong>', htmlspecialchars($row['item_name']), '</p>';
  echo '</div>';

  // 最新送信日時
  echo '<p class="is-size-7 has-text-grey">最終送信：', $row['latest_time'], '</p>';

  echo '<span class="icon is-medium has-text-grey" style="position:absolute; right:10px; top:50%; transform:translateY(-50%);">';
  echo '<i class="fas fa-angle-right"></i></span>';

  echo '</a>';
  echo '</div>';
  echo '</section>';
}
?>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>