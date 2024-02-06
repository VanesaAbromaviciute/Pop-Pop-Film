<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Document</title>
</head>

<body>
    <?php
    require_once "../config.php";

    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/comments/' . $Cod;

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    // Obtener el código de respuesta HTTP después de la ejecución de curl
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    // Verificar si el comentario no existe y manejar el error
    if ($httpCode !== 200 || $json === 'false') {
        echo "<p style='color: red;'>El comentario no existe.</p>";
        exit();
    }
    $comments = json_decode($json)
    ?>
    <div class="text">
        <h1>Comment</h1>
    </div>
    <div class="form-container">
        <label for="Cod">Cod:</label>
        <input type="text" id="Cod" name="Cod" value="<?= $comments->CodComment ?>" disabled>
        <br>
        <label for="date">Comment Date:</label>
        <input type="date" id="date" name="date" value="<?= $comments->CommentDate ?>" disabled>
        <br>
        <label for="comment">Comment:</label>
        <input type="text" id="comment" name="comment" value="<?= $comments->comments ?>" disabled>
        <br>
        </form>
    </div>
    <div class="enlace">
        <a href="./index.php">Previous Page</a>
    </div>

</body>

</html>