<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require 'db-connect.php';

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode(["success" => false, "error" => "ログインしてください"]);
    exit;
}

$pdo = new PDO($connect, USER, PASS);

$data = json_decode(file_get_contents("php://input"), true);
$followed_id = $data['followed_id'] ?? null;
$user_id = $_SESSION['user']['user_id'];

// フォロー状態を確認
$sql = $pdo->prepare("SELECT * FROM follow WHERE follower_id=? AND followed_id=?");
$sql->execute([$user_id, $followed_id]);
$follow = $sql->fetch(PDO::FETCH_ASSOC);

if ($follow) {
    // すでにフォローしていたら解除
    $sql = $pdo->prepare("DELETE FROM follow WHERE follower_id=? AND followed_id=?");
    $sql->execute([$user_id, $followed_id]);
    echo json_encode(["success" => true, "following" => false]);
} else {
    // フォローする
    $sql = $pdo->prepare("INSERT INTO follow (follower_id, followed_id) VALUES (?, ?)");
    $sql->execute([$user_id, $followed_id]);
    echo json_encode(["success" => true, "following" => true]);
}