   <?php
// ========================================
// 購入画面（buyテーブル利用）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'purchase.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// ----------------------------------------
// 購入処理
// ----------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $item_id = $_POST['item_id'] ?? null;
  $address = $_POST['delivery_address'] ?? '';
  $method  = $_POST['payment_method'] ?? '';
  $count   = $_POST['payment_count'] ?? '';

  if ($item_id && $address) {
    // 1️⃣ 購入情報をbuyテーブルに追加
    $sql = $pdo->prepare('
      INSERT INTO buy (item_id, user_id, buy_time, delivery_address, is_delete)
      VALUES (?, 1, NOW(), ?, 0)
    ');
    $sql->execute([$item_id, $address]);

    // 2️⃣ itemテーブルのis_deleteを1（購入済み）に更新
    $pdo->prepare('UPDATE item SET is_delete = 1 WHERE item_id = ?')
        ->execute([$item_id]);

    echo '
      <section class="section has-background-warning-light">
        <div class="container has-text-centered">
          <h2 class="title is-4">購入が完了しました！</h2>
          <p>ご購入ありがとうございます。</p>
          <br>
          <a href="item-list.php" class="button is-warning">商品一覧に戻る</a>
        </div>
      </section>
    ';
    require 'footer-menu.php';
    require 'footer.php';
    exit;
  }
}

// ----------------------------------------
// 商品情報取得（item＋item_image）
// ----------------------------------------
$item_id = $_GET['item_id'] ?? 1;
$sql = $pdo->prepare('
  SELECT i.item_id, i.item_name, i.price, im.image_path
  FROM item i
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  WHERE i.item_id = ?
');
$sql->execute([$item_id]);
$item = $sql->fetch(PDO::FETCH_ASSOC);

$shipping_fee = 500;
$total_price = $item ? $item['price'] + $shipping_fee : 0;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="css/purchase.css">

<section class="section has-background-warning-light">
  <div class="container">

    <!-- タイトル行 -->
    <div class="title-bar">
      <a href="item-detail.php?item_id=<?= htmlspecialchars($item_id) ?>" class="back-arrow">＜</a>
      <h2 class="title is-5">購入手続き</h2>
    </div>

    <!-- 商品概要 -->
    <div class="item-summary">
      <figure class="image-box">
        <img src="item-image/<?= htmlspecialchars($item['image_path'] ?? 'no-image.png') ?>" alt="商品画像">
      </figure>
      <div class="item-info">
        <p class="item-name"><?= htmlspecialchars($item['item_name']) ?></p>
        <p class="item-price">¥<?= number_format($item['price']) ?></p>
      </div>
    </div>

    <!-- 入力フォーム -->
    <form method="post">
      <input type="hidden" name="item_id" value="<?= htmlspecialchars($item_id) ?>">

      <div class="field">
        <label class="label">配送先住所 <span class="required">※必須</span></label>
        <input class="input" type="text" name="delivery_address" placeholder="〇〇県〇〇市〇〇区" required>
      </div>

      <div class="field">
        <label class="label">支払い方法 <span class="required">※必須</span></label>
        <div class="select is-fullwidth">
          <select name="payment_method" required>
            <option value="クレジットカード" selected>クレジットカード</option>
            <option value="銀行振込">銀行振込</option>
            <option value="コンビニ払い">コンビニ払い</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label class="label">支払い回数</label>
        <div class="select is-fullwidth">
          <select name="payment_count">
            <option value="1回払い" selected>1回払い</option>
            <option value="3回払い">3回払い</option>
            <option value="6回払い">6回払い</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label class="label">送料</label>
        <p class="fee">¥<?= number_format($shipping_fee) ?></p>
      </div>

      <div class="field">
        <label class="label">合計金額</label>
        <p class="total">¥<?= number_format($total_price) ?></p>
      </div>

      <div class="field">
        <button type="submit" class="button is-warning is-fullwidth is-medium">購入する</button>
      </div>
    </form>
  </div>
</section>

<?php require 'footer-menu.php'; require 'footer.php'; ?>
