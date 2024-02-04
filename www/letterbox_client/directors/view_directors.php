<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>View Director</title>
</head>
<body>
<?php
require_once "../config.php";
$Cod = $_GET["Cod"];
$apiUrl = $webServer . '/directors/' . $Cod;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$directors = json_decode($json);
curl_close($curl);
?>
<div class="text">
<h1>Director</h1>
</div>
<div class="form-container">
<label for="Cod">Cod:</label>
<input type="text" id="Cod" name="Cod" value="<?=$directors->CodDirector?>" disabled>
<br>
<label for="name">Name:</label>
<input type="text" id="name" name="name" value="<?=$directors->DirectorName?>" disabled>
<br>
<label for="lastname">Lastname:</label>
<input type="text" id="lastname" name="lastname" value="<?=$directors->DirectorLastname?>" disabled>
<br>
<label for="age">Age:</label>
<input type="date" id="age" name="age" value="<?=$directors->DirectorAge?>" disabled>
<br>
</form>
</div>
<div class="enlace">
<a href="./index.php">Página anterior</a>
</div>

</body>
</html>