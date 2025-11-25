<?php
// ========================================
// いいね一覧画面（goodテーブルから取得）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db-connect.php';
$css_files = ['main-style.css', 'item-list.css']; // ← item-list.cssを共通利用
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

$sql = "
  SELECT 
    i.item_id, 
    i.item_name, 
    i.price, 
    im.image_path
  FROM good g
  JOIN item i ON g.item_id = i.item_id
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  WHERE g.is_delete = 0
";
$items = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section has-background-warning-light">
  <div class="container">
    <!-- タイトル -->
    <div class="title-area">
      <a href="#" onclick="history.back()" class="back-arrow">&lt;</a>
      <h3 class="title">いいね一覧</h3>
    </div>

    <!-- 商品カード一覧 -->
    <div class="item-grid">
      <?php if (empty($items)): ?>
        <p>現在「いいね」された商品はありません。</p>
      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <a href="item-detail.php?item_id=<?= htmlspecialchars($item['item_id']) ?>" class="item-link">
            <div class="item">
              <div class="image-box">
                <img src="item-image/<?= htmlspecialchars($item['image_path'] ?? 'no-image.png') ?>" alt="商品画像">
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
