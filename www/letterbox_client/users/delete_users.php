<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');

    body {
      background-image: url(./img/Popcorn\ Minimal.jpg);
      font-size: 30px;
      font-family: 'DM Serif Display', serif;
      background-size: cover;
    }
  </style>
</head>

<body>
  <?php
  error_reporting(0);
  require_once "../config.php";
  $cod = $_GET["Cod"];
  $apiUrl = $webServer . '/users/' . $cod;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $apiUrl);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

  // Receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $server_output = curl_exec($ch);

  curl_close($ch);

  $result = json_decode($server_output);

  if ($result->deleted == "true") {
    echo "User $cod has been deleted";
  } else {
    echo "ERROR: Can't delete user $cod";
  }
  ?>
</body>

</html>