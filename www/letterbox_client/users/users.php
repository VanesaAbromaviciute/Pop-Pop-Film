<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiUrl = $webServer . '/user';
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

    $users = json_decode($server_output);

    $_GET["Cod"] = $users->CodUser;

    include("view_users.php");
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
    <title>Insert Users</title>
</head>
<body>
    <div class="text">
    <h1>Insert users</h1>
    </div>
    <div class="form-container">
    <form method="post">
        <form>
            <label for="name">Name:</label>
            <input type="text" id="name" name="UserName" maxlength="10">
            <br>
            <label for="lastname">Lastname:</label>
            <input type="text" id="lastname" name="UserLastname" maxlength="15">
            <br>
            <label for="nickname">Nickname:</label>
            <input type="text" id="nickname" name="UserNickname" maxlength="15">
            <br>
            <label for="age">Age:</label>
            <input type="date" id="age" name="UserAge">
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