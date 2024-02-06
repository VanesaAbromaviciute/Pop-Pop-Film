<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Cod = $_GET['Cod'];
    $apiUrl = $webServer . '/movies/' . $Cod;

    $params = array(
        "MovieName" => $_POST['name'],
        "MovieDuration" => $_POST['duration'],
        "ReleaseDate" => $_POST['date'],
    );
    $apiUrl .= "?" . http_build_query($params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($server_output);
    include("view_movies.php");
} else {
    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/movies/' . $Cod;

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
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
        <title>Update user</title>
    </head>

    <body>
        <div class="text">
            <h1>Update User</h1>
        </div>
        <div class="form-container">
            <form method="post">
                <form>
                    <label for="Cod">Cod:</label>
                    <input type="text" id="Cod" name="Cod" value="<?= $movies->CodMovie ?>" disabled>
                    <br>
                    <label for="name">Movie Name:</label>
                    <input type="text" id="name" name="name" maxlength="20" value="<?= $movies->MovieName ?>">
                    <br>
                    <label for="duartion">Movie Duration:</label>
                    <input type="text" id="duration" name="duration" maxlength="3" value="<?= $movies->MovieDuration ?>">
                    <br>
                    <label for="date">Release Year:</label>
                    <input type="text" id="date" name="date" maxlength="4" value="<?= $movies->ReleaseDate ?>">
                    <br>
                    <input type="submit" value="Save">
                </form>
        </div>
        <div class="enlace">
            <a href="./index.php">Página anterior</a>
        </div>
    </body>
<?php
}
?>

    </html>