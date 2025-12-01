<?php
session_start();
require 'db-connect.php';

$page_title = '問い合わせ';
$css_files = ['main-style.css', 'admin-header.css', 'admin-contact-style.css'];
require 'admin-header.php';

$pdo = new PDO($connect, USER, PASS);

// is_deal = 0（未対応）だけ取得
$sql = $pdo->prepare("
    SELECT h.*, ui.nickname 
    FROM help AS h
    LEFT JOIN user_info AS ui ON h.user_id = ui.user_id
    WHERE h.is_deal = 0
    ORDER BY h.send_time DESC
");
$sql->execute();
$requests = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 未対応の削除申請が存在しない場合 -->
<?php if (count($requests) === 0): ?>
    <section class="section py-4">
        <div class="container">
            <p class="has-text-centered has-text-grey">
                現在、未対応の問い合わせはありません。
            </p>
        </div>
    </section>

<?php else: ?>
    <?php foreach ($requests as $req): ?>
        <section class="section py-4">
            <div class="container">

                <div class="box notice-box is-flex is-justify-content-space-between is-align-items-center">

                    <!-- 左側（問い合わせ内容） -->
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
                            <strong>送信日時：</strong>
                            <?= htmlspecialchars($req['send_time']) ?>
                        </p>

                        <?php
                        $max_length = 50; // リスト上で表示する最大文字数
                        $content = $req['content'] ?? '';
                        $display_content = mb_strlen($content) > $max_length ? mb_substr($content, 0, $max_length) . '…' : $content;
                        ?>
                        <p class="is-size-7 has-text-grey">
                            <strong>送信内容：</strong>
                            <?= htmlspecialchars($display_content); ?>
                        </p>
                    </div>

                    <!-- 右側（詳細ボタン） -->
                    <span>
                        <a
                            href="admin-contact2.php?help_id=<?= urlencode($req['help_id']) ?>"
                            class="button is-warning">
                            詳細を確認
                        </a>
                    </span>

                </div>

            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?php require 'footer.php'; ?>