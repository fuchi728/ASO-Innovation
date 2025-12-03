<?php
$css_files = ['main-style.css', 'notice-style.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>

<div class="tabs is-fullwidth">
  <ul>
    <li class="is-active"><a href="notice-1.php">お知らせ</a></li>
    <li><a href="notice-2.php">NEWS</a></li>
  </ul>
</div>

<section class="section py-4">
  <div class="container">
    <div class="box notice-box is-flex is-justify-content-space-between is-align-items-center">
      <div class="is-flex is-flex-direction-column">
        <p>コメントが届きました。</p>
        <p class="is-size-7 has-text-grey">2025-11-06 10:00:00</p>
      </div>
      <span class="icon is-medium has-text-grey">
        <i class="fas fa-angle-right"></i>
      </span>
    </div>
  </div>
</section>

<section class="section py-4">


<section class="section py-4">
  <div class="container">
    <div class="box notice-box is-flex is-justify-content-space-between is-align-items-center">
      <div class="is-flex is-flex-direction-column">
        <p>メッセージが届きました。</p>
        <p class="is-size-7 has-text-grey">2025-11-08 10:19:58</p>
      </div>
      <span class="icon is-medium has-text-grey">
        <i class="fas fa-angle-right"></i>
      </span>
    </div>
  </div>
</section>

<section class="section py-4">
  <div class="container">
    <div class="box notice-box is-flex is-justify-content-space-between is-align-items-center">
      <div class="is-flex is-flex-direction-column">
        <p>商品が購入されました。</p>
        <p class="is-size-7 has-text-grey">2025-11-06 16:07:21</p>
      </div>
      <span class="icon is-medium has-text-grey">
        <i class="fas fa-angle-right"></i>
      </span>
    </div>
  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>