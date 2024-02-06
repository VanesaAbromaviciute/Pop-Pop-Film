<?php
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apiUrl = $webServer . '/comments';
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

    $comments = json_decode($server_output);

    $_GET["Cod"] = $comments->CodComment;

    include("view_comments.php");
} else {
    $apiUrl = $webServer . '/reviews';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $reviews = json_decode($json);

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
        <title> Insert comment</title>
    </head>

    <body>
        <div class="text">
            <h1>Insert Comment</h1>
        </div>
        <div class="form-container">
            <form method="post">
                <form>
                    <label for="date">Comment Date:</label>
                    <input type="date" id="date" name="CommentDate">
                    <br>
                    <label for="comments">Comment:</label>
                    <input type="text" id="comments" name="comments">
                    <br>
                    <label for="CodReview">Review:</label>
                    <select name="CodReview" id="CodReview">
                        <?php
                        foreach ($reviews as $review) { ?>
                            <option value="<?= $review->CodReview ?>"><?= $review->CodReview ?></option>
                        <?php
                        }
                        ?>
                    </select>
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