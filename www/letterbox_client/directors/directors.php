<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiUrl = $webServer . '/directors';
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

    $directors = json_decode($server_output);

    $_GET["Cod"] = $directors->CodDirector;

    include("view_directors.php");
} else {

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
    <title>Insert Director</title>
</head>
<body>
    <div class="text">
    <h1>Insert Director</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="name">Name:</label>
            <input type="text" id="name"  maxlength="10" name="DirectorName">
            <br>
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname"maxlength="15" name="DirectorLastname">
            <br>
            <label for="age">Age:</label>
            <input type="date" id="age" name="DirectorAge">
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