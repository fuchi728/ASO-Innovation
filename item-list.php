<?php
// ========================================
// 商品一覧（検索・ソート対応 / SOLD表示 / いいね数対応）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
 
require_once 'db-connect.php';
 
$css_files = ['main-style.css', 'item-list.css'];
require 'header.php';
require 'header-menu.php';
 
$pdo = new PDO($connect, USER, PASS);
 
// ソート取得
$order = $_GET['sort'] ?? 'price_asc';
switch ($order) {
  case 'price_desc': $orderBy = 'i.price DESC'; break;
  case 'id_desc':    $orderBy = 'i.item_id DESC'; break;
  case 'like_desc':  $orderBy = 'good_count DESC'; break;
  default:           $orderBy = 'i.price ASC';
}
 
// ====== 検索条件 ======
$keyword     = $_GET['keyword'] ?? '';
$price_min   = $_GET['price_min'] ?? null;
$price_max   = $_GET['price_max'] ?? null;
$categories  = $_GET['categories'] ?? [];
 
$sql = "
  SELECT
    i.*,
    im.image_path,
    COUNT(g.item_id) AS good_count
  FROM item i
  LEFT JOIN good g ON i.item_id = g.item_id AND g.is_delete = 0
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  WHERE 1=1 AND i.is_deleted = 0
";
 
$params = [];
 
// キーワード検索
if ($keyword !== '') {
    $sql .= " AND i.item_name LIKE :keyword";
    $params[':keyword'] = "%$keyword%";
}
 
// 最小価格
if ($price_min !== null && $price_min !== '') {
    $sql .= " AND i.price >= :price_min";
    $params[':price_min'] = $price_min;
}
 
// 最大価格
if ($price_max !== null && $price_max !== '') {
    $sql .= " AND i.price <= :price_max";
    $params[':price_max'] = $price_max;
}
 
// カテゴリ
if (!empty($categories)) {
    $in = [];
    foreach ($categories as $index => $catId) {
        $key = ":cat$index";
        $in[] = $key;
        $params[$key] = $catId;
    }
    $sql .= " AND i.category_id IN (" . implode(',', $in) . ")";
}

// 販売中のみ
if (!empty($_GET['onsale']) && $_GET['onsale'] == 1) {
    $sql .= " AND i.is_sold = 0";
}
 
$sql .= " GROUP BY i.item_id ORDER BY $orderBy";
 
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$from = 'item-list';
?>
 
<section class="section has-background-warning-light">
  <div class="container">
 
    <!-- ソートメニュー（中央寄せ） -->
    <div class="sort-bar" style="text-align:center; margin-bottom:15px;">
      <div class="sort-center" style="display:inline-block; position:relative;">
        <select id="sortSelect" onchange="location.href='?sort='+this.value;" class="select is-rounded">
          <option value="price_asc"  <?= $order=='price_asc'?'selected':'' ?>>価格順（安い順）</option>
          <option value="price_desc" <?= $order=='price_desc'?'selected':'' ?>>価格順（高い順）</option>
          <option value="id_desc"    <?= $order=='id_desc'?'selected':'' ?>>新着順</option>
          <option value="like_desc"  <?= $order=='like_desc'?'selected':'' ?>>いいね順</option>
        </select>
        <i class="fas fa-sort-amount-up" style="margin-left:8px;"></i>
      </div>
    </div>
 
    <!-- 商品一覧 -->
    <div class="item-grid">
 
      <?php if (empty($items)): ?>
        <p>現在表示できる商品がありません。</p>
 
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <!-- like-list.php と同じ構造 -->
          <a href="history-insert.php?item_id=<?= intval($item['item_id'])?>&from=<?= $from ?>" class="item-link">
            <div class="item">
 
              <!-- SOLD タグ -->
              <?php if ($item['is_sold'] == 1): ?>
                <span class="sold-tag">SOLD</span>
              <?php endif; ?>
 
              <div class="image-box">
                <img 
                  src="item-image/<?= htmlspecialchars($item['image_path'] ?? 'no-image.png') ?>" 
                  alt="商品画像">
              </div>
 
              <p><?= htmlspecialchars($item['item_name']) ?></p>
              <p>¥<?= number_format($item['price']) ?></p>
 
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
 
    </div>
 
  </div>
</section>
 
<?php require 'footer-menu.php'; require 'footer.php'; ?>
 