<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}
$css_files = ['main-style.css','Listing_style.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>
<?php require 'db-connect.php'; ?>
<?php
$user_id = $_SESSION['user']['user_id'];

$pdo = new PDO($connect, USER, PASS);

// 出品件数
$sql = $pdo->prepare("SELECT COUNT(*) FROM sell WHERE user_id = ? AND is_delete = 0");
$sql->execute([$user_id]);
$sell_count = $sql->fetchColumn();

// 取引件数
$sql = $pdo->prepare("
(
    SELECT s.item_id
    FROM sell s
    JOIN buy b ON b.item_id = s.item_id AND b.is_delete = 0
    WHERE s.user_id = :uid AND i.is_delete = 0
)
UNION ALL
(
    SELECT b.item_id
    FROM buy b
    JOIN sell s ON s.item_id = b.item_id AND s.is_delete = 0
    WHERE b.user_id = :uid AND i.is_delete = 0
)
");
$sql->execute(['uid' => $user_id]);
$trade_count = $sql->rowCount();
?>


<!-- メインコンテンツを囲むコンテナ -->
<div class="miya2">
  <div class="container">

    <!-- 出品中ステータスカード -->
    <a href="sell-list.php">
    <div class="status-card">
      <div class="status-icon grid-icon">
        <div class="grid-square"></div>
        <div class="grid-square"></div>
        <div class="grid-square"></div>
        <div class="grid-square"></div>
      </div>
      <div class="status-info">
        <span class="status-title">出品中</span>
        <span class="status-count"><?= $sell_count ?>件</span>
      </div>
    </div>
    </a>

    <!-- 取引中ステータスカード -->
    <a href="dm-list.php">
    <div class="status-card">
      <span class="status-icon">📦</span>
      <div class="status-info">
        <span class="status-title">取引中</span>
        <span class="status-count"><?= $trade_count ?>件</span>
      </div>
    </div>
    </a>

    <!-- 🔽 ここに移動（コンテナ内） -->
<button class="listing-button" onclick="location.href='mypage-selledit.php'">
  <span class="camera-icon">📸</span>
  出品する
</button>


  </div>
</div>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
