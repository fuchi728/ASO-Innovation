<?php
// ========================================
// 商品一覧画面（価格順・新着順・いいね順対応）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'item-list.css'];
require 'header.php';
require 'header-menu.php';

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

// 検索条件
$keyword = $_GET['keyword'] ?? '';
$price   = $_GET['price'] ?? '';
$categories = $_GET['categories'] ?? [];

$sql = "
  SELECT 
    i.*, 
    im.image_path,
    COUNT(g.item_id) AS good_count
  FROM item i
  LEFT JOIN good g ON i.item_id = g.item_id AND g.is_delete = 0
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  WHERE 1=1
";
$params = [];

// キーワード検索
if ($keyword !== '') {
    $sql .= " AND i.item_name LIKE :keyword";
    $params[':keyword'] = "%$keyword%";
}

// 価格検索
if ($price !== '') {
    $sql .= " AND i.price >= :price";
    $params[':price'] = $price;
}

// カテゴリ検索
if (!empty($categories)) {
    $in = [];
    foreach ($categories as $index => $catId) {
        $key = ":cat$index";
        $in[] = $key;
        $params[$key] = $catId;
    }
 $sql .= " AND i.category_id IN (" . implode(',', $in) . ")";}

// GROUP BY + ORDER BY
$sql .= " GROUP BY i.item_id ORDER BY $orderBy";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
<!-- 商品一覧 -->
<div class="item-grid">
  <?php if (empty($items)): ?>
    <p>現在表示できる商品がありません。</p>
  <?php else:
    foreach ($items as $item): ?>
      <!-- 全体をリンク化 -->
      <a href="history-insert.php?item_id=<?= intval($item['item_id']) ?>" class="item-link">
        <div class="item">
          <div class="image-box">
            <img 
              src="item-image/<?= htmlspecialchars($item['image_path'] ?? 'no-image.png') ?>" 
              alt="商品画像">
          </div>
          <p><?= htmlspecialchars($item['item_name']) ?></p>
          <p>¥<?= number_format($item['price']) ?></p>
        </div>
      </a>
  <?php endforeach; endif; ?>
</div>

  </div>
</section>

<?php require 'footer-menu.php'; require 'footer.php'; ?>
