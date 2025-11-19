<?php
// ========================================
// フォロー中一覧（解除もこのファイル内で処理）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db-connect.php';

$css_files = ['main-style.css', 'follow-list.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// 仮ログイン中ユーザー（例：3番）
// 実際は $_SESSION['user_id'] に置き換えてOK
$login_user = 3;

// -----------------------------
// POSTが送られてきた場合 → フォロー解除
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['followed_id'])) {
  $followed_id = $_POST['followed_id'];

  $delete = $pdo->prepare('DELETE FROM follow WHERE follower_id = ? AND followed_id = ?');
  $delete->execute([$login_user, $followed_id]);
}

// -----------------------------
// フォロー中ユーザーを取得
// -----------------------------
$sql = $pdo->prepare("
  SELECT 
    u.user_id, 
    u.email AS user_name
  FROM follow f
  JOIN login u ON f.followed_id = u.user_id
  WHERE f.follower_id = ?
  AND u.is_delete = 0
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
            <form method="post" style="margin:0;">
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
