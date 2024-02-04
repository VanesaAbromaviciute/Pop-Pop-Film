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
<?php
require_once "../config.php";

$cod = $_GET["Cod"];
$apiUrl = $webServer . "/review/$cod/movies";
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$movies = json_decode($json);
curl_close($curl);
?>
<div class="text">
<h1>Comments of review</h1>
</div>
<div class="table">
<table border="1">
    <tr>
        <td>Cod Movie</td>
        <td>Cod Review</td>
        <td>Movie Name</td>
        <td>Movie Duration</td>
        <td>Realease Date</td>
        <td>Edit</td>
        <td>Delete</td>
    </tr>

    <?php
    foreach ($movies as $movie) {
    ?>
        <tr>
            <td><a href="<?= $urlPrefix ?>/view_movies.php?Cod=<?= $movie->CodMovie ?>"><?= $movie->CodMovie ?></a></td>
            <td><a href="<?= $urlPrefix ?>/view_reviews.php?Cod=<?= $movie->CodReview ?>"><?= $movie->CodReview ?></a></td>
            <td><?= $movie->MovieName ?></td>
            <td><?= $movie->MovieDuration ?></td>
            <td><?= $movie->ReleaseDate ?></td>
            <td><a href="../movies/put_movies.php?Cod=<?= $movie->CodMovie ?>">Edit</a></td>
            <td><a href="../movies/delete_movies.php?Cod=<?= $movie->CodMovie?>">Delete</a></td>
        <tr> 
        <?php
    }
        ?>
</table>
</div>
<div class="enlace">
<a href="./movies.php">New Movie</a>
<br><br>
<a href="../reviews/index.php">Página anterior</a>
</div>
<br>
</body>
</html>