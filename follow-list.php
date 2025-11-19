<?php
// ========================================
// フォロー中一覧画面（follower_id基準）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db-connect.php';

$css_files = ['main-style.css', 'follow-list.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// ログイン中ユーザーID（例：3）
$login_user = 3;

// -----------------------------
// follower_idがログイン中のユーザーの行を取得
// -----------------------------
$sql = $pdo->prepare("
  SELECT 
    u.user_id, 
    u.user_name
  FROM follow f
  JOIN user_info u ON f.followed_id = u.user_id
  WHERE f.follower_id = ?
");
$sql->execute([$login_user]);
$follows = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section has-background-warning-light">
  <div class="container">
    <h2 class="title is-5 has-text-centered">フォロー中</h2>

    <?php if (empty($follows)): ?>
      <p class="has-text-centered">フォロー中のユーザーはいません。</p>
    <?php else: ?>
      <div class="follow-list">
        <?php foreach ($follows as $follow): ?>
          <div class="follow-card">
            <span class="user-name"><?= htmlspecialchars($follow['user_name']) ?></span>
            <form method="post" action="unfollow.php" class="follow-form">
              <input type="hidden" name="followed_id" value="<?= htmlspecialchars($follow['user_id']) ?>">
              <button type="submit" class="button is-small is-warning">フォロー解除</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php require 'footer-menu.php'; require 'footer.php'; ?>
