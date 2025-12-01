<?php
session_start();
require 'db-connect.php';

$css_files = ['main-style.css', 'admin-header.css', 'admin-news-style.css'];
require 'admin-header.php';

$pdo = new PDO($connect, USER, PASS);

// news投稿（DB保存）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'] ?? '';
    $detail = $_POST['detail'] ?? '';

    if (!empty($title) && !empty($detail)) {

        $sql = $pdo->prepare("
            INSERT INTO news (title, detail, send_time)
            VALUES (?, ?, NOW())
        ");
        $sql->execute([$title, $detail]);

        echo "<script>alert('投稿成功');</script>"; // アラート
        echo "<script>location.href='admin-news.php';</script>";
    }
}
?>

<section class="section py-4">
    <div class="container">
        <h1 class="title is-4">NEWS投稿</h1>

        <!-- タイトル -->
        <div class="field">
            <label class="label">タイトル</label>
            <div class="control">
                <input
                    class="input"
                    type="text"
                    name="title"
                    form="news-form"
                    placeholder="タイトルを入力"
                    required>
            </div>
        </div>

        <!-- 本文 -->
        <div class="field">
            <label class="label">本文</label>
            <div class="control">
                <textarea
                    class="textarea"
                    name="detail"
                    rows="12"
                    form="news-form"
                    placeholder="本文を入力"
                    required></textarea>
            </div>
        </div>


        <div id="news-box">

            <form method="post" action="admin-news.php" id="news-form">


                <!-- ボタン -->
                <div class="fixed-buttons">
                    <button class="button is-warning" type="submit">
                        投稿
                    </button>
                    <a href="admin-news-list.php" class="button is-warning">
                        NEWS一覧
                    </a>
                </div>

            </form>
        </div>

    </div>
</section>

<?php require 'footer.php'; ?>