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

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>商品出品</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css"> 
  <!-- 上記のCSSコードをここに直接記述するか、style.cssに保存してリンクしてください -->
  <style>
    /* 上記で提供したCSSをここに貼り付けます */
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      background-color: #ffffff; /* 背景色を白 */
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start; /* 上部に寄せる */
      min-height: 100vh;
    }

    .container {
      background-color: #fcefdc; /* 画像の背景色と同じ薄いクリーム色に変更 */
      width: 100%;
      max-width: 500px; /* 必要に応じて調整 */
      padding: 20px 0; /* 上下のパディングは残す */
      box-sizing: border-box;
    }

    .header {
      display: flex;
      align-items: center;
      padding: 0 20px 20px;
    }

    .back-arrow {
      font-size: 24px;
      color: #333;
      margin-right: 20px;
    }

    .image-upload-wrapper {
      flex-grow: 1;
      display: flex;
      justify-content: center;
    }

    .image-upload-box {
      background-color: #fcf4e3; /* 画像アップロード部分の薄いベージュ */
      border: 1px solid #e0d0b0; /* 枠線 */
      border-radius: 12px;
      padding: 20px;
      width: 120px;
      height: 120px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
      position: relative;
    }

    .image-upload-box input[type="file"] {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    .camera-icon {
      font-size: 40px;
      color: #c9b498; /* カメラアイコンの色 */
      margin-bottom: 5px;
    }

    .image-count {
      font-size: 14px;
      color: #8c7e6c; /* 数字の色 */
    }

    /* form-section と price-display-box の背景色を削除し、containerの背景色を引き継ぐ */
    .form-section {
      /* background-color: #fcefdc; */ /* 削除 */
      margin: 0 20px 20px; /* 左右マージンと下マージン */
      padding: 0; /* パディングを削除 */
      border-radius: 0; /* 角丸を削除 */
      box-shadow: none; /* 影を削除 */
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group:last-of-type {
      margin-bottom: 0;
    }

    label {
      display: block;
      font-size: 14px;
      color: #555;
      margin-bottom: 8px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
      width: calc(100% - 20px); /* 左右のパディングを考慮 */
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-sizing: border-box;
      background-color: #ffffff; /* 入力フィールドの背景を白に */
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    select {
      appearance: none; /* デフォルトのスタイルを無効化 */
      -webkit-appearance: none;
      background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000000%22%20d%3D%22M287%2C146.24L146.2%2C287L5.4%2C146.24h281.6z%22%2F%3E%3C%2Fsvg%3E'); /* 下向き矢印アイコン */
      background-repeat: no-repeat;
      background-position: right 10px center;
      background-size: 12px;
      padding-right: 30px; /* 矢印のスペース */
    }

    .price-display-box {
      /* background-color: #fcefdc; */ /* 削除 */
      border-radius: 0; /* 角丸を削除 */
      margin: 0 20px; /* 左右マージン */
      padding: 15px 0; /* 左右のパディングを削除 */
      box-shadow: none; /* 影を削除 */
    }

    /* 販売価格入力欄の追加スタイル */
    .price-input-group {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px; /* 必要に応じて調整 */
    }

    .price-input-group label {
      margin-bottom: 0; /* ラベルの下マージンをリセット */
      flex-shrink: 0; /* ラベルが縮まないように */
    }

    .price-input-group .price-range {
      font-size: 16px;
      color: #333;
      font-weight: bold;
    }

    /* 手数料・利益の表示部分のスタイル */
    .price-details-section {
      background-color: #fcf4e3; /* 画像でいう「販売手数料」部分の背景色 */
      border-radius: 12px;
      padding: 15px 20px;
      margin: 0 20px 20px; /* 左右マージンと下マージン */
    }


    .price-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 0;
    }

    .price-row.total-profit {
      border-top: 1px solid #eee; /* 区切り線 */
      margin-top: 10px;
      padding-top: 15px;
      font-weight: bold;
    }

    .price-label {
      font-size: 15px;
      color: #555;
    }

    .price-value {
      font-size: 16px;
      color: #333;
    }

    .price-value.yen {
      font-size: 18px;
      font-weight: bold;
    }

    .update-button {
      background-color: #f7d54b; /* 更新ボタンの黄色 */
      color: #fff;
      font-size: 18px;
      font-weight: bold;
      padding: 15px 20px;
      border: none;
      border-radius: 12px;
      width: calc(100% - 40px); /* 左右のマージンを考慮 */
      margin: 0 20px 20px; /* 左右マージンと下マージン */
      cursor: pointer;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      transition: background-color 0.2s ease;
    }

    .update-button:hover {
      background-color: #f2cc3a;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <span class="back-arrow">&lt;</span>
      <div class="image-upload-wrapper">
        <div class="image-upload-box">
          <span class="camera-icon">📸</span>
          <span class="image-count">1〜20枚</span>
          <input type="file" id="product_images" name="product_images[]" accept="image/*" multiple required>
        </div>
      </div>
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
            <option value="家電">家電</option>
            <option value="本">本</option>
            <option value="衣類">衣類</option>
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
    const priceInput = document.getElementById("price");
    const feeDisplay = document.getElementById("fee-display");
    const profitDisplay = document.getElementById("profit-display");

    priceInput.addEventListener("input", () => {
      const price = parseInt(priceInput.value);
      if (!isNaN(price) && price >= 300) {
        const fee = Math.floor(price * 0.10);
        const profit = price - fee;
        feeDisplay.textContent = `¥${fee}`;
        profitDisplay.textContent = `¥${profit}`;
      } else {
        feeDisplay.textContent = "-";
        profitDisplay.textContent = "-";
      }
    });
  </script>
</body>
</html>
