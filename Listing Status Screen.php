<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>出品状況</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /*
     * CSS スタイルシート
     * 提供された画像の外観を再現するためのスタイル
     */

    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      background-color: #fcf4e3; /* 画像と同じ、全体の薄いクリーム色の背景 */
      margin: 0;
      padding: 0;
      display: flex; /* Flexbox を使用して要素を配置 */
      flex-direction: column; /* 要素を縦方向に並べる */
      justify-content: flex-start; /* コンテンツを上部に寄せる */
      align-items: center; /* コンテンツを水平方向に中央寄せ */
      min-height: 100vh; /* ビューポートの高さに合わせて最小高さを設定 */
    }

    .container {
      width: 100%; /* 親要素の幅に合わせて全幅を使用 */
      max-width: 500px; /* PCなど広い画面での最大幅を制限 */
      /* background-color は body と同じ色なので、透過させて body の背景色を表示 */
      padding: 20px 20px 0; /* 上下左右のパディングを調整 (下部はボタンとの間隔で調整) */
      box-sizing: border-box; /* パディングを要素の幅と高さに含める */
      display: flex; /* Flexbox を使用して子要素を配置 */
      flex-direction: column; /* 子要素を縦方向に並べる */
      gap: 20px; /* 子要素 (status-card) 間の隙間 */
    }

    .status-card {
      background-color: #fcf4e3; /* カードの背景色 */
      border: 1px solid #e0d0b0; /* カードの枠線 */
      border-radius: 12px; /* カードの角を丸くする */
      display: flex; /* Flexbox を使用してアイコンと情報を配置 */
      align-items: center; /* アイコンと情報を垂直方向に中央寄せ */
      padding: 20px; /* カード内部のパディング */
      box-shadow: 0 1px 3px rgba(0,0,0,0.05); /* 軽い影を追加 */
      cursor: pointer; /* マウスカーソルをポインターに変更してクリック可能であることを示す */
      transition: background-color 0.2s ease; /* ホバー時の背景色変化を滑らかにする */
      width: 100%; /* コンテナの幅に合わせる */
      box-sizing: border-box; /* パディングを幅に含める */
    }

    .status-card:hover {
      background-color: #fcf0da; /* ホバー時の背景色 */
    }

    .status-icon {
      font-size: 40px; /* アイコンのサイズ */
      color: #6a6a6a; /* アイコンの色 */
      margin-right: 20px; /* アイコンとテキストの間の右マージン */
      line-height: 1; /* アイコンの行の高さを調整して中央揃えを改善 */
      flex-shrink: 0; /* 画面幅が狭くなってもアイコンが縮まないようにする */
    }
    
    /* 四角いグリッドアイコン (出品中アイコン) のスタイル */
    .grid-icon {
      display: grid; /* Grid レイアウトを使用して四角を配置 */
      grid-template-columns: repeat(2, 1fr); /* 2列のグリッド */
      gap: 5px; /* 四角間の隙間 */
      width: 40px; /* アイコン全体の幅 */
      height: 40px; /* アイコン全体の高さ */
    }

    .grid-square {
      width: 17px; /* 各四角のサイズ */
      height: 17px;
      border: 2px solid #6a6a6a; /* 四角の枠線 */
      border-radius: 3px; /* 少し角丸に */
    }

    .status-info {
      display: flex; /* Flexbox を使用してタイトルと件数を配置 */
      flex-direction: column; /* タイトルと件数を縦方向に並べる */
    }

    .status-title {
      font-size: 18px; /* タイトルのフォントサイズ */
      font-weight: bold; /* タイトルを太字に */
      color: #333; /* タイトルの色 */
      margin-bottom: 5px; /* タイトルと件数の間の下マージン */
    }

    .status-count {
      font-size: 16px; /* 件数のフォントサイズ */
      color: #555; /* 件数の色 */
    }

    .listing-button {
      background-color: #f7d54b; /* 出品ボタンの黄色 */
      color: #fff; /* ボタンの文字色を白に */
      font-size: 18px; /* ボタンのフォントサイズ */
      font-weight: bold; /* ボタンの文字を太字に */
      padding: 15px 20px; /* ボタンのパディング */
      border: none; /* ボタンの枠線を削除 */
      border-radius: 12px; /* ボタンの角を丸くする */
      /* width は body の中央寄せと container の max-width に合わせて調整 */
      width: calc(100% - 40px); /* 左右に20pxずつマージンを確保 */
      max-width: 460px; /* コンテナの最大幅 (500px) の内側 (500-20-20) */
      margin: 20px 0; /* 上下にマージン、左右は body の align-items: center; で中央寄せ */
      display: flex; /* Flexbox を使用してアイコンとテキストを配置 */
      justify-content: center; /* アイコンとテキストを水平方向に中央寄せ */
      align-items: center; /* アイコンとテキストを垂直方向に中央寄せ */
      cursor: pointer; /* マウスカーソルをポインターに変更 */
      box-shadow: 0 3px 6px rgba(0,0,0,0.1); /* 影を追加 */
      transition: background-color 0.2s ease; /* ホバー時の背景色変化を滑らかにする */
      box-sizing: border-box; /* パディングを幅に含める */
    }

    .listing-button:hover {
      background-color: #f2cc3a; /* ホバー時の背景色 */
    }

    .listing-button .camera-icon {
      font-size: 24px; /* ボタン内のカメラアイコンのサイズ */
      margin-right: 10px; /* アイコンとテキストの間の右マージン */
      color: #fff; /* アイコンの色を白に */
    }
  </style>
</head>
<body>
  <!-- メインコンテンツを囲むコンテナ -->
  <div class="container">
    <!-- 出品中ステータスカード -->
    <div class="status-card">
      <div class="status-icon grid-icon">
        <div class="grid-square"></div>
        <div class="grid-square"></div>
        <div class="grid-square"></div>
        <div class="grid-square"></div>
      </div>
      <div class="status-info">
        <span class="status-title">出品中</span>
        <span class="status-count">2件</span>
      </div>
    </div>

    <!-- 取引中ステータスカード -->
    <div class="status-card">
      <span class="status-icon">📦</span> <!-- 絵文字をアイコンとして使用 -->
      <div class="status-info">
        <span class="status-title">取引中</span>
        <span class="status-count">1件</span>
      </div>
    </div>
  </div>

  <!-- 出品するボタン -->
  <button class="listing-button">
    <span class="camera-icon">📸</span> <!-- 絵文字をアイコンとして使用 -->
    出品する
  </button>
</body>
</html>