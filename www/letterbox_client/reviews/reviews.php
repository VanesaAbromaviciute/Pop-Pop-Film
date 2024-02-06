<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiUrl = $webServer . '/reviews';
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

    $reviews = json_decode($server_output);

    $_GET["Cod"] = $reviews->CodReview;

    include("view_reviews.php");
} else {
    $apiUrl = $webServer . '/users';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $users = json_decode($json);
    $apiUrl = $webServer . '/movies';
    curl_close($curl);
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $movies = json_decode($json);
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
    <title>Insert review</title>
</head>
<body>
    
</body>
</html>
    <div class="text">
    <h1>Insert review</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="date">Review Date:</label>
            <input type="date" id="date" name="ReviewDate">
            <br>
            <label for="star">StarNumber:</label>
            <input type="text" id="star" name="StarNumber" maxlength="5">
            <br>
            <label for="CodUser">Usuario:</label>
          <select name="CodUser" id="CodUser">
            <?php
            foreach($users as $user) {?>
            <option value="<?= $user->CodUser?>"><?= $user->UserName?></option>
            <?php
            }
            ?>
          </select>
            <br>
            <label for="CodMovie">Película:</label>
            <select name="CodMovie" id="CodMovie">
            <?php
            foreach($movies as $movie) {?>
            <option value="<?= $movie->CodMovie?>"><?= $movie->MovieName?></option>
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
        <a href="./index.php">Página anterior</a>
        </div>

        </body>
    <?php
}
    ?>

    </html>