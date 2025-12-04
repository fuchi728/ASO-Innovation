<?php
$css_files = ['main-style.css', 'Product Listing Form.css', 'title.css'];
require 'header.php';
require 'header-menu.php';
require_once 'db-connect.php';

$item_id = $_GET['item_id'] ?? null;
if (!$item_id) {
    echo "<script>alert('商品が指定されていません'); location.href='sell-list.php';</script>";
    exit;
}

// DB接続
$pdo = new PDO($connect, USER, PASS);

// 商品情報取得
$stmt = $pdo->prepare('SELECT * FROM item WHERE item_id=?');
$stmt->execute([$item_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

// カテゴリ取得
$categories = $pdo->query("SELECT * FROM category ORDER BY category_id ASC")->fetchAll(PDO::FETCH_ASSOC);

// 画像取得
$stmt = $pdo->prepare("SELECT * FROM item_image WHERE item_id=?");
$stmt->execute([$item_id]);
$imageList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ページタイトル -->
<nav id="page_title" class="navbar is-justify-content-space-between is-align-items-center" role="navigation" aria-label="main navigation">
    <a href="sell-list.php" id="back_button" class="button is-medium is-outlined">
        <span class="icon is-small">
            <i class="fas fa-angle-left"></i>
        </span>
    </a>

</nav>

<div class="content">
    <form action="update-item.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item_id) ?>">

        <!-- 画像アップロード -->
        <div class="image-upload-wrapper">
            <div class="slider-container">
                <button type="button" id="slide-left" class="slide-btn">←</button>
                <div id="slider-track" class="slider-track">
                    <label class="upload-box" onclick="document.getElementById('product_images').click()">
                        <span class="camera-icon">📸</span>
                        <span class="image-count" id="image-count"><?= count($imageList) ?>/20</span>
                    </label>

                    <?php foreach ($imageList as $img): ?>
                        <img src="item-image/<?= htmlspecialchars($img['image_path']) ?>" class="preview-img">
                    <?php endforeach; ?>
                </div>
                <button type="button" id="slide-right" class="slide-btn">→</button>
            </div>
        </div>

        <input type="file" id="product_images" name="product_images[]" accept="image/*" multiple style="display:none;">

        <!-- 商品名 -->
        <div class="form-group">
            <label for="product_name">商品名</label>
            <input type="text" id="product_name" name="product_name" value="<?= htmlspecialchars($item['item_name']) ?>" placeholder="40文字以内" maxlength="40" required>
        </div>

        <!-- 商品説明 -->
        <div class="form-group">
            <label for="product_description">商品の説明</label>
            <textarea id="product_description" name="product_description" placeholder="1000文字以内" maxlength="1000" rows="4" required><?= htmlspecialchars($item['detail']) ?></textarea>
        </div>

        <!-- カテゴリ -->
        <div class="form-group">
            <label for="product_category">カテゴリ</label>
            <select id="product_category" name="product_category" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $item['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- 販売価格 -->
        <div class="price-display-box">
            <label for="price">販売価格</label>
            <input type="number" name="product_price" id="price" value="<?= htmlspecialchars($item['price']) ?>" min="300" required>
        </div>

        <?php
        if ($item['is_sold'] == 1) {
            echo  '<button type="submit" class="update-button" disabled>SOLD OUTのため更新不可</button>';
        } else {
            echo '<button type="submit" class="update-button">更新する</button>';
        }
        ?>
    </form>

    <form action="item-delete.php">
        <input type="hidden" name="item_id" value="<?= $item_id ?>">
        <input type="hidden" name="from" value="Product Listing Form">
        <button class="button is-danger" type="submit">商品を削除する</button>
    </form>
</div>

<script>
    const fileInput = document.getElementById("product_images");
    const sliderTrack = document.getElementById("slider-track");
    const imageCount = document.getElementById("image-count");

    fileInput.addEventListener("change", () => {
        const files = fileInput.files;

        if (files.length > 20) {
            alert("画像は最大20枚までです。");
            fileInput.value = "";
            imageCount.textContent = "0/20";
            return;
        }

        // 既存のプレビュー削除
        sliderTrack.querySelectorAll(".preview-img").forEach(e => e.remove());

        imageCount.textContent = `${files.length}/20`;

        Array.from(files).forEach(file => {
            if (!file.type.startsWith("image/")) return;
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("preview-img");
                sliderTrack.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    document.getElementById("slide-left").addEventListener("click", () => {
        sliderTrack.scrollBy({
            left: -115,
            behavior: "smooth"
        });
    });
    document.getElementById("slide-right").addEventListener("click", () => {
        sliderTrack.scrollBy({
            left: 115,
            behavior: "smooth"
        });
    });
</script>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>