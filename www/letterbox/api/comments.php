<?php
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,"/") !== 0){
    $url = "/$url";
}

$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);

header("Content-Type:application/json");
error_log("URL: " . $url);
error_log("METHOD: " . $_SERVER['REQUEST_METHOD']);
if($url == $urlPrefix . '/comments' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List of comments");
    $comments = getAllComments($dbConn);
    echo json_encode($comments);

}

if(preg_match("/comment\/([0-9]+)\/review/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("List of comment reviewed");

    $commentCod = $matches[1];
    $reviews = getReviews($dbConn, $commentCod);
    echo json_encode($reviews);
    return;
}


if($url == $urlPrefix . '/comments' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create comment");
    $input = $_POST;
    $Cod = addComment($input, $dbConn);
    if($Cod){
        $input['CodComment'] = $Cod;
        $input['link'] = "/comments/$Cod";
    }

    echo json_encode($input);
    return;
}

if(preg_match("/comments\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update comment");

    $input = $_GET;
    $Cod = $matches[1];
    updateComment($input, $dbConn, $Cod);

    $comment = getComment($dbConn, $Cod);
    echo json_encode($comment);
    return;
}

if(preg_match("/comments\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get Comment");

    $CodComment= $matches[1];
    $comment = getComment($dbConn, $CodComment);

    echo json_encode($comment);
    return;
}

if(preg_match("/comments\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $Cod = $matches[1];
    error_log("Delete comment: ". $Cod);
    $deletedCount = deleteComment($dbConn, $Cod);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'Cod'=> $Cod,
        'deleted'=> $deleted
    ]);
    return;
}

/**
 * Get all records
 *
 * @param $db
 * @return mixed fetchAll result
 */
function getAllComments($db) {
    $statement = $db->prepare("SELECT comment.*,  CommentDate , comments
          FROM comment ");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}
/*
 * Get record based on ID
 *
 * @param $db
 * @param $id
 *
 * @return mixed Associative Array with statement fetch
 */
function getComment($db, $Cod) {
    $statement = $db->prepare("SELECT comment.*, CommentDate, comments
          FROM comment
          WHERE CodComment=:Cod");
    $statement->bindValue(':Cod', $Cod);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Delete record based on ID
 *
 * @param $db
 * @param $id
 * 
 * @return integer number of deleted records
 */
function deleteComment($db, $Cod) {
    $sql = "DELETE FROM comment where CodComment=:Cod";
    $statement = $db->prepare($sql);
    $statement->bindValue(':Cod', $Cod);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Add record
 *
 * @param $input
 * @param $db
 * @return integer id of the inserted record
 */
function addComment($input, $db){

    $sql = "INSERT INTO comment
          (comments, CommentDate, CodReview) 
          VALUES 
          (:comments, :CommentDate, :CodReview)";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);

    $statement->execute();

    return $db->lastInsertId();
}

/**
 * @param $statement
 * @param $params
 * @return PDOStatement
 */
function bindAllValues($statement, $params){
    $allowedFields = ['CommentDate','comments', 'CodReview'];

    foreach($params as $param => $value){
        if(in_array($param, $allowedFields)){
            error_log("bind $param $value");
            $statement->bindValue(':'.$param, $value);
        }
    }
    return $statement;
}

/**
 * Get fields as parameters to set in record
 *
 * @param $input
 * @return string
 */
function getParams($input) {
    $allowedFields = ['CommentDate','comments', 'CodReview'];

    foreach($input as $param => $value){
        if(in_array($param, $allowedFields)){
                $filterParams[] = "$param=:$param";
        }
    }

    return implode(", ", $filterParams);
}

function getReviews($db, $CodReview) {
    $statement = $db->prepare("SELECT review.*, comment.*
          FROM review left join comment on  review.CodReview = comment.CodReview
         WHERE comment.CodComment = :CodComment");
    $statement->bindParam(':CodComment', $CodReview, PDO::PARAM_INT );
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
} 


/**
 * Update Record
 *
 * @param $input
 * @param $db
 * @param $id
 * @return integer number of updated records
 */
function updateComment($input, $db, $Cod){

    $fields = getParams($input);

    $sql = "
          UPDATE comment
          SET $fields 
          WHERE CodComment=$Cod
           ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $Cod;
}
