<?php session_start(); ?>
<?php
$css_files = ['main-style.css', 'title.css', 'mypage.css'];
require 'header.php';
?>
<?php require 'db-connect.php'; ?>

<!--ページタイトル-->
<div class="page_title">
  <span class="title is-6">マイページ</span>
</div>

<div class="content">

  <div class="box">
    <div class="columns is-mobile is-vcentered">
      <div class="column is-narrow p-0">
        <div class="is-flex is-align-items-center">
          <figure id="user_icon" class="image is-64x64 m-3">
            <a href="#">
              <img class="is-rounded" src="user_icon/default.png" alt="ユーザー画像">
            </a>
          </figure>
        </div>
      </div>
      <div class="column">
        <a href="#" class="title is-5 m-0">ニックネーム</a>
      </div>
      <div class="column is-narrow">
        <a href="mypage-edit.php">編集</a>
      </div>
    </div>
  </div>

  <div class="has-text-right">
    フォロワー：
    フォロー中：
  </div>

  <div class="block">
    お名前：<br>
    住所：<br>
    自己紹介：

  </div>

  <!-- 閲覧履歴 -->
  <div id="history" class="box">
    <p class="title is-6">閲覧履歴</p>

    <a class="button">...さらに表示</a>
  </div>

  <div class="has-text-right block">
    <a id="help_link" href="help.php">お問い合わせはこちらから＞</a>
  </div>

  <form action="logout.php">
    <button id="button" class="button is-fullwidth">ログアウト</button>
  </form>
</div>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>