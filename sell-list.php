<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'sell-list.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

$sql = $pdo->query('
  SELECT s.sell_id, s.item_id, i.image_path
  FROM sell s
  JOIN item_image i ON s.item_id = i.item_id
  WHERE s.is_delete = 0
  AND i.show_home = 1
');

$sells = $sql->fetchAll(); // ← これ1回だけ
?>

<section class="section">
  <div class="container">

    <!-- タイトルと出品数 -->
    <div class="title-area">
      <a href="#" onclick="history.back()" class="back-arrow">&lt;</a>
      <h3 class="title">出品一覧</h3>
    </div>
    <p>出品数：<?= count($sells) ?></p>

    <!-- 🔸 商品カード一覧 -->
    <div class="item-grid">
      <?php if (empty($sells)): ?>
        <p>現在出品されている商品はありません。</p>
      <?php else: foreach ($sells as $sell): ?>
        <a href="item-detail.php?item_id=<?= htmlspecialchars($sell['item_id']) ?>" class="item-link">
          <div class="item">
            <div class="image-box">
              <img src="item-image/<?= htmlspecialchars($sell['image_path']) ?>" alt="商品画像">
            </div>
          </div>
        </a>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
