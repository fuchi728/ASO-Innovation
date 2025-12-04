<?php
session_start();
require_once 'db-connect.php';

$css_files = ['main-style.css', 'notice-style.css'];
require 'header.php';
require 'header-menu.php';

// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header('Location: login.php');
    exit;
}

$login_user = $_SESSION['user']['user_id'];
$pdo = new PDO($connect, USER, PASS);

// コメント通知
$sql = $pdo->prepare("
    SELECT
        c.comment_time AS notice_time,
        c.item_id,
        i.item_name,
        'comment' AS notice_type,
        NULL AS partner_id
    FROM comment AS c
    INNER JOIN sell AS s ON c.item_id = s.item_id
    INNER JOIN item AS i ON i.item_id = c.item_id
    WHERE s.user_id = :user
      AND c.user_id != :user
      AND c.is_delete = 0
      AND s.is_delete = 0
");
$sql->execute([':user' => $login_user]);
$comment_notices = $sql->fetchAll(PDO::FETCH_ASSOC);

// 購入通知
$sql = $pdo->prepare("
    SELECT
        i.update_time AS notice_time,
        i.item_id,
        i.item_name,
        'purchase' AS notice_type,
        NULL AS partner_id
    FROM item AS i
    INNER JOIN sell AS s ON i.item_id = s.item_id
    WHERE i.is_sold = 1
      AND i.is_deleted = 0
      AND s.user_id = :user
      AND s.is_delete = 0
");
$sql->execute([':user' => $login_user]);
$purchase_notices = $sql->fetchAll(PDO::FETCH_ASSOC);

// DM通知
$sql = $pdo->prepare("
    SELECT
        DM.send_time AS notice_time,
        DM.item_id,
        i.item_name,
        'dm' AS notice_type,
        DM.sender_id AS partner_id
    FROM DM
    INNER JOIN item AS i ON i.item_id = DM.item_id
    WHERE DM.receiver_id = :user
      AND DM.sender_id != :user
      AND DM.is_delete = 0
      AND i.is_deleted = 0
");
$sql->execute([':user' => $login_user]);
$dm_notices = $sql->fetchAll(PDO::FETCH_ASSOC);

// パートナーの名前取得
if (!empty($dm_notices)) {
    foreach ($dm_notices as &$dm) {
        if (!empty($dm['partner_id'])) {
            $stmt = $pdo->prepare("SELECT nickname FROM user_info WHERE user_id = :uid");
            $stmt->execute([':uid' => $dm['partner_id']]);
            $dm['partner_name'] = $stmt->fetchColumn();
        } else {
            $dm['partner_name'] = "不明";
        }
    }
}

// すべての通知を統合
$all_notices = array_merge($comment_notices, $purchase_notices, $dm_notices);

// 日時順にソート（降順）
usort($all_notices, function($a, $b) {
    return strtotime($b['notice_time']) - strtotime($a['notice_time']);
});
?>

<div class="tabs is-fullwidth">
  <ul>
    <li class="is-active"><a href="notice-1.php">お知らせ</a></li>
    <li><a href="notice-2.php">NEWS</a></li>
  </ul>
</div>

<section class="section py-4">
  <div class="container">
    <?php foreach($all_notices as $notice): ?>
      <?php
        $link = '#';
        $text = '';
        if ($notice['notice_type'] === 'comment') {
            $link = "item-detail.php?item_id=" . htmlspecialchars($notice['item_id']);
            $text = "あなたの出品した " . htmlspecialchars($notice['item_name']) . " にコメントが届きました。";
        } elseif ($notice['notice_type'] === 'purchase') {
            $link = "item-detail.php?item_id=" . htmlspecialchars($notice['item_id']);
            $text = "あなたの出品した " . htmlspecialchars($notice['item_name']) . " が購入されました。";
        } elseif ($notice['notice_type'] === 'dm') {
            $link = "dm-detail.php?item_id=" . htmlspecialchars($notice['item_id']) . "&partner_id=" . htmlspecialchars($notice['partner_id']);
            $text = htmlspecialchars($notice['partner_name']) . " さんからメッセージが届きました。";
        }
      ?>
      <section class="section py-4">
        <div class="container">
          <a href="<?= $link ?>">
            <div class="box notice-box is-flex is-justify-content-space-between is-align-items-center">
              <div class="is-flex is-flex-direction-column">
                <p><?= $text ?></p>
                <p class="is-size-7 has-text-grey"><?= htmlspecialchars($notice['notice_time']) ?></p>
              </div>
              <span class="icon is-medium has-text-grey">
                <i class="fas fa-angle-right"></i>
              </span>
            </div>
          </a>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
</section>

<?php
require 'footer-menu.php';
require 'footer.php';
?>
