<?php
// =============================
// DB接続設定（ロリポップ MySQL）
// =============================
$dsn = 'mysql:host=mysql327.phy.lolipop.lan;dbname=LAA1607954-aso;charset=utf8';
$user = 'LAA1607954';
$password = 'innovation';  // DBパスワード

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // echo "DB接続成功"; // ← 確認用に一時的に有効化してもOK
} catch (PDOException $e) {
    exit('DB接続エラー: ' . $e->getMessage());
}
?>

<!-- ===== メイン ===== -->
<section class="section has-background-warning-light">
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


show-homeの1をホームの画像に当てはめる