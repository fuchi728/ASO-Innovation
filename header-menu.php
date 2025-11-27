<div id="app">
  <nav class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center has-background-warning" role="navigation" aria-label="main navigation">
    <div class="navbar-brand ml-5">
      <a class="navbar-item" href="#">
        <img src="logo-image/Vanikaロゴ.png">
      </a>
    </div>
    <div class="navbar-end is-flex is-align-items-center is-flex-wrap-nowrap mr-5">
      <a class="navbar-item" @click.prevent="toggleSearch">
        <span class="icon">
          <i class="fas fa-search"></i>
        </span>
      </a>
      <a class="navbar-item" href="#">
        <span class="icon">
          <i class="fas fa-bell"></i>
        </span>
      </a>
    </div>
  </nav>

  <!-- 検索欄 -->
  <div v-if="showSearch" class="search-bar box">
    <form action="item-list.php" method="GET">
      <p class="title is-6 has-text-centered">条件を入力して下さい</p>
      <button class="close-btn" @click="showSearch = false">
        <i class="fas fa-times"></i>
      </button>
      <div class="field">
        <p class="control has-icons-right">
          <input class="input is-rounded is-fullwidth" type="text" placeholder="商品を検索する" name="keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
        </p>
      </div>

      <div class="field">
        <label class="label">価格</label>
        <div class="field is-grouped">
          <div class="control is-expanded">
            <input
              class="input is-rounded"
              type="number"
              name="price_min"
              placeholder="最小価格"
              value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>">
          </div>
          <div class="control">
            <span>〜</span>
          </div>
          <div class="control is-expanded">
            <input
              class="input is-rounded"
              type="number"
              name="price_max"
              placeholder="最大価格"
              value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>">
          </div>
        </div>
      </div>
      <!-- <div class="field">
        <label class="label">価格</label>
        <div class="control">
          <div class="select is-fullwidth">
            <select name="price">
              <option value="" disabled>価格帯を選ぶ</option>
              <option value="0" <?= (isset($_GET['price']) && $_GET['price'] === "0") ? 'selected' : '' ?>>0~</option>
              <option value="1000" <?= (isset($_GET['price']) && $_GET['price'] === "1000") ? 'selected' : '' ?>>1000~</option>
              <option value="5000" <?= (isset($_GET['price']) && $_GET['price'] === "5000") ? 'selected' : '' ?>>5000~</option>
            </select>
          </div>
        </div>
      </div> -->
      <?php
      require_once 'db-connect.php';
      $pdo = new PDO($connect, USER, PASS);
      // カテゴリ取得
      $sql = $pdo->query("select * from category order by category_id");
      $categories = $sql->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div class="field">
        <label class="label">カテゴリ</label>
        <div class="control">
          <?php
          foreach ($categories as $c) {
            $checked = '';
            if (!empty($_GET['categories']) && in_array($c['category_id'], $_GET['categories'])) {
              $checked = 'checked';
            }
            echo '<label class="checkbox is-block mb-3">';
            echo '<input type="checkbox" name="categories[]" value="' . $c['category_id'] . '"' . $checked . '>' . $c['category'];
            echo '</label>';
          }
          ?>
        </div>
      </div>

      <div class="field mt-4 has-text-centered">
        <button id="button" class="button is-info" type="submit">検索</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.js"></script>
<script src="./script/search.js"></script>