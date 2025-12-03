<?php session_start(); ?>
<?php
// ログイン確認
if (!isset($_SESSION['user']['user_id'])) {
    header("Location: login.php");
    exit;
}
$css_files = ['main-style.css','selledit_style.css'];
require 'header.php';
?>
<?php require 'header-menu.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $imagePaths = [];
  if (isset($_FILES["product_images"])) {
    $images = $_FILES["product_images"];
    for ($i = 0; $i < count($images["name"]); $i++) {
      if ($images["error"][$i] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
        }
        $fileName = basename($images["name"][$i]);
        $filePath = $uploadDir . $fileName;
        move_uploaded_file($images["tmp_name"][$i], $filePath);
        $imagePaths[] = $filePath;
      }
    }
  }

  $name = htmlspecialchars($_POST["product_name"]);
  $description = htmlspecialchars($_POST["product_description"]);
  $category = htmlspecialchars($_POST["product_category"]);
  $price = intval($_POST["product_price"]);
  $fee = floor($price * 0.10);
  $profit = $price - $fee;

  echo "<div style='padding:1em; font-family:sans-serif'>";
  echo "<h2>📝 出品内容</h2>";
  echo "<p><strong>商品名:</strong> {$name}</p>";
  echo "<p><strong>説明:</strong> {$description}</p>";
  echo "<p><strong>カテゴリ:</strong> {$category}</p>";
  echo "<p><strong>販売価格:</strong> ¥{$price}</p>";
  echo "<p><strong>販売手数料 (10%):</strong> ¥{$fee}</p>";
  echo "<p><strong>販売利益:</strong> ¥{$profit}</p>";
  if ($imagePaths) {
    echo "<p><strong>画像:</strong><br>";
    foreach ($imagePaths as $path) {
      echo "<img src='{$path}' style='width:100px; height:100px; object-fit:cover; margin:5px; border-radius:8px'>";
    }
    echo "</p>";
  }
  echo "<hr></div>";
}
?>

<div class="miya">
  <div class="container">

    <!-- ▼▼▼ ここから：アップデート版と完全一致の画像アップロード部分 ▼▼▼ -->
    <div class="image-upload-wrapper">
        <div class="slider-container">

            <!-- 左ボタン -->
            <button type="button" id="slide-left" class="slide-btn">←</button>

            <!-- スライダー本体 -->
            <div id="slider-track" class="slider-track">

                <!-- アップロードボックス -->
                <label class="upload-box" onclick="document.getElementById('product_images').click()">
                    <span class="camera-icon">📸</span>
                    <span class="image-count" id="image-count">0/20</span>
                </label>

                <!-- ここに JS でプレビュー画像が追加される -->
            </div>

            <!-- 右ボタン -->
            <button type="button" id="slide-right" class="slide-btn">→</button>
        </div>
    </div>

    <!-- ファイル入力 -->
    <input type="file" id="product_images" name="product_images[]" accept="image/*" multiple style="display:none;">
    <!-- ▲▲▲ 完全移植ここまで ▲▲▲ -->


    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-section">

        <!-- 商品名 -->
        <div class="form-group">
          <label for="product_name">商品名</label>
          <input type="text" id="product_name" name="product_name" placeholder="40文字以内" maxlength="40" required>
        </div>

        <!-- 商品説明 -->
        <div class="form-group">
          <label for="product_description">商品の説明</label>
          <textarea id="product_description" name="product_description" placeholder="1000文字以内" maxlength="1000" rows="4" required></textarea>
        </div>

        <!-- カテゴリ -->
        <div class="form-group">
          <label for="product_category">カテゴリ</label>
          <select id="product_category" name="product_category" required>
            <option value="">選択</option>
            <option value="ファッション">ファッション</option>
            <option value="家電・スマホ・カメラ">家電・スマホ・カメラ</option>
            <option value="本・音楽・ゲーム">本・音楽・ゲーム</option>
            <option value="ホビー・エンタメ">ホビー・エンタメ</option>
            <option value="コスメ・美容">コスメ・美容</option>
            <option value="スポーツ・アウトドア">スポーツ・アウトドア</option>
            <option value="インテリア・住まい">インテリア・住まい</option>
            <option value="その他">その他</option>
          </select>
        </div>
      </div>

      <!-- 販売価格 -->
      <div class="price-display-box">
        <div class="price-input-group">
          <label for="price">販売価格</label>
          <span class="price-range">¥ 300〜</span>
        </div>
        <input type="number" name="product_price" id="price" min="300" max="99999" required>
      </div>

      <!-- 手数料・利益 -->
      <div class="price-details-section">
        <div class="price-row">
          <span class="price-label">販売手数料（10%）</span>
          <span class="price-value" id="fee-display">-</span>
        </div>
        <div class="price-row total-profit">
          <span class="price-label">販売利益</span>
          <span class="price-value yen" id="profit-display">-</span>
        </div>
      </div>

      <button type="submit" class="update-button">出品する</button>
    </form>
  </div>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>

<!-- ▼▼▼ update-item と同じ JS 完全コピー ▼▼▼ -->
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
        sliderTrack.scrollBy({ left: -115, behavior: "smooth" });
    });
    document.getElementById("slide-right").addEventListener("click", () => {
        sliderTrack.scrollBy({ left: 115, behavior: "smooth" });
    });
</script>
