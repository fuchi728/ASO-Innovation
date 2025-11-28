<?php
session_start();
require 'db-connect.php';

// // 管理者ログインチェック（必要なら）
// if (!isset($_SESSION['admin'])) {
//     header("Location: admin-login.php");
//     exit;
// }

$css_files = ['main-style.css', 'admin-header.css', 'admin-account-del-style.css'];
require 'admin-header.php';

$pdo = new PDO($connect, USER, PASS);

// is_deal = 0（未対応）だけ取得
$sql = $pdo->prepare("
    SELECT dr.*, ui.nickname 
    FROM delete_request AS dr
    LEFT JOIN user_info AS ui ON dr.user_id = ui.user_id
    WHERE dr.is_deal = 0
    ORDER BY dr.request_time DESC
");
$sql->execute();
$requests = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 未対応の削除申請が存在しない場合 -->
<?php if (count($requests) === 0): ?>
    <section class="section py-4">
        <div class="container">
            <p class="has-text-centered has-text-grey">
                現在、未処理のアカウント削除申請はありません。
            </p>
        </div>
    </section>

<?php else: ?>
    <?php foreach ($requests as $req): ?>
        <section class="section py-4">
            <div class="container">

                <div class="box notice-box is-flex is-justify-content-space-between is-align-items-center">

                    <!-- 左側（申請内容） -->
                    <div class="is-flex is-flex-direction-column">

                        <div style="display: flex; align-items: center; gap: 8px;">
                            <p><strong>ユーザー：</strong>
                            <figure id="user_icon">
                                <?php
                                if (empty($user['profile_image'])) {
                                    echo '<img src="user-icon/default.png" alt="ユーザー画像">';
                                } else {
                                    echo '<img src="user-icon/' . htmlspecialchars($user['profile_image']) . '" alt="ユーザー画像">';
                                }
                                ?>
                            </figure>

                            <?= htmlspecialchars($req['nickname'] ?? ('ID:' . $req['user_id'])) ?></p>

                        </div>

                        <p class="is-size-7 has-text-grey">
                            <strong>申請日時：</strong>
                            <?= htmlspecialchars($req['request_time']) ?>
                        </p>

                        <?php
                        // $reason に退会理由が入っている場合
                        $max_length = 50; // リスト上で表示する最大文字数
                        $reason = $req['reason'] ?? '';
                        $display_reason = mb_strlen($reason) > $max_length ? mb_substr($reason, 0, $max_length) . '…' : $reason;
                        ?>
                        <p class="is-size-7 has-text-grey">
                            <strong>申請理由：</strong>
                            <?= htmlspecialchars($display_reason); ?>
                        </p>
                    </div>

                    <!-- 右側（詳細ボタン） -->
                    <span>
                        <a
                            href="admin-account-del2.php?delete_id=<?= urlencode($req['delete_id']) ?>"
                            class="button is-warning">
                            詳細を確認
                        </a>
                    </span>

                </div>

            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<!-- フッターリンク、不要なら削除 -->
<?php require 'footer.php'; ?>