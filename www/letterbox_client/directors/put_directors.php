<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Cod = $_GET['Cod'];
    $apiUrl = $webServer . '/directors/' . $Cod;

    $params = array(
        "DirectorName" => $_POST['name'],
        "DirectorLastname" => $_POST['lastname'],
        "DirectorAge" => $_POST['age'],
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
    $_GET["CodDirector"] = $Cod;
    include("view_directors.php");

} else {
    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/directors/' . $Cod;
    
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $directors = json_decode($json);
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
    <title>Update Director</title>
</head>
<body>
    <div class="text">
    <h1>Update Director</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="Cod">Cod:</label>
            <input type="text" id="Cod" name="Cod" value="<?= $directors->CodDirector ?>" disabled>
            <br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name"  maxlength="10" value="<?= $directors->DirectorName ?>" >
            <br>
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="lastname" maxlength="15" value="<?= $directors->DirectorLastname ?>" >
            <br>
            <label for="age">Age:</label>
            <input type="date" id="age" name="age" value="<?= $directors->DirectorAge ?>">
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