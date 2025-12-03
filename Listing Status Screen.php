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
        <span class="status-count">2件</span>
      </div>
    </div>
    </a>

    <!-- 取引中ステータスカード -->
    <a href="dm-list.php">
    <div class="status-card">
      <span class="status-icon">📦</span>
      <div class="status-info">
        <span class="status-title">取引中</span>
        <span class="status-count">1件</span>
      </div>
    </div>
    </a>

    <!-- 🔽 ここに移動（コンテナ内） -->
<button class="listing-button" onclick="location.href='https://aso2401373.oops.jp/2025/ASO-Innovation/mypage-selledit.php'">
  <span class="camera-icon">📸</span>
  出品する
</button>


  </div>
</div>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
