<?php
// ========================================
// フォロワー一覧（自分をフォローしている人）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db-connect.php';

$css_files = ['main-style.css', 'follow-list.css'];
require 'header.php';
?>
<?php require 'db-connect.php'; ?>

$pdo = new PDO($connect, USER, PASS);

// ▼ ログイン中のユーザー（仮）
//   ※あなたの環境では session の user_id を使うなら書き換えてOK
$login_user = $_SESSION['user']['user_id'] ?? 3;

// -----------------------------
// フォロワー一覧（削除されていないユーザーのみ）
// -----------------------------
$sql = $pdo->prepare("
    SELECT 
        u.user_id,
        u.nickname,
        u.profile_image
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

                <?php foreach ($followers as $f): ?>
                    <div class="follow-card">

                        <!-- アイコン（あれば表示、無ければデフォルト） -->
                        <div class="icon-area">
                            <?php if (empty($f['profile_image'])): ?>
                                <img src="user-icon/default.png" class="user-icon">
                            <?php else: ?>
                                <img src="user-icon/<?= htmlspecialchars($f['profile_image']) ?>" class="user-icon">
                            <?php endif; ?>
                        </div>

                        <!-- ニックネーム -->
                        <span class="user-name">
                            <?= htmlspecialchars($f['nickname'] ?: '名無しユーザー') ?>
                        </span>

                        <!-- プロフィールボタン（other-user.php へ） -->
                        <a 
                            href="other-user.php?user=<?= htmlspecialchars($f['user_id']) ?>&from=follower-list"
                            class="button is-warning is-small">
                            プロフィール
                        </a>

                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
