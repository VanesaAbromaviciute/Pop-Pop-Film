<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>View Review</title>
</head>

<body>
    <?php
    require_once "../config.php";

    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/reviews/' . $Cod;

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    // Obtener el código de respuesta HTTP después de la ejecución de curl
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    // Verificar si la la review no existe y manejar el error
    if ($httpCode !== 200 || $json === 'false') {
        echo "<p style='color: red;'>La review no existe.</p>";
        exit();
    }
    $reviews = json_decode($json);
    ?>
    <div class="text">
        <h1>Review</h1>
    </div>
    <div class="form-container">
        <form>
            <label for="Cod">Cod:</label>
            <input type="text" id="Cod" name="Cod" value="<?= $reviews->CodReview ?>" disabled>
            <br>
            <label for="date">Review Date:</label>
            <input type="text" id="date" name="date" value="<?= $reviews->ReviewDate ?>" disabled>
            <br>
            <label for="stars">Star Number:</label>
            <input type="text" id="stars" name="stars" value="<?= $reviews->StarNumber ?>" disabled>
            <br>
        </form>
    </div>
    <div class="enlace">
        <a href="./index.php">Previous Page</a>
    </div>

</body>

</html>