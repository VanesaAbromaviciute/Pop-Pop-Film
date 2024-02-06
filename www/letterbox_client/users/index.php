<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Users</title>
</head>
<body>
<?php
    require_once "../config.php";

    $apiUrl = $webServer . '/users';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $users = json_decode($json);
    curl_close($curl);
    ?>
    <div class="text">
<h1>Users List</h1>
</div>
<div class="table">
    <table border="1">
        <tr>
            <td>Cod</td>
            <td>Name</td>
            <td>Lastname</td>
            <td>Nickname</td>
            <td>Age</td>
            <td>Actions</td>
        </tr>

        <?php
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>";
            echo "<a href='$urlPrefix/users/view_users.php?Cod=$user->CodUser'>$user->CodUser</a>";
            echo "<td>$user->UserName</td>";
            echo "<td>$user->UserLastname</td>";
            echo "<td>$user->UserNickname</td>";
            echo "<td>$user->UserAge</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/users/put_users.php?Cod=$user->CodUser'>Edit</a>";
            echo " ";
            echo "<a href='$urlPrefix/users/delete_users.php?Cod=$user->CodUser'>Delete</a>";
            echo "</td>";
            echo "<tr>";
        }
        ?>
    </table>
    </div>
    <br>
    <div class="enlace">
    <a href="./users.php">New User</a>
    <br><br>
    <a href="../index.php">Previous Page</a>
    </div>
</body>
</html>