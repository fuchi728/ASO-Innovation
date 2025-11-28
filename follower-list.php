<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
  header("Location: login.php");
  exit;
}
// ========================================
// フォロワー一覧（自分をフォローしている人）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'follow-list.css', 'title.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

if(isset($_GET['user'])){
  $user = intval($_GET['user']);
}else{
  $user = $_SESSION['user']['user_id'];
}

// 遷移先
$from = $_GET['from'] ?? null;
if($from == 'other-user'){
  $back_link = 'other-user.php?user='. $user;
}else{
  $back_link = 'mypage.php';
}

// -----------------------------
// フォロワー一覧（nickname 表示）
// -----------------------------
$sql = $pdo->prepare("
  SELECT 
    u.user_id,
    u.nickname
  FROM follow f
  JOIN user_info u ON f.follower_id = u.user_id
  WHERE f.followed_id = ?
  AND u.is_delete = 0
");
$sql->execute([$user]);
$followers = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section has-background-warning-light">
  <div class="container">
    <!--ページタイトル-->
    <nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center">
      <a href="<?= $back_link ?>" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small"><i class="fas fa-angle-left"></i></span>
      </a>
      <div class="navbar-center">
        <span class="page_title title is-6">フォロワー</span>
      </div>
    </nav>

    <?php if (empty($followers)): ?>
      <p class="has-text-centered">フォロワーはいません。</p>
    <?php else: ?>
      <div class="follow-list">

        <?php foreach ($followers as $f): ?>
          <div class="follow-card">

            <!-- ★ 左寄せで名前だけ -->
            <span class="user-name">
              <?= htmlspecialchars($f['nickname'] ?: '名無しユーザー') ?>
            </span>

            <!-- ★ プロフィールへ (other-user.php?user=◯◯) -->
            <a href="other-user.php?user=<?= $f['user_id'] ?>&from=follower-list"
              class="button is-warning is-small">
              プロフィール
            </a>

          </div>
        <?php endforeach; ?>

      </div>
    <?php endif; ?>

  </div>
</section>

<?php require 'footer-menu.php';
require 'footer.php'; ?>