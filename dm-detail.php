<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>

<?php
$css_files = ['main-style.css', 'dm-detail-style.css'];
require 'header.php';
?>

<nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center">
    <a href="dm-list.php" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small"><i class="fas fa-angle-left"></i></span>
    </a>
    <div class="navbar-center">
        <span class="title is-6">メッセージ</span>
    </div>
</nav>

<?php
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// ログイン中のユーザーID取得
$user_id = $_SESSION['user']['user_id'];

// GET パラメータ取得
// item_id、sender_id、receiver_idを受け取る
$item_id = $_GET['item_id'] ?? 0;
$sender_id = $_GET['sender_id'] ?? 0;
$receiver_id = $_GET['receiver_id'] ?? 0;

$pdo = new PDO($connect, USER, PASS);

// DM取得
// DMテーブルから対象のitem_idのメッセージを取得し、送信者情報をJOIN
// 送信時間順（古い順）で並べる
$sql = $pdo->prepare("
    SELECT DM.*, ui.nickname, ui.profile_image
    FROM DM
    LEFT JOIN user_info AS ui ON ui.user_id = DM.sender_id
    WHERE DM.item_id = ? AND DM.is_delete = 0
    ORDER BY DM.send_time ASC
");
$sql->execute([$item_id]);
$messages = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- メッセージ表示 -->
<div class="dm-area">
    <?php foreach ($messages as $msg): ?>
        <?php
        // 右側 = ログインユーザーの送信
        // 左側 = 相手の送信
        // sender_id が自分なら右側
        $isMyMessage = ($msg['sender_id'] == $user_id);
        ?>

        <!-- ユーザー情報表示 -->
        <div class="message-block <?php echo $isMyMessage ? 'my-message' : 'partner-message'; ?>">

            <div class="user-info is-flex is-align-items-center">
                <figure class="image is-24x24 m-2" id="user_icon">
                    <img class="is-rounded"
                        src="icon_image/<?php echo $msg['profile_image'] ?: 'default.png'; ?>"
                        alt="ユーザー画像">
                </figure>

                <span class="nickname">
                    <?php echo htmlspecialchars($msg['nickname']); ?>
                </span>

                <span class="message-time is-size-7 has-text-grey m-2">
                    <?php echo $msg['send_time']; ?>
                </span>
            </div>

            <!-- メッセージ本文 -->
            <div class="balloon <?php echo $isMyMessage ? 'right' : 'left'; ?>">
                <p><?php echo htmlspecialchars($msg['main_text']); ?></p>
            </div>

        </div>
    <?php endforeach; ?>
</div>

<!-- メッセージ入力欄 -->
<!-- フォームでdm-insert.phpにPOST送信 -->
<form action="dm-insert.php" method="post">
    <div class="message-input-area">
        <div class="field has-addons p-3">

            <!-- メッセージ入力テキストボックス -->
            <div class="control is-expanded">
                <input class="input" type="text" placeholder="メッセージを入力" name="main_text">

                <!-- hiddenで必要情報を送信 -->
                <input type="hidden" name="item_id" value="<?= $item_id ?>">
                <input type="hidden" name="sender_id" value="<?= $sender_id ?>">
                <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">

            </div>

            <div class="control">
                <button class="button">
                    <i class="fas fa-pen-square is-size-4"></i>
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    // ページ読み込み後に最下部へスクロール
    window.onload = function() {
        const dmArea = document.querySelector('.dm-area');
        if (dmArea) {
            dmArea.scrollTop = dmArea.scrollHeight;
        }
    };
</script>


<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>