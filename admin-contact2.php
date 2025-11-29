<?php
session_start();
require 'db-connect.php';

$css_files = ['main-style.css', 'admin-header.css', 'admin-contact-style.css'];
require 'admin-header.php';

$pdo = new PDO($connect, USER, PASS);

// GET で help_id を取得
$help_id = $_GET['help_id'] ?? 0;

$sql = $pdo->prepare("
    SELECT h.*, ui.nickname 
    FROM help AS h
    LEFT JOIN user_info AS ui ON h.user_id = ui.user_id
    WHERE h.help_id = ?
");
$sql->execute([$help_id]);
$request = $sql->fetch(PDO::FETCH_ASSOC);

if (!$request) {
  echo '<section class="section py-4"><div class="container">';
  echo '<p class="has-text-centered has-text-grey">対象の問い合わせは存在しません。</p>';
  echo '</div></section>';
  require 'footer.php';
  exit;
}

// 日付と時間に分割
$request_date = date('Y/m/d', strtotime($request['send_time']));
$request_time = date('H:i:s', strtotime($request['send_time']));
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
            <a href="admin-contact1.php" class="button  is-warning">一覧に戻る</a>
          </div>

          <div class="mt-2 has-text-centered">
            <?php if ($request['is_deal'] == 0): ?>
              <form method="post" action="admin-contact3.php">
                <input type="hidden" name="help_id" value="<?= htmlspecialchars($help_id) ?>">
                <button type="submit" class="button is-success">対応完了</button>
              </form>
            <?php else: ?>
              <button class="button is-success" disabled>対応済</button>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- 右カラム：問い合わせ内容全文 -->
      <div class="column notification" id="rigth-display">
        <p class="is-size-4"><strong>内容：</strong></p>
        <p class="is-size-4"><?= nl2br(htmlspecialchars($request['content'])) ?></p>
      </div>

    </div>
  </div>
</section>

<?php require 'footer.php'; ?>