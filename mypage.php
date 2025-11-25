<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}

$css_files = ['main-style.css', 'title.css', 'mypage.css'];
require 'header.php';
?>
<?php require 'db-connect.php'; ?>

<!--ページタイトル-->
<div class="page_title">
  <span class="title is-6">マイページ</span>
</div>

<div class="content">
  <!-- ユーザー情報 -->
  <?php
  $pdo = new PDO($connect, USER, PASS);
  $sql1 = $pdo->prepare('select * from user_info where user_id=?');
  $sql1->execute([$_SESSION['user']['user_id']]);
  $user = $sql1->fetch(PDO::FETCH_ASSOC);
  ?>

  <div id="icon" class="box mb-1">
    <div class="columns is-mobile is-vcentered">
      <div class="column is-narrow p-0">
        <div class="is-flex is-align-items-center">
          <figure id="user_icon" class="m-3">
            <a href="sell-list.php">
              <?php
              if (empty($user['profile_image'])) {
                echo '<img src="user-icon/default.png" alt="ユーザー画像">';
              } else {
                echo '<img src="user-icon/' . htmlspecialchars($user['profile_image']) . '" alt="ユーザー画像">';
              }
              ?>
            </a>
          </figure>
        </div>
      </div>
      <div class="column">
        <a href="sell-list.php" class="title is-5 m-0">
          <?= $user['nickname'] ?>
        </a>
      </div>
      <div class="column is-narrow">
        <a href="mypage-edit.php">編集</a>
      </div>
    </div>
  </div>

  <div class="follow has-text-right block">
    <?php
    $sql2 = $pdo->prepare('select count(*) from follow where followed_id=?');
    $sql2->execute([$_SESSION['user']['user_id']]);
    $followed = $sql2->fetchColumn();

    $sql3 = $pdo->prepare('select count(*) from follow where follower_id=?');
    $sql3->execute([$_SESSION['user']['user_id']]);
    $follower = $sql3->fetchColumn();
    ?>
    <a href="follower-list">フォロワー：<?= $followed ?></a>　
    <a href="follow-list">フォロー中：<?= $follower ?></a>
  </div>

  <div class="block">
    <p>お名前：<?= htmlspecialchars($user['name']) ?></p>
    <p>住所：<?= htmlspecialchars($user['address']) ?></p>
    自己紹介：
    <div class="self_introduction block">
      <div id="self_introduction_app">
        <div v-for="(line, index) in visibleLines" :key="index">
          <span v-if="line !== ''">{{ line }}</span>
          <br v-else>
        </div>
        <a v-if="lines.length > 4 && !expanded" @click="expanded = true" class="button">
          さらに表示
        </a>
        <a v-if="expanded" @click="expanded = false" class="button">
          ×閉じる
        </a>
      </div>
    </div>
  </div>

  <!-- 閲覧履歴 -->
  <div id="history_app">
    <div id="history" class="box mb-3">
      <p class="title is-6">閲覧履歴</p>
      <div v-if="history.length > 0">
        <div class="columns is-mobile is-multiline">
          <div class="column is-half-mobile is-one-quarter-desktop" v-for="item in history" :key="item.item_id">
            <a :href="'item-detail.php?item_id=' + item.item_id + '&from=mypage'" class="item-link">
              <div class="card has-text-centered m-0">
                <div class="card-image is-flex is-justify-content-center p-3">
                  <figure class="image is-96x96">
                    <img :src="'item-image/' + item.image_path">
                  </figure>
                </div>
                <div class="card-content pt-2">
                  <p class="title is-6  mb-5">{{item.item_name}}</p>
                  <p class="subtitle is-6">¥{{item.price.toLocaleString()}}</p>
                </div>
              </div>
            </a>
          </div>
        </div>
        <div class="mt-3">
          <a v-if="total > 4 && !expanded" class="button" @click="showMore">...さらに表示</a>
          <a v-if="expanded" class="button" @click="closeMore">×閉じる</a>
        </div>
      </div>
      <p v-else class="m-3">閲覧履歴はありません</p>
    </div>
  </div>

  <!-- お問い合わせリンク -->
  <div class="has-text-right block">
    <a id="help_link" href="help.php">お問い合わせはこちらから＞</a>
  </div>

  <form action="logout.php">
    <button id="button" class="button is-fullwidth">ログアウト</button>
  </form>
</div>

<script>
  window.PROFILE_TEXT = <?= json_encode($user['self_introduction'] ?? '', JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script src="./script/history.js"></script>
<script src="./script/self-introduction.js"></script>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>