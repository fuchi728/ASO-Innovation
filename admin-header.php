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
<header class="admin-header">

    <!-- 上段：ロゴ＋タイトル＋検索 -->
    <div class="admin-header-top">
        <div class="admin-title-area">
            <img src="logo-image/Vanikaロゴ.png" class="admin-logo">
            <span class="admin-title">管理者画面 - トップページ -</span>
        </div>

        <div class="admin-search-icon">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <!-- 下段メニュー -->
    <nav class="admin-menu">
        <a class="admin-tab" href="admin-home.php">ホーム</a>
        <a class="admin-tab" href="#">NEWS</a>
        <a class="admin-tab" href="admin-account-del1.php">アカウント削除申請</a>
        <a class="admin-tab" href="admin-contact1.php">お問い合わせ</a>
    </nav>

</header>

<!-- 固定ヘッダーのぶん余白 -->
<div class="admin-header-space"></div>
