<?php
require_once "../config.php";

$cod = $_GET["Cod"];
$apiUrl = $webServer . "/comment/$cod/review";
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
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
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>review of comment</title>
</head>

<body>

    <div class="text">
        <h1>Reviews of comment</h1>
    </div>
    <div class="table">
        <table border="1">
            <tr>
                <td>Cod Review</td>
                <td>Review Date</td>
                <td>Star Number</td>
                <td>Edit</td>
                <td>Delete</td>
            </tr>

            <?php
            foreach ($reviews as $review) {
            ?>
                <tr>
                    <td><a href="<?= $urlPrefix ?>/view_reviews.php?Cod=<?= $review->CodReview ?>"><?= $review->CodReview ?></a></td>
                    <td><?= $review->ReviewDate ?></td>
                    <td><?= $review->StarNumber ?></td>
                    <td><a href="../reviews/put_reviews.php?Cod=<?= $review->CodReview ?>">Edit</a></td>
                    <td><a href="../reviews/delete_reviews.php?Cod=<?= $review->CodReview ?>">Delete</a></td>
                <tr>
                <?php
            }
                ?>
        </table>
    </div>
    <div class="enlace">
        <a href="./reviews.php">New Review</a>
        <br><br>
        <a href="../comments/index.php">Página anterior</a>
    </div>
    <br>

</body>

</html>