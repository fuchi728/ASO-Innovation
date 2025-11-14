<?php
// ========================================
// 商品一覧画面（ソート付き）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db-connect.php';
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// 並び替え処理
$order = $_GET['sort'] ?? 'price_asc';
$order_sql = match($order) {
  'price_desc' => 'i.price DESC',
  'id_desc'    => 'i.item_id DESC',
  default      => 'i.price ASC'
};

// ✅ item と item_image を結合して取得
$sql = "
  SELECT i.*, im.image_path
  FROM item i
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  ORDER BY $order_sql
";

$items = $pdo->query($sql)->fetchAll();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="css/item-list.css">

<section class="section has-background-warning-light">
  <div class="container">

    <!-- ソートメニュー -->
    <div class="sort-bar">
      <div class="sort-center">
        <select id="sortSelect" onchange="location.href='?sort='+this.value;">
          <option value="price_asc" <?= $order=='price_asc'?'selected':'' ?>>価格順（安い順）</option>
          <option value="price_desc" <?= $order=='price_desc'?'selected':'' ?>>価格順（高い順）</option>
          <option value="id_desc" <?= $order=='id_desc'?'selected':'' ?>>新着順</option>
        </select>
        <i class="fas fa-sort-amount-up"></i>
      </div>
    </div>

    <!-- 商品一覧 -->
    <div class="item-grid">
      <?php if (empty($items)): ?>
        <p>現在表示できる商品がありません。</p>
      <?php else:
        foreach ($items as $item): ?>
          <div class="item">
            <a href="item-detail.php?item_id=<?= htmlspecialchars($item['item_id']) ?>">
              <div class="image-box">
                <img 
                  src="item-image/<?= htmlspecialchars($item['image_path'] ?? 'no-image.png') ?>" 
                  alt="商品画像"
                >
              </div>
            </a>
            <p><?= htmlspecialchars($item['item_name']) ?></p>
            <p>¥<?= number_format($item['price']) ?></p>
          </div>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
