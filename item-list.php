<?php
// =========================
// 商品一覧画面（Vanika）
// =========================

// ソート順選択の処理
$order = isset($_GET['order']) ? $_GET['order'] : 'price';

// 仮の商品データ（DB接続前の仮データ）
$items = [
  ['price' => 1000, 'image' => 'sample1.jpg'],
  ['price' => 500,  'image' => 'sample2.jpg'],
  ['price' => 1000, 'image' => 'sample3.jpg'],
  ['price' => 500,  'image' => 'sample4.jpg'],
];

// 価格順ソート処理
if ($order === 'price') {
  usort($items, function ($a, $b) {
    return $a['price'] <=> $b['price'];
  });
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vanika - 商品一覧</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background-color: #f5e1c4;
      padding-top: 60px;   /* ヘッダー分 */
      padding-bottom: 80px; /* フッター分 */
    }
    .item-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-top: 20px;
    }
    .item {
      background-color: #e0e0e0;
      text-align: center;
      padding: 20px 0;
    }
    .bottom-nav a {
      text-align: center;
      color: #000;
    }
    .bottom-nav i {
      display: block;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>

<!-- 共通ヘッダー -->
<?php include('header.php'); ?>

<!-- ===== メイン ===== -->
<section class="section">
  <div class="container">

    <!-- ソート -->
    <div class="field has-addons is-justify-content-flex-end">
      <div class="control">
        <div class="select is-small">
          <select onchange="location.href='?order='+this.value">
            <option value="price" <?= $order==='price'?'selected':'' ?>>価格順</option>
          </select>
        </div>
      </div>
      <div class="control">
        <button class="button is-small">
          <i class="fas fa-sort"></i>
        </button>
      </div>
    </div>

    <!-- 商品一覧 -->
    <div class="item-grid">
      <?php foreach ($items as $item): ?>
        <div class="item">
          <p>写真</p>
          <p>¥<?= number_format($item['price']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- 共通フッター -->
<?php include('footer.php'); ?>

</body>
</html>
