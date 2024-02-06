<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiUrl = $webServer . '/movies';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt(
        $ch,
        CURLOPT_POSTFIELDS,
        http_build_query($_POST)
    );

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);

    $movies = json_decode($server_output);

    $_GET["Cod"] = $movies->CodMovie;

    include("view_movies.php");
} else {
    $apiUrl = $webServer . '/directors';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $directors = json_decode($json);

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
    <title>Insert Movie</title>
</head>
<body>
    <div class="text">
    <h1>Insert Movie</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="name">Movie Name:</label>
            <input type="text" id="name" maxlength="20" name="MovieName">
            <br>
            <label for="duration">Movie Duration:</label>
            <input type="text" id="duration" maxlength="3" name="MovieDuration">
            <br>
            <label for="date">Release Year:</label>
            <input type="text" id="date" maxlength="4" name="ReleaseDate">
            <br>
            <label for="CodDirector">Director:</label>
          <select name="CodDirector" id="CodDirector">
            <?php
            foreach($directors as $director) {?>
            <option value="<?= $director->CodDirector?>"><?= $director->DirectorName?></option>
            <?php
            }
            ?>
            </select>
            <br>
            <input type="submit" value="Save">
        </form>
        </div>
        <br>
        <div class="enlace">
        <a href="./index.php">Previous Page</a>
        </div>

        </body>
    <?php
}
    ?>

    </html>