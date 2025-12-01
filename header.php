<?php
if (!isset($css_files) || !is_array($css_files)) {
    $css_files = [];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanika - ASO Innovation</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php foreach ($css_files as $css): ?>
        <link rel="stylesheet" href="css/<?= htmlspecialchars($css, ENT_QUOTES) ?>">
    <?php endforeach; ?>
</head>
<body>