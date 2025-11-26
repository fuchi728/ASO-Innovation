<?php
// ========================================
// 管理者トップページ（商品一覧表示）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db-connect.php';

$css_files = ['main-style.css', 'item-list.css','admin-header.css'];
require 'admin-header.php';

// ★ 管理者メニューもここで読み込み
require 'admin-menu.php';

$pdo = new PDO($connect, USER, PASS);
$order = $_GET['sort'] ?? 'price_asc';

switch ($order) {
  case 'price_desc':
    $orderBy = 'i.price DESC';
    break;
  case 'id_desc':
    $orderBy = 'i.item_id DESC';
    break;
  case 'like_desc':
    $orderBy = 'good_count DESC';
    break;
  default:
    $orderBy = 'i.price ASC';
}

$sql = "
  SELECT 
    i.*, 
    im.image_path,
    COUNT(g.item_id) AS good_count
  FROM item i
  LEFT JOIN good g ON i.item_id = g.item_id AND g.is_delete = 0
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  GROUP BY i.item_id
  ORDER BY $orderBy
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
          <option value="like_desc" <?= $order=='like_desc'?'selected':'' ?>>いいね順</option>
        </select>
        <i class="fas fa-sort-amount-up"></i>
      </div>
    </div>
  </div>

  <!-- フル幅グリッド -->
  <div class="item-wrapper">
    <div class="item-grid">
      <?php if (empty($items)): ?>
        <p>現在表示できる商品がありません。</p>
      <?php else: foreach ($items as $item): ?>
        <a href="item-detail.php?item_id=<?= htmlspecialchars($item['item_id']) ?>" class="item-link">
          <div class="item">
            <div class="image-box">
              <img src="item-image/<?= htmlspecialchars($item['image_path'] ?? 'no-image.png') ?>">
            </div>
            <p><?= htmlspecialchars($item['item_name']) ?></p>
            <p>¥<?= number_format($item['price']) ?></p>
          </div>
        </a>
      <?php endforeach; endif; ?>
    </div>
  </div>

</section>

</body>
</html>
