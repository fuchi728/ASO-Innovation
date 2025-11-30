<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
  header("Location: login.php");
  exit;
}
$item_id = $_POST['item_id'] ?? null;
if (!$item_id) {
  header("Location: item-list.php");
  exit;
}
// ========================================
// 購入画面（buyテーブル利用）
// ========================================
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'db-connect.php';

$css_files = ['main-style.css', 'purchase.css','title.css'];
require 'header.php';
require 'header-menu.php';

$pdo = new PDO($connect, USER, PASS);

// ----------------------------------------
// 購入処理
// ----------------------------------------
$user_id = $_SESSION['user']['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $item_id = $_POST['item_id'] ?? null;
  $address = $_POST['delivery_address'] ?? '';
  $method  = $_POST['payment_method'] ?? '';
  $count   = $_POST['payment_count'] ?? '';

  if ($item_id && $address) {
    // 1️⃣ 購入情報をbuyテーブルに追加
    $sql = $pdo->prepare('
      INSERT INTO buy (item_id, user_id, delivery_address)
      VALUES (?, ?, ?)
    ');
    $sql->execute([$item_id, $user_id, $address]);

    // 2️⃣ itemテーブルのis_soldを1（購入済み）に更新
    $pdo->prepare('UPDATE item SET is_sold = 1 WHERE item_id = ?')
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

$sql = $pdo->prepare('
  SELECT i.item_id, i.item_name, i.price, im.image_path
  FROM item i
  LEFT JOIN item_image im ON i.item_id = im.item_id AND im.show_home = 1
  WHERE i.item_id = ? AND i.is_sold = 0
');
$sql->execute([$item_id]);
$item = $sql->fetch(PDO::FETCH_ASSOC);

$shipping_fee = 500;
$total_price = $item ? $item['price'] + $shipping_fee : 0;
?>

<section class="section has-background-warning-light">
  <div class="container">

    <nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center" role="navigation" aria-label="main navigation">
      <a href="item-detail.php?item_id=<?= htmlspecialchars($item_id) ?>" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small">
          <i class="fas fa-angle-left"></i>
        </span>
      </a>
      <div class="navbar-center">
        <span class="title is-6">購入手続き</span>
      </div>
    </nav>

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

<?php require 'footer-menu.php';
require 'footer.php'; ?>