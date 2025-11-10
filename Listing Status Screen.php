<?php
$css_files = ['main-style.css','Listing_style.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>


  <!-- メインコンテンツを囲むコンテナ -->
   <div class="miya2">
  <div class="container">
    <!-- 出品中ステータスカード -->
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

    <!-- 取引中ステータスカード -->
    <div class="status-card">
      <span class="status-icon">📦</span> <!-- 絵文字をアイコンとして使用 -->
      <div class="status-info">
        <span class="status-title">取引中</span>
        <span class="status-count">1件</span>
      </div>
    </div>
  </div>

  <!-- 出品するボタン -->
  <button class="listing-button">
    <span class="camera-icon">📸</span> <!-- 絵文字をアイコンとして使用 -->
    出品する
  </button>
</div>
<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>