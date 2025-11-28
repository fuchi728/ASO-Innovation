<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanika 管理者画面</title>

    <!-- Icon / Bulma / FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

    <!-- 個別CSS -->
    <?php foreach ($css_files as $css): ?>
        <link rel="stylesheet" href="css/<?= $css ?>">
    <?php endforeach; ?>
</head>

<body>

    <!-- ===== 管理者ヘッダー ===== -->
    <div id="app">
        <header class="admin-header">

            <!-- 上段：ロゴ＋タイトル＋検索 -->
            <div class="admin-header-top">
                <div class="admin-title-area">
                    <img src="logo-image/Vanikaロゴ.png" class="admin-logo">
                    <span class="admin-title">管理者画面 - トップページ -</span>
                </div>

                <div class="admin-search-icon" @click.prevent="toggleSearch">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- 下段メニュー -->
            <nav class="admin-menu">
                <a class="admin-tab" href="admin-home.php">ホーム</a>
                <a class="admin-tab" href="admin-news.php">NEWS</a>
                <a class="admin-tab" href="admin-delete-request.php">アカウント削除申請</a>
                <a class="admin-tab" href="admin-contact.php">お問い合わせ</a>
            </nav>

        </header>

        <!-- 固定ヘッダーのぶん余白 -->
        <div class="admin-header-space"></div>

        <!-- 検索欄 -->
        <div v-if="showSearch" class="search-bar box">
            <form action="admin-home.php" method="GET">
                <p class="title is-6 has-text-centered">条件を入力して下さい</p>
                <div class="field">
                    <p class="control has-icons-right">
                        <input class="input is-rounded is-fullwidth" type="text" placeholder="商品を検索する" name="keyword" value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                    </p>
                </div>
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