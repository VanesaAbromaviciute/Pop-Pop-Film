<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Directors</title>
</head>

<body>
    <?php
    require_once "../config.php";

    $apiUrl = $webServer . '/directors';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $directors = json_decode($json);
    curl_close($curl);

    ?>
    <div class="text">
    <h1>Directors List</h1>
    </div>
    <div class="table">
    <table border="1">
        <tr>
            <td>Cod</td>
            <td>Name</td>
            <td>LastName</td>
            <td>Age</td>
            <td>Actions</td>
        </tr>
        <?php
        foreach ($directors as $director) {
            echo "<tr>";
            echo "<td>";
            echo "<a href='$urlPrefix/directors/view_directors.php?Cod=$director->CodDirector'>$director->CodDirector</a>";
            echo "<td>$director->DirectorName</td>";
            echo "<td>$director->DirectorLastname</td>";
            echo "<td>$director->DirectorAge</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/directors/put_directors.php?Cod=$director->CodDirector'>Edit</a>";
            echo " ";
            echo "<a href='$urlPrefix/directors/delete_directors.php?Cod=$director->CodDirector'>Delete</a>";
            echo "</td>";
            echo "<tr>";
        }
        ?>
    </table>
    </div>
    <br>
    <div class="enlace">
    <a href="./directors.php">New Director</a>
    <br><br>
    <a href="../index.php">Previous Page</a>
    </div>
</body>

</html>