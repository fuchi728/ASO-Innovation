<?php
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
    <div class="header">
      <a href="home.php" class="button is-medium is-outlined">
    <span class="icon is-small">
        <i class="fas fa-angle-left"></i>
    </span>
</a>
      <div class="image-upload-wrapper">
  <!-- スライドプレビュー -->
  <div class="slider-container">
    <div id="preview-area" class="slider-track"></div>
  </div>

  <!-- アップロードボックス -->
  <label class="image-upload-box" id="upload-box">
    <span class="camera-icon" id="camera-icon">📸</span>
    <span class="image-count" id="image-count">1〜20枚</span>
    <input type="file" id="product_images" name="product_images[]" accept="image/*" multiple required>
  </label>
</div>

<!-- スライダーの左右ボタン -->
<div class="slider-controls">
  <button type="button" id="slide-left" class="slide-btn">←</button>
  <div id="preview-area" class="slider-track"></div>
  <button type="button" id="slide-right" class="slide-btn">→</button>
</div>





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
        <input type="number" name="product_price" id="price" min="300" max="99999" style="margin-bottom: 20px;" required>
      </div>

      <!-- 手数料・利益（リアルタイム表示） -->
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

      <!-- 更新ボタン -->
      <button type="submit" class="update-button">更新する</button>
    </form>
  </div>

  <script>
const imageInput = document.getElementById("product_images");
const previewArea = document.getElementById("preview-area");
const uploadBox = document.getElementById("upload-box");
const cameraIcon = document.getElementById("camera-icon");
const imageCount = document.getElementById("image-count");

imageInput.addEventListener("change", () => {
  previewArea.innerHTML = ""; // プレビュー初期化
  const files = imageInput.files;

  if (files.length > 0) {
    // 最初の画像でアップロードボックスを置き換え
    const firstReader = new FileReader();
    firstReader.onload = (e) => {
      uploadBox.innerHTML = `
        <img src="${e.target.result}" class="main-preview" id="main-preview">
        <input type="file" id="product_images" name="product_images[]" accept="image/*" multiple required style="display:none;">
      `;
      // 再度選択できるようにクリックイベントを追加
      const mainPreview = document.getElementById("main-preview");
      const newInput = uploadBox.querySelector('input[type="file"]');
      mainPreview.addEventListener("click", () => newInput.click());
      newInput.addEventListener("change", () => {
        imageInput.files = newInput.files;
        imageInput.dispatchEvent(new Event("change"));
      });
    };
    firstReader.readAsDataURL(files[0]);
  }

  // スライダーに全画像表示（2枚目以降）
  Array.from(files).forEach((file, index) => {
    if (!file.type.startsWith("image/")) return;
    if (index === 0) return; // 最初の画像はアップロードボックスに表示済み

    const reader = new FileReader();
    reader.onload = (e) => {
      const img = document.createElement("img");
      img.src = e.target.result;
      img.classList.add("preview-image");
      previewArea.appendChild(img);
    };
    reader.readAsDataURL(file);
  });

  // カウント表示更新
  imageCount.textContent = `${files.length}枚選択中（最大20枚）`;
});

// スライドボタンの動作
document.getElementById("slide-left").addEventListener("click", () => {
  previewArea.scrollBy({ left: -100, behavior: "smooth" });
});

document.getElementById("slide-right").addEventListener("click", () => {
  previewArea.scrollBy({ left: 100, behavior: "smooth" });
});

imageInput.addEventListener("change", () => {
  const files = imageInput.files;

  if (files.length > 20) {
    alert("画像は最大20枚まで選択できます。");
    imageInput.value = ""; // リセット
    return;
  }

  // 以下、既存のプレビュー処理を続けてOK
});


</script>



  </div>
  <?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
