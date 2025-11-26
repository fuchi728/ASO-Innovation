<?php
// ========================================
// フォロワー一覧（自分をフォローしている人）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'follower-list.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// 仮ログイン中ユーザー（例：3番）
$login_user = 3;

// -----------------------------
// フォロワー一覧を取得（nickname 表示）
// -----------------------------
$sql = $pdo->prepare("
  SELECT 
    u.user_id,
    u.nickname,
    u.email
  FROM follow f
  JOIN user_info u ON f.follower_id = u.user_id
  WHERE f.followed_id = ?
  AND u.is_delete = 0
");
$sql->execute([$login_user]);
$followers = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/follow-list.css">

<section class="section has-background-warning-light">
  <div class="container">

    <!-- 戻るボタン＋タイトル -->
    <div class="title-bar">
      <a href="mypage.php" class="back-arrow">＜</a>
      <h2 class="title is-5">フォロワー</h2>
    </div>

    <?php if (empty($followers)): ?>
      <p class="has-text-centered">フォロワーはいません。</p>
    <?php else: ?>
      <div class="follow-list">
        <?php foreach ($followers as $follower): ?>
          <div class="follow-card">
            <span class="user-name"><?= htmlspecialchars($follower['nickname'] ?: '名無しユーザー') ?></span>
            <a href="user-page.php?user_id=<?= htmlspecialchars($follower['user_id']) ?>" 
               class="button is-warning is-small">プロフィール</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require 'footer-menu.php'; require 'footer.php'; ?>
