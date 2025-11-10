<?php
// ========================================
// 商品一覧画面（ソート付き）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'db-connect.php';
<?php
$css_files = ['main-style.css'];
require 'header.php';
require 'header-menu.php';
?>

<!-- Font Awesome 読み込み（アイコン用） -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="item-list.css">

<section class="section has-background-warning-light">
  <div class="container">

    <!-- ソートメニュー -->
    <div class="sort-bar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
      <select id="sortSelect" style="padding:6px 10px;border-radius:6px;border:1px solid #ccc;">
        <option value="price_asc">価格順（安い順）</option>
        <option value="price_desc">価格順（高い順）</option>
        <option value="id_desc">新着順</option>
      </select>

      <!-- 🔽 Font Awesomeアイコン（リンクなし） -->
      <i class="fas fa-sort-amount-up" style="font-size:24px;color:#333;"></i>
    </div>

    <!-- 商品一覧 -->
    <div class="item-grid">
      <?php
        // DB接続とデータ取得
        $pdo = new PDO($connect, USER, PASS);
        $order = $_GET['sort'] ?? 'price_asc';

        // ソート条件を切り替え
        switch ($order) {
          case 'price_desc':
            $sql = 'SELECT * FROM item ORDER BY price DESC';
            break;
          case 'id_desc':
            $sql = 'SELECT * FROM item ORDER BY item_id DESC';
            break;
          default:
            $sql = 'SELECT * FROM item ORDER BY price ASC';
            break;
        }

        // SQL実行
        $items = $pdo->query($sql)->fetchAll();

        // 出力処理
        if (empty($items)) {
          echo '<p>現在表示できる商品がありません。</p>';
        } else {
          foreach ($items as $item) {
            echo '<div class="item">';
            echo '<img src="item-image/no-image.png" alt="商品画像">';
            echo '<p>' . htmlspecialchars($item['item_name']) . '</p>';
            echo '<p>¥' . number_format($item['price']) . '</p>';
            echo '</div>';
          }
        }
      ?>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
