<?php
session_start();
require 'db-connect.php';

$css_files = ['main-style.css', 'admin-header.css', 'admin-account-del-style.css'];
require 'admin-header.php';

$pdo = new PDO($connect, USER, PASS);

// GET で delete_id を取得
$delete_id = $_GET['delete_id'] ?? 0;

// delete_request と user_info を JOIN して該当申請を取得
$sql = $pdo->prepare("
    SELECT dr.*, ui.nickname 
    FROM delete_request AS dr
    LEFT JOIN user_info AS ui ON dr.user_id = ui.user_id
    WHERE dr.delete_id = ?
");
$sql->execute([$delete_id]);
$request = $sql->fetch(PDO::FETCH_ASSOC);

if (!$request) {
  echo '<section class="section py-4"><div class="container">';
  echo '<p class="has-text-centered has-text-grey">対象のアカウント削除申請は存在しません。</p>';
  echo '</div></section>';
  require 'footer.php';
  exit;
}

// 日付と時間に分割
$request_date = date('Y/m/d', strtotime($request['request_time']));
$request_time = date('H:i:s', strtotime($request['request_time']));
?>

<section class="section py-4">
  <div class="container">
    <div class="columns">

      <!-- 左カラム -->
      <div class="column is-half">
        <div class="box" id="left-display">
          <br>
          <p class="is-size-4"><strong>ユーザー：</strong><?= htmlspecialchars($request['nickname'] ?? ('ID:' . $request['user_id'])) ?></p>
          <p class="is-size-4"><strong>送信日　：</strong><?= htmlspecialchars($request_date) ?></p>
          <p class="is-size-4"><strong>送信時間：</strong><?= htmlspecialchars($request_time) ?></p>
          <br>
          <br>

          <div class="mt-4 has-text-centered">
            <a href="admin-account-del1.php" class="button  is-warning">一覧に戻る</a>
          </div>

          <div class="mt-2 has-text-centered">
            <?php if ($request['is_deal'] == 0): ?>
              <form method="post" action="admin-account-del3.php">
                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($delete_id) ?>">
                <button type="submit" class="button is-success">対応完了</button>
              </form>
            <?php else: ?>
              <button class="button is-success" disabled>対応済</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- 右カラム：退会理由全文 -->
      <div class="column notification" id="rigth-display">
        <p class="is-size-4"><strong>退会理由：</strong></p>
        <p class="is-size-4"><?= nl2br(htmlspecialchars($request['reason'])) ?></p>
      </div>

    </div>
  </div>
</section>

<?php require 'footer.php'; ?>