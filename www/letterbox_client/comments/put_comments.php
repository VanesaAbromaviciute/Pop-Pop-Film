<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Cod = $_GET['Cod'];
    $apiUrl = $webServer . '/comments/' . $Cod;

    $params = array(
        "CommentDate" => $_POST['date'],
        "comments" => $_POST['comment'],
    );
    $apiUrl .= "?" . http_build_query($params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);
    $httpCode = 200; // Inicializa httpCode

    if ($httpCode !== 200) {
        // El comentario no existe, manejar el error 
        echo "<p style='color: red;'>El comentario no existe.</p>";
        exit();
    }
    $result = json_decode($server_output);
    include("view_comments.php");
} else {
    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/comments/' . $Cod;

    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $comments = json_decode($json);
    curl_close($curl);
    // Verificar si comentario no existe y manejar el error
    if (empty($comments)) {
        echo "<p style='color: red;'>El comentario con el código $Cod no existe.</p>";
        exit();
    }
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
        <title>Update</title>
    </head>

    <body>
        <div class="text">
            <h1>Update Comments</h1>
        </div>
        <div class="form-container">
            <form method="post">
                <form>
                    <label for="Cod">Cod:</label>
                    <input type="text" id="Cod" name="Cod" value="<?= $comments->CodComment ?>" disabled>
                    <br>
                    <label for="date">Comment Date:</label>
                    <input type="date" id="date" name="date" value="<?= $comments->CommentDate ?>">
                    <br>
                    <label for="comments">Comment:</label>
                    <input type="text" id="comment" name="comment" maxlength="50" value="<?= $comments->comments ?>">
                    <br>
                    <input type="submit" value="Save">
                </form>
        </div>
        <div class="enlace">
            <a href="./index.php">Previous Page</a>
        </div>
    </body>
<?php
}
?>

    </html>