<?php session_start(); ?>
<?php require_once 'db-connect.php'; ?>

<?php
$limit = $_GET['limit'] ?? 4;
$user_id = $_SESSION['user']['user_id'];

$pdo = new PDO($connect, USER, PASS);

$count = $pdo->prepare("
    SELECT COUNT(*) 
    FROM view_history 
    WHERE user_id = ?
");
$count->execute([$user_id]);
$total = $count->fetchColumn();

$sql = $pdo->prepare("
    SELECT i.item_id, i.item_name, i.price, img.image_path
    FROM view_history vh
    JOIN item i ON vh.item_id = i.item_id
    LEFT JOIN item_image img ON i.item_id = img.item_id AND img.show_home = 1
    WHERE vh.user_id = ?
    ORDER BY vh.view_time DESC
    LIMIT $limit
");
$sql->execute([$user_id]);
$data = $sql->fetchAll(PDO::FETCH_ASSOC);
echo json_encode([
    "total" => $total,
    "items" => $data
]);
?>