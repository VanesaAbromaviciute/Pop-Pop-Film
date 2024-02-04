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
<h1>Reviews de un comentario</h1>
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
            <td><a href="<?= $urlPrefix ?>/view_reviews.php?Cod=<?= $review->CodReview?>"><?= $review->CodReview ?></a></td>
            <td><?= $review->ReviewDate ?></td>
            <td><?= $review->StarNumber ?></td>
            <td><a href="../reviews/put_reviews.php?Cod=<?= $review->CodReview ?>">Edit</a></td>
            <td><a href="../reviews/delete_reviews.php?Cod=<?= $review->CodReview ?>">Delete</a></td>
        <tr>
        <?php
    }
        ?>
</table>
<a href="./reviews.php">New Review</a>
<br><br>
<a href="../index.php">Página anterior</a>
<br>