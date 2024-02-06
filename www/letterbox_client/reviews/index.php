<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Reviews</title>
</head>
<body>
    <?php
    require_once "../config.php";

    $apiUrl = $webServer . '/reviews';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $reviews = json_decode($json);
    curl_close($curl);
    ?>
    <div class="text">
    <h1>Reviews List</h1>
    </div>
    <div class="table">
    <table border="1">
        <tr>
            <td>Cod</td>
            <td>Review_Date</td>
            <td>StarNumber</td>
            <td>Users</td>
            <td>Movies</td>
            <td>Comments</td>
            <td>Actions</td>
        </tr>

        <?php
        foreach ($reviews as $review) {
            echo "<tr>";
            echo "<td>";
            echo "<a href='$urlPrefix/reviews/view_reviews.php?Cod=$review->CodReview'>$review->CodReview</a>";
            echo "<td>$review->ReviewDate</td>";
            echo "<td>$review->StarNumber</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/users/users_cod.php?Cod=$review->CodReview'>Users</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/movies/movies_cod.php?Cod=$review->CodReview'>Movie</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/comments/comments_cod.php?Cod=$review->CodReview'>Comments</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/reviews/put_reviews.php?Cod=$review->CodReview'>Edit</a>";
            echo " ";
            echo "<a href='$urlPrefix/reviews/delete_reviews.php?Cod=$review->CodReview'>Delete</a>";
            echo "</td>";
            echo "<tr>";
        }
        ?>
    </table>
    </div>
    <br>
    <div class="enlace">
    <a href="./reviews.php">New Review</a>
    <br><br>
    <a href="../index.php">Previous Page</a>
    </div>
</body>

</html>