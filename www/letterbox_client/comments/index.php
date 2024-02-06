<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@1&display=swap');
    </style>
    <title>Comments</title>
</head>

<body>
    <?php
    require_once "../config.php";

    $apiUrl = $webServer . '/comments';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $comments = json_decode($json);
    curl_close($curl);

    ?>
    <div class="text">
    <h1>Comment List</h1>
    </div>
    <div class="table">
    <table border="1">
        <tr>
            <td>Cod</td>
            <td>Date</td>
            <td>Comment</td>
            <td>Review</td>
            <td>Actions</td>
        </tr>
        <?php
        foreach ($comments as $comment) {
            echo "<tr>";
            echo "<td>";
            echo "<a href='$urlPrefix/comments/view_comments.php?Cod=$comment->CodComment'>$comment->CodComment</a>";
            echo "<td>$comment->CommentDate</td>";
            echo "<td>$comment->comments</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/reviews/review_cod.php?Cod=$comment->CodComment'>Review</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='$urlPrefix/comments/put_comments.php?Cod=$comment->CodComment'>Edit</a>";
            echo " ";
            echo "<a href='$urlPrefix/comments/delete_comments.php?Cod=$comment->CodComment'>Delete</a>";
            echo "</td>";
            echo "<tr>";
        }
        ?>
    </table>
    </div>
    <br>
    <div class="enlace">
    <a href="./comments.php">New Comment</a>
    <br><br>
    <a href="../index.php">Previous Page</a>
    </div>
</body>

</html>