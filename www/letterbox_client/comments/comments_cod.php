<?php
require_once "../config.php";

$cod = $_GET["Cod"];
$apiUrl = $webServer . "/review/$cod/comment";
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$comments = json_decode($json);
curl_close($curl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Comments of review</title>
</head>
<body>
<div class="text">
<h1>Comments of review</h1>
</div>
<div class="table">
<table border="1">
    <tr>
        <td>Cod Comment</td>
        <td>Cod Review</td>
        <td>Date</td>
        <td>Comment</td>
        <td>Edit</td>
        <td>Delete</td>
    </tr>

    <?php
    foreach ($comments as $comment) {
    ?>
        <tr>
            <td><a href="<?= $urlPrefix ?>/view_comments.php?Cod=<?= $comment->CodComment ?>"><?= $comment->CodComment ?></a></td>
            <td><a href="<?= $urlPrefix ?>/view_reviews.php?Cod=<?= $comment->CodReview ?>"><?= $comment->CodReview ?></a></td>
            <td><?= $comment->CommentDate ?></td>
            <td><?= $comment->comments ?></td>
            <td><a href="../comments/put_comments.php?Cod=<?= $comment->CodComment ?>">Edit</a></td>
            <td><a href="../comments/delete_comments.php?Cod=<?= $comment->CodComment ?>">Delete</a></td>
        <tr> 
        <?php
    }
        ?>
</table>
</div>
<div class="enlace">
<a href="./comments.php">New Comment</a>
<br><br>
<a href="../reviews/index.php">Previous Page</a>
<br>
</div>