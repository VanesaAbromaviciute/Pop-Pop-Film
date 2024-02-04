<?php
require_once "../config.php";

$cod = $_GET["Cod"];
$apiUrl = $webServer . "/movie/$cod/director";
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$directors = json_decode($json);
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
    <title>Director of movie</title>
</head>
<body>
    <div class="text">
<h1>Director of movie</h1>
</div>
<div class="table">
<table border="1">
    <tr>
        <td>Cod Director</td>
        <td>Director Name</td>
        <td>Director Lastname</td>
        <td>Director Age</td>
        <td>Edit</td>
        <td>Delete</td>
    </tr>

    <?php
    foreach ($directors as $director) {
    ?>
        <tr>
            <td><a href="<?= $urlPrefix ?>/view_directors.php?Cod=<?= $director->CodDirector ?>"><?= $director->CodDirector?></a></td>
            <td><?= $director->DirectorName ?></td>
            <td><?= $director->DirectorLastname ?></td>
            <td><?= $director->DirectorAge ?></td>
            <td><a href="../directors/put_directors.php?Cod=<?= $director->CodDirector?>">Edit</a></td>
            <td><a href="../directors/delete_directors.php?Cod=<?= $director->CodDirector?>">Delete</a></td>
        <tr> 
        <?php
    }
        ?>
</table>
</div>
<div class="enlace">
<a href="./directors.php">New Director</a>
<br><br>
<a href="../movies/index.php">Página anterior</a>
</div>
<br>
</body>
</html>