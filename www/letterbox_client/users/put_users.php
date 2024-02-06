<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Cod = $_GET['Cod'];
    $apiUrl = $webServer . '/users/' . $Cod;

    $params = array(
        "UserName" => $_POST['name'],
        "UserLastname" => $_POST['lastname'],
        "UserNickname" => $_POST['nickname'],
        "UserAge" => $_POST['age'],
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
    $_GET["CodUser"] = $Cod;
    $result = json_decode($server_output);
    $httpCode = 200; // Inicializa httpCode

    if ($httpCode !== 200) {
        // El usuario no existe, manejar el error 
        echo "<p style='color: red;'>El user no existe.</p>";
        exit();
    }
    include("view_users.php");

} else {
    $Cod = $_GET["Cod"];
    $apiUrl = $webServer . '/users/' . $Cod;
    
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $users = json_decode($json);
    curl_close($curl);
    // Verificar si pelicula no existe y manejar el error
    if (empty($users)) {
        echo "<p style='color: red;'>El user con el código $Cod no existe.</p>";
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
    <title>Update User</title>
</head>
<body>
    <div class="text">
    <h1>Update User</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="Cod">Cod:</label>
            <input type="text" id="Cod" name="Cod" value="<?= $users->CodUser ?>" disabled>
            <br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= $users->UserName ?>" maxlength="10">
            <br>
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="lastname" value="<?= $users->UserLastname ?>" maxlength="15" >
            <br>
            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="nickname" value="<?= $users->UserNickname ?>" maxlength="15">
            <br>
            <label for="age">Age:</label>
            <input type="date" id="age" name="age" value="<?= $users->UserAge ?>">
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