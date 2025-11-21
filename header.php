<!DOCTYPE html>
<html>
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vanika - ASO Innovation</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <!-- <link rel="stylesheet" href="css/main_style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/title.css"> -->
 
    <?php
    foreach ($css_files as $css) {
        echo '<link rel="stylesheet" href="css/' . $css . '">' . "\n";
    }
    ?>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="main-style.css">
    <link rel="stylesheet" href="title.css">
    <link rel="stylesheet" href="item-list.css">
</head>
 
<body>