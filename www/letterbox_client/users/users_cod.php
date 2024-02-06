<?php
require_once "../config.php";

$cod = $_GET["Cod"];
$apiUrl = $webServer . "/review/$cod/users";
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$users = json_decode($json);
curl_close($curl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
</head>
<body>
<div class="text">
<h1>Users of review</h1>
</div>
<div class="table">
<table border="1">
    <tr>
        <td>Cod User</td>
        <td>Cod Review</td>
        <td>User Name</td>
        <td>User Lastname</td>
        <td>User Age</td>
        <td>Nickname</td>
        <td>Edit</td>
        <td>Delete</td>
    </tr>

    <?php
    foreach ($users as $user) {
    ?>
        <tr>
            <td><a href="<?= $urlPrefix ?>/view_users.php?Cod=<?= $user->CodUser ?>"><?= $user->CodUser ?></a></td>
            <td><a href="<?= $urlPrefix ?>/view_reviews.php?Cod=<?= $user->CodReview ?>"><?= $user->CodReview ?></a></td>
            <td><?= $user->UserName ?></td>
            <td><?= $user->UserLastname ?></td>
            <td><?= $user->UserAge ?></td>
            <td><?= $user->UserNickname ?></td>
            <td><a href="/put_users.php?Cod=<?= $user->CodUser?>">Edit</a></td>
            <td><a href="/delete_users.php?Cod=<?= $user->CodUser?>">Delete</a></td>
        <tr> 
        <?php
    }
        ?>
</table>
</div>
<div class="enlace">
<a href="./users.php">New User</a>
<br><br>
<a href="../reviews/index.php">Página anterior</a>
<br>
</div>
 
</body>
</html>