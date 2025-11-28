<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}
// ========================================
// フォロー中一覧（フォロー解除機能付き）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'follow-list.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// 仮ログイン中ユーザー（例：3番）
$login_user = 3;

// -----------------------------
// フォロー解除処理
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['followed_id'])) {
  $followed_id = $_POST['followed_id'];
  $delete = $pdo->prepare('DELETE FROM follow WHERE follower_id = ? AND followed_id = ?');
  $delete->execute([$login_user, $followed_id]);
}

// -----------------------------
// フォロー中ユーザーを取得（nickname 表示）
// -----------------------------
$sql = $pdo->prepare("
  SELECT 
    u.user_id,
    u.nickname,
    u.email
  FROM follow f
  JOIN user_info u ON f.followed_id = u.user_id
  WHERE f.follower_id = ?
  AND u.is_delete = 0
");
$sql->execute([$login_user]);
$follows = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="css/follow-list.css">

<section class="section has-background-warning-light">
  <div class="container">

    <!-- 戻るボタン＋タイトル -->
    <div class="title-bar">
      <a href="mypage.php" class="back-arrow">＜</a>
      <h2 class="title is-5">フォロー中</h2>
    </div>

    <?php if (empty($follows)): ?>
      <p class="has-text-centered">フォロー中のユーザーはいません。</p>
    <?php else: ?>
      <div class="follow-list">
        <?php foreach ($follows as $follow): ?>
          <div class="follow-card">
            <span class="user-name"><?= htmlspecialchars($follow['nickname'] ?: '名無しユーザー') ?></span>
            <form method="post">
              <input type="hidden" name="followed_id" value="<?= htmlspecialchars($follow['user_id']) ?>">
              <button type="submit" class="button is-warning is-small">フォロー解除</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require 'footer-menu.php'; require 'footer.php'; ?>
