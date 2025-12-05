<?php session_start(); ?>
<?php
$css_files = ['main-style.css', 'title.css', 'mypage.css'];
require 'header.php';
?>
<?php require 'db-connect.php'; ?>

<?php
$user_id = $_SESSION['user']['user_id'];
// ユーザー情報取得
$pdo = new PDO($connect, USER, PASS);
$sql1 = $pdo->prepare('select * from user_info where user_id=?');
$sql1->execute([$_GET['user']]);
$other_user = $sql1->fetch(PDO::FETCH_ASSOC);

// 遷移元取得
$from = $_GET['from'] ?? null;
$item_id = $_GET['item_id'] ?? null;
$other_user_id = $_GET['user'] ?? null;

if ($_SESSION['user']['role'] == 1) {
  $back_link = 'admin-home.php';
} else if ($from === 'mypage') {
  $back_link = 'mypage.php';
} else if ($from === 'other-user') {
  $back_link = 'other-user.php?user=' . urlencode($other_user_id)
    . '&item_id=' . urlencode($item_id)
    . '&from=' . urlencode($from);
} else {
  $back_link = 'item-list.php';
}

// フォロー済みチェック
$sql2 = $pdo->prepare("SELECT 1 FROM follow WHERE follower_id=? AND followed_id=?");
$sql2->execute([$user_id, $other_user['user_id']]);
$isFollowing = $sql2->fetch() ? true : false;
?>

<!--ページタイトル-->
<nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center" role="navigation" aria-label="main navigation">
  <a href="<?= $back_link ?>" id="back_button" class="button is-medium is-outlined">
    <span class="icon is-small">
      <i class="fas fa-angle-left"></i>
    </span>
  </a>
  <div class="navbar-center">
    <span class="title is-6"><?= $other_user['nickname'] ?></span>
  </div>
</nav>

<div class="content">

  <div id="icon" class="box mb-1">
    <div class="columns is-mobile is-vcentered p-1">
      <!-- アイコン -->
      <div class="column is-narrow ml-1">
        <figure id="user_icon" class="m-0">
          <?php
          if (empty($other_user['profile_image'])) {
            echo '<img src="user-icon/default.png" alt="ユーザー画像">';
          } else {
            echo '<img src="user-icon/' . htmlspecialchars($other_user['profile_image']) . '" alt="ユーザー画像">';
          }
          ?>
        </figure>
      </div>
      <!-- ニックネーム -->
      <div class="column p-0">
        <span class="title is-5 m-0"><?= $other_user['nickname'] ?></span>
      </div>
      <!-- フォローボタン -->
      <div class="column is-narrow ml-auto mt-1">
        <?php if ($_SESSION['user']['role'] == 0): ?>
          <div id="follow_app">
            <button class="button is-danger"
              :class="following ? 'is-danger' : 'is-outlined'"
              @click="toggleFollow">
              {{ following ? 'フォロー中' : '+フォロー' }}
            </button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="follow block">
    <?php
    // 出品数
    $sql3 = $pdo->prepare('
      select count(*) 
      from sell s
      join item i on s.item_id = i.item_id
      where s.user_id=? and i.is_deleted = 0
    ');
    $sql3->execute([$other_user['user_id']]);
    $sell_count = $sql3->fetchColumn();
    // フォロワー数
    $sql4 = $pdo->prepare('select count(*) from follow where followed_id=?');
    $sql4->execute([$other_user['user_id']]);
    $followed = $sql4->fetchColumn();
    // フォロー数
    $sql5 = $pdo->prepare('select count(*) from follow where follower_id=?');
    $sql5->execute([$other_user['user_id']]);
    $follower = $sql5->fetchColumn();
    ?>
    <span class="mr-3"><?= $sell_count ?> 出品</span>
    <a href="follower-list?user=<?= htmlspecialchars($other_user_id) ?>&from=other-user" class="mr-3"><?= $followed ?> フォロワー</a>
    <a href="follow-list?user=<?= htmlspecialchars($other_user_id) ?>&from=other-user"><?= $follower ?> フォロー中</a>
  </div>

  <!-- 自己紹介文 -->
  <div id="self_introduction_app">
    <div class="self_introduction block">
      <div v-for="(line, index) in visibleLines" :key="index">
        <span v-if="line !== ''">{{ line }}</span>
        <br v-else>
      </div>
      <div v-if="lines.length > 4">
        <a v-if="!expanded" @click="expanded = true" class="button">
          さらに表示
        </a>
        <a v-else @click="expanded = false" class="button">
          ×閉じる
        </a>
      </div>
    </div>
  </div>
  <hr class="has-background-grey-lighter">

  <!-- 出品一覧 -->
  <?php
  // 商品情報取得
  $sql6 = $pdo->prepare(
    "select i.item_id, 
            i.item_name, 
            i.price,
            i.is_sold as item_is_sold,
            i.category_id,
            img.image_path,
            s.user_id
     from sell s
     join item i on s.item_id = i.item_id and i.is_deleted = 0
     left join item_image img on i.item_id = img.item_id and img.show_home = 1
     where s.user_id = ?"
  );
  $sql6->execute([$other_user['user_id']]);
  $sells = $sql6->fetchAll(PDO::FETCH_ASSOC);
  // カテゴリ取得
  $sql7 = $pdo->prepare("
    select distinct c.category_id, c.category
    from sell s
    join item i on s.item_id = i.item_id and i.is_deleted = 0
    join category c on i.category_id = c.category_id
    where s.user_id = ?
  ");
  $sql7->execute([$other_user['user_id']]);
  $categories = $sql7->fetchAll(PDO::FETCH_ASSOC);
  ?>
  <div id="sell_app">
    <div class="block">
      <label class="checkbox">
        <input type="checkbox" v-model="onlyAvailable">
        販売中のみ表示
      </label>
    </div>
    <div class="block scroll">
      <button
        class="button is-rounded"
        :class="selectedCategory == 0 ? 'is-link' : ''"
        @click="selectedCategory = 0">
        すべて
      </button>
      <button
        v-for="cat in categories"
        :key="cat.category_id"
        class="button is-rounded"
        :class="selectedCategory == cat.category_id ? 'is-link' : ''"
        @click="selectedCategory = cat.category_id">
        {{ cat.category }}
      </button>
    </div>
    <div v-if="sell_list.length > 0">
      <div class="columns is-mobile is-multiline">
        <div class="column is-half-mobile is-one-quarter-desktop" v-for="item in sell" :key="item.item_id">
          <a :href="'item-detail.php?item_id=' + item.item_id + '&from=other-user' + '&user=' + item.user_id " class="item-link">
            <div class="card has-text-centered m-0">
              <div class="card-image is-flex is-justify-content-center p-3">
                <figure class="image is-96x96">
                  <img :src="'item-image/' + item.image_path">
                </figure>
              </div>
              <div class="card-content pt-2">
                <p class="title is-6 mb-5">{{item.item_name}}</p>
                <p class="subtitle is-6">¥{{item.price.toLocaleString()}}</p>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <p v-else class="m-3">出品している商品はありません</p>
  </div>


</div>

<script>
  window.INIT_FOLLOWING = <?= json_encode($isFollowing) ?>;
  window.FOLLOWED_ID = <?= json_encode($other_user['user_id']) ?>;
  window.PROFILE_TEXT = <?= json_encode($other_user['self_introduction'] ?? '', JSON_UNESCAPED_UNICODE); ?>;
  window.INIT_SELLS = <?= json_encode($sells) ?>;
  window.CATEGORIES = <?= json_encode($categories, JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script src="./script/follow.js"></script>
<script src="./script/self-introduction.js"></script>
<script src="./script/sell.js"></script>

<?php
if ($_SESSION['user']['role'] == 0) {
  require 'footer-menu.php';
}
?>
<?php require 'footer.php'; ?>