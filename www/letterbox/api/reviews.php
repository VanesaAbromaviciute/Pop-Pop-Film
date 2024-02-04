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
if($url == $urlPrefix . '/reviews' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List of reviews");
    $reviews = getAllReviews($dbConn);
    echo json_encode($reviews);

}

if(preg_match("/review\/([0-9]+)\/comment/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("List review comments");

    $reviewCod = $matches[1];
    $comments = getComments($dbConn, $reviewCod);
    echo json_encode($comments);
    return;
}

if(preg_match("/review\/([0-9]+)\/movie/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("List review movies");

    $reviewCod = $matches[1];
    $movies = getMovies($dbConn, $reviewCod);
    echo json_encode($movies);
    return;
}

if(preg_match("/review\/([0-9]+)\/user/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("List review user");

    $reviewCod = $matches[1];
    $users = getUsers($dbConn, $reviewCod);
    echo json_encode($users);
    return;
}

if($url == $urlPrefix . '/reviews' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create rewiev");
    $input = $_POST;
    $reviewCod = addReview($input, $dbConn);
    if($reviewCod){
        $input['CodReview'] = $reviewCod;
        $input['link'] = "/reviews/$reviewCod";
    }

    echo json_encode($input);
}

if(preg_match("/reviews\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update review");

    $input = $_GET;
    $reviewCod = $matches[1];
    updateReview($input, $dbConn, $reviewCod);

    $review = getReview($dbConn, $reviewCod);
    echo json_encode($review);
}

if(preg_match("/reviews\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get reviews");

    $reviewCod = $matches[1];
    $review = getReview($dbConn, $reviewCod);

    echo json_encode($review);
}

if(preg_match("/reviews\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $reviewCod= $matches[1];
    error_log("Delete review: ". $reviewCod);
    $deletedCount = deleteReview($dbConn, $reviewCod);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'CodReview'=> $reviewCod,
        'deleted'=> $deleted
    ]);
}

/**
 * Get Record based on ID
 *
 * @param $db
 * @param $id
 *
 * @return mixed Associative Array with statement fetch
 */
function getReview($db, $Cod) {
    $statement = $db->prepare("SELECT review.*, ReviewDate , StarNumber
          FROM review 
         WHERE CodReview=:Cod");
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
function deleteReview($db, $Cod) {
    $sql = "DELETE FROM review where CodReview=:CodReview";
    $statement = $db->prepare($sql);
    $statement->bindValue(':CodReview', $Cod);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Get all records
 *
 * @param $db
 * @return mixed fetchAll result
 */
function getAllReviews($db) {
    $statement = $db->prepare("SELECT review.*,  ReviewDate as review_date, StarNumber as star_number 
          FROM review ");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

/**
 * Add record
 *
 * @param $input
 * @param $db
 * @return integer id of the inserted record
 */
function addReview($input, $db){

    $sql = "INSERT INTO review 
          (ReviewDate, StarNumber, CodUser, CodMovie) 
          VALUES 
          (:ReviewDate, :StarNumber, :CodUser, :CodMovie)";

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
    $allowedFields = ['ReviewDate', 'StarNumber', 'CodUser', 'CodMovie'];

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
    $allowedFields = ['ReviewDate', 'StarNumber', 'CodUser', 'CodMovie'];

    foreach($input as $param => $value){
        if(in_array($param, $allowedFields)){
                $filterParams[] = "$param=:$param";
        }
    }

    return implode(", ", $filterParams);
}


/**
 * Update Record
 *
 * @param $input
 * @param $db
 * @param $id
 * @return integer number of updated records
 */
function updateReview($input, $db, $Cod){

    $fields = getParams($input);

    $sql = " UPDATE review 
          SET $fields 
          WHERE CodReview = $Cod ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $Cod;
}

/**
 *
 *
 * @param $db
 * @param $postId
 * @return mixed fetchAll result
 */
 function getComments($db, $CodReview) {
    $statement = $db->prepare("SELECT comment.*, review.*
          FROM comment left join review on  comment.CodReview = review.CodReview
         WHERE review.CodReview = :CodReview");
    $statement->bindParam(':CodReview', $CodReview, PDO::PARAM_INT );
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
} 


/**
 *
 *
 * @param $db
 * @param $postId
 * @return mixed fetchAll result
 */
function getMovies($db, $CodReview) {
    $statement = $db->prepare("SELECT movie.*, review.*
        FROM movie left join review on  review.CodMovie = movie.CodMovie
        WHERE review.CodMovie = :CodMovie;");
          $statement->bindParam(':CodMovie', $CodReview, PDO::PARAM_INT );
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
} 


/**
 * 
 *
 * @param $db
 * @param $postId
 * @return mixed fetchAll result
 */
function getUsers($db, $CodReview) {
    $statement = $db->prepare("SELECT user.*, review.*
        FROM user left join review on review.CodUser= user.CodUser
        WHERE review.CodUser = :CodUser;");
          $statement->bindParam(':CodUser', $CodReview, PDO::PARAM_INT );
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
} 