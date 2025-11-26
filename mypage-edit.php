<?php session_start(); ?>
<?php
$css_files = ['main-style.css', 'title.css', 'mypage.css'];
require 'header.php';
?>
<?php require_once 'db-connect.php'; ?>

<!--ページタイトル-->
<nav id="page_title" class="navbar is-flex is-fixed-top is-justify-content-space-between is-align-items-center" role="navigation" aria-label="main navigation">
    <a href="mypage.php" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small">
            <i class="fas fa-angle-left"></i></a>
    </span>
    <div class="navbar-center">
        <span class="title is-6">マイページ編集</span>
    </div>
</nav>

<!-- ユーザー情報取得 -->
<?php
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->prepare('select * from user_info where user_id=?');
$sql->execute([$_SESSION['user']['user_id']]);
$user = $sql->fetch(PDO::FETCH_ASSOC);
?>

<div class="content">
    <form action="mypage-insert.php" method="post" enctype="multipart/form-data">

        <div class="box card p-0">
            <div class="card-content">
                <div class="media">
                    <div class="media-left">
                        <figure id="user_icon" class="image is-64x64 m-3">
                            <?php
                            if (empty($user['profile_image'])) {
                                echo '<img src="user-icon/default.png" alt="ユーザー画像">';
                            } else {
                                echo '<img src="user-icon/' . htmlspecialchars($user['profile_image']) . '" alt="ユーザー画像">';
                            }
                            ?> </figure>
                    </div>
                    <div class="media-content">
                        <p class="title is-5">プロフィール画像</p>
                        <!--画像アップ -->
                        <div class="field">
                            <div class="file is-boxed">
                                <label class=" file-label">
                                    <span class="file-cta">
                                        <span class="file-label">
                                            画像を選択する
                                        </span>
                                    </span>
                                    <input id="file-input" class="file-input" type="file" name="resume">
                                    <span id="file-name" class="file-name">

                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block">

            <div class="field">
                <label class="label">ニックネーム</label>
                <div class="control">
                    <input name="nickname" v-model="nickname" class="input" type="text" placeholder="ニックネームを入力してください" value="<?= $user['nickname'] ?>">
                </div>
            </div>

            <div class="field">
                <label class="label">自己紹介文</label>
                <textarea name="textarea" v-model="textarea" class="textarea is-normal" placeholder="例）ご覧いただきありがとうございます。"><?= $user['self_introduction'] ?></textarea>
            </div>

            <div class="field">
                <label class="label">お名前（他ユーザーには表示されません）</label>
                <div class="control">
                    <input name="name" v-model="name" class="input" type="text" placeholder="氏名を入力してください" value="<?= $user['name'] ?>">
                </div>
            </div>

            <div class="field">
                <label class="label">住所（他ユーザーには表示されません）</label>
                <div class="control">
                    <input name="address" v-model="address" class="input" type="text" placeholder="住所を入力してください" value="<?= $user['address'] ?>">
                </div>
            </div>
        </div>
        <button id="button" type="submit" class="button is-fullwidth">更新する</button>
    </form>

</div>

<script>
    document.getElementById('file-input').addEventListener('change', function() {
        const fileName = this.files.length > 0 ? this.files[0].name : "";
        document.getElementById('file-name').textContent = fileName;
    });
</script>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>