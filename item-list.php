<?php
// ========================================
// 商品一覧画面（show_home=1の画像を取得）
// ========================================




ini_set('display_errors', 1);
error_reporting(E_ALL);

?>

<?php require 'db-connect.php';?>
<?php require 'header.php'; ?>
<?php require 'header-menu.php'; ?>

<section class="section has-background-warning-light">
  <div class="container">

    <div class="item-grid">
      <?php 
        $pdo=new PDO($connect, USER, PASS);
        $sql = $pdo->query('select * from item_image where show_home=1');
        $items = $sql->fetchAll();
        if (empty($items)): ?>
        <p>現在表示できる商品がありません。</p>
      <?php else: ?>
        <?php 
          foreach ($items as $item){
            echo '<div class="item">';
            echo '<img src="item-image/', $item['image_path'], '" alt="商品画像">';
            echo '<p>商品ID：', $item['item_id'], ')</p>';
            echo '</div>';
          }
        ?>

      <?php endif; ?>
    </div>

  </div>
</section>

<?php require 'footer-menu.php'; ?>
<?php require 'footer.php'; ?>
