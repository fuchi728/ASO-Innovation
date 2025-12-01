<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}

// ========================================
// いいね一覧
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db-connect.php';
$css_files = ['main-style.css', 'item-list.css']; // ← SOLD デザインは item-list.css を共通利用
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

$user_id = $_SESSION['user']['user_id'];

// いいねした商品取得（is_sold＝SOLD判定も取る）
$sql = "
  SELECT
    i.item_id,
    i.item_name,
    i.price,
    i.is_sold,
    im.image_path
  FROM good g
  JOIN item i ON g.item_id = i.item_id
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  WHERE g.user_id = ? AND g.is_delete = 0 AND i.is_deleted = 0
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="section has-background-warning-light">
  <div class="container">

    <!-- タイトル -->
    <div class="title-area">
      <h1 class="title is-4">いいね一覧</h1>
    </div>

    <!-- 商品リスト -->
    <div class="item-grid">

      <?php if (empty($items)): ?>
        <p>現在「いいね」された商品はありません。</p>

      <?php else: ?>
        <?php foreach ($items as $item): ?>
          <a href="history-insert.php?item_id=<?= htmlspecialchars($item['item_id']) ?>" class="item-link">
            <div class="item">

              <!-- ★ SOLD タグ（item-list.php と同じ）-->
              <?php if ($item['is_sold'] == 1): ?>
                <span class="sold-tag">SOLD</span>
              <?php endif; ?>

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
