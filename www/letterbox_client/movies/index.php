<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Movies</title>
</head>

<body>
    <?php
    require_once "../config.php";

    $apiUrl = $webServer . '/movies';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $movies = json_decode($json);
    curl_close($curl);
    ?>
    <div class="text">
    <h1>Movies List</h1>
    </div>
    <div class="table">
    <table border="1">
        <tr>
            <td>Cod</td>
            <td>Name</td>
            <td>Duration</td>
            <td>Release_Date</td>
            <td>Director</td>
            <td>Actions</td>
        </tr>
        <?php
        foreach ($movies as $movie) {
            echo "<tr>";
            echo "<td>";
            echo "<a href='$urlPrefix/movies/view_movies.php?Cod=$movie->CodMovie'>$movie->CodMovie</a>";
            echo "<td>$movie->MovieName</td>";
            echo "<td>$movie->MovieDuration</td>";
            echo "<td>$movie->ReleaseDate</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/directors/director_cod.php?Cod=$movie->CodMovie'>Director</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/movies/put_movies.php?Cod=$movie->CodMovie'>Edit</a>";
            echo " ";
            echo "<a href='$urlPrefix/movies/delete_movies.php?Cod=$movie->CodMovie'>Delete</a>";
            echo "</td>";
            echo "<tr>";
        }
        ?>
    </table>
    </div>
    <br>
    <div class="enlace">
    <a href="./movies.php">New Movie</a>
    <br><br>
    <a href="../index.php">Previous Page</a>
    </div>
    <br>
</body>

</html>