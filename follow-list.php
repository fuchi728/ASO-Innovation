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

$css_files = ['main-style.css', 'follow-list.css', 'title.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

if (isset($_GET['user'])) {
  $user = intval($_GET['user']);
} else {
  $user = $_SESSION['user']['user_id'];
}

// 遷移先
$from = $_GET['from'] ?? null;
if ($from == 'other-user') {
  $back_link = 'other-user.php?user=' . $user;
} else {
  $back_link = 'mypage.php';
}

// -----------------------------
// フォロー解除処理
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['followed_id'])) {
  $followed_id = $_POST['followed_id'];
  $delete = $pdo->prepare('DELETE FROM follow WHERE follower_id = ? AND followed_id = ?');
  $delete->execute([$user, $followed_id]);
}

// -----------------------------
// フォロー中ユーザーを取得（nickname 表示）
// -----------------------------
$sql = $pdo->prepare("
  SELECT 
    u.user_id,
    u.nickname,
    u.email,
    u.profile_image
  FROM follow f
  JOIN user_info u ON f.followed_id = u.user_id
  WHERE f.follower_id = ?
  AND u.is_delete = 0
");
$sql->execute([$user]);
$follows = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section has-background-warning-light">
  <div class="container">

    <!--ページタイトル-->
    <nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center">
      <a href="<?= $back_link ?>" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small"><i class="fas fa-angle-left"></i></span>
      </a>
      <div class="navbar-center">
        <span class="page_title title is-6">フォロー中</span>
      </div>
    </nav>

    <?php if (empty($follows)): ?>
      <p class="has-text-centered">フォロー中のユーザーはいません。</p>
    <?php else: ?>
      <div class="follow-list">
        <?php foreach ($follows as $follow): ?>
          <div class="follow-card">
            <div class="is-flex is-align-items-center">
              <figure id="user_icon" class="m-3">
                <?php
                if (empty($follow['profile_image'])) {
                  echo '<img src="user-icon/default.png" alt="ユーザー画像">';
                } else {
                  echo '<img src="user-icon/' . htmlspecialchars($user['profile_image']) . '" alt="ユーザー画像">';
                }
                ?>
              </figure>
              <span class="user-name">
                <?= htmlspecialchars($follow['nickname'] ?: '名無しユーザー') ?>
              </span>
            </div>
            <!-- 他人のフォロー一覧のとき -->
            <?php if ($follow['user_id'] != $_SESSION['user']['user_id'] && $from != 'mypage'): ?>
              <a href="other-user.php?user=<?= $follow['user_id'] ?>&from=follow-list"
                class="button is-warning is-small">
                プロフィール
              </a>
            <?php endif; ?>
            <!-- 自分のフォロー一覧のとき -->
            <?php if ($from === 'mypage'): ?>
              <form method="post">
                <input type="hidden" name="followed_id" value="<?= htmlspecialchars($follow['user_id']) ?>">
                <button type="submit" class="button is-warning is-small">フォロー解除</button>
              </form>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<?php require 'footer-menu.php';
require 'footer.php'; ?>