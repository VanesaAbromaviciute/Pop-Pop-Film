<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Cod = $_GET['Cod'];
    $apiUrl = $webServer . '/reviews/' . $Cod;

    $params = array(
        "ReviewDate" => $_POST['date'],
        "StarNumber" => $_POST['stars'],
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
    include("view_reviews.php");

} else {
    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/reviews/' . $Cod;
    
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $reviews = json_decode($json);
    curl_close($curl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update review</title>
</head>
<body>
<link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
</body>
</html>
    <div class="text">
    <h1>Update review</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="Cod">Cod:</label>
            <input type="text" id="Cod" name="Cod" value="<?= $reviews->CodReview ?>" disabled>
            <br>
            <label for="date">Review Date:</label>
            <input type="date" id="date" name="date" value="<?= $reviews->ReviewDate ?>" >
            <br>
            <label for="stars">Star Number:</label>
            <input type="text" id="stars" name="stars" maxlength="5" value="<?= $reviews->StarNumber ?>" >
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