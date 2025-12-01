<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
  header("Location: login.php");
  exit;
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'sell-list.css', 'title.css'];
require 'header.php';
require 'header-menu.php';

$user_id = $_SESSION['user']['user_id'];

$pdo = new PDO($connect, USER, PASS);

$sql = $pdo->prepare('
  SELECT s.sell_id, s.item_id, i.image_path, it.*
  FROM sell s
  JOIN item it ON s.item_id = it.item_id
  JOIN item_image i ON s.item_id = i.item_id
  WHERE s.is_delete = 0
    AND s.user_id = ?
    AND it.is_deleted = 0
    AND i.show_home = 1
');

$sql->execute([$user_id]);
$sells = $sql->fetchAll(); // ← これ1回だけ
?>

<section class="section">
  <div class="container">

    <!-- タイトルと出品数 -->
    <?php
    // 遷移元取得
    $from = $_GET['from'] ?? null;
    $item_id = $_GET['item_id'] ?? null;
    $other_user_id = $_GET['user'] ?? null;

    if ($from === 'mypage') {
      $back_link = 'mypage.php';
    } else {
      $back_link = 'Listing Status Screen.php';
    }
    ?>
    <nav id="page_title" class="navbar is-justify-content-space-between is-align-items-center" role="navigation" aria-label="main navigation">
      <a href="<?= $back_link ?>" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small">
          <i class="fas fa-angle-left"></i>
        </span>
      </a>
      <div class="navbar-center">
        <span class="title is-6">出品一覧</span>
      </div>
    </nav>
    <p>出品数：<?= count($sells) ?></p>

    <!-- 🔸 商品カード一覧 -->
    <div class="item-grid">
      <?php if (empty($sells)): ?>
        <p>現在出品されている商品はありません。</p>
        <?php else: foreach ($sells as $sell): ?>

          <a href="Product Listing Form.php?item_id=<?= htmlspecialchars($sell['item_id']) ?>" class="item-link">
            <div class="item">
              <?php if ($sell['is_sold'] == 1): ?>
                <span class="sold-tag">SOLD</span>
              <?php endif; ?>
              <div class="image-box">
                <img src="item-image/<?= htmlspecialchars($sell['image_path']) ?>" alt="商品画像">
              </div>
              <p><?= htmlspecialchars($sell['item_name']) ?></p>
              <p>¥<?= number_format($sell['price']) ?></p>
            </div>
          </a>
      <?php endforeach;
      endif; ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>