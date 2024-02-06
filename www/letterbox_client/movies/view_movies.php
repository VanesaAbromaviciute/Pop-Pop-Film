<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>View Movies</title>
</head>

<body>
    <?php
    require_once "../config.php";
    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/movies/' . $Cod;

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    // Obtener el código de respuesta HTTP después de la ejecución de curl
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    // Verificar si la pelicula no existe y manejar el error
    if ($httpCode !== 200 || $json === 'false') {
        echo "<p style='color: red;'>La pelicula no existe.</p>";
        exit();
    }
    $movies = json_decode($json);
    ?>

    <div class="text">
        <h1>Movies</h1>
    </div>
    <div class="form-container">
        <form>
            <label for="Cod">Cod:</label>
            <input type="text" id="Cod" name="Cod" value="<?= $movies->CodMovie ?>" disabled>
            <br>
            <label for="name">Movie name:</label>
            <input type="text" id="name" name="name" value="<?= $movies->MovieName ?>" disabled>
            <br>
            <label for="duration">Duration:</label>
            <input type="text" id="duration" name="duration" value="<?= $movies->MovieDuration ?>" disabled>
            <br>
            <label for="release">Release Date:</label>
            <input type="text" id="release" name="release" value="<?= $movies->ReleaseDate ?>" disabled>
            <br>
        </form>
    </div>
    <div class="enlace">
        <a href="./index.php">Previous Page</a>
    </div>

</body>

</html>