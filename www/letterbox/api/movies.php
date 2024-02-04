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
if($url == $urlPrefix . '/movies' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("Get all the movies "); 
    $movies = getAllMovies($dbConn);
    echo json_encode($movies);
}

if(preg_match("/movie\/([0-9]+)\/director/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("List of directors");

    $movieCod = $matches[1];
    $directors = getDirectors($dbConn, $movieCod);
    echo json_encode($directors);
    return;
}


if($url == $urlPrefix . '/movies' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create movie");
    $input = $_POST;
    $movieCod = addMovie($input, $dbConn);
    if($movieCod){
        $input['CodMovie'] = $movieCod;
        $input['link'] = "/movies/$movieCod";
    }
    echo json_encode($input);
}

if(preg_match("/movies\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    $input = $_GET;
    $movieCod = $matches[1];
    error_log("Update movie ");
    $movieCod = updateMovie($input, $dbConn, $movieCod);
    var_dump($input);
}

if(preg_match("/movies\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    $movieCod = $matches[1];
    error_log("Get a movie");
    $movieCod = getMovie ($dbConn, $movieCod);
    echo json_encode ($movieCod);
}

if(preg_match("/movies\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){
    $movieCod = $matches[1];
    error_log("Delete a movie");
    $movies = deleteMovie($dbConn, $movieCod);
    echo json_encode ($movies); 
}

function getAllMovies($db) {
    $statement = $db->prepare(
    "SELECT movie.*,
    MovieName,
    MovieDuration,
    ReleaseDate
    FROM movie");
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
function addMovie($input, $db){

    $sql = "INSERT INTO movie 
          (MovieName, MovieDuration, ReleaseDate, CodDirector) 
          VALUES 
          (:MovieName, :MovieDuration, :ReleaseDate, :CodDirector)";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);

    $statement->execute();

    return $db->lastInsertId();
}

function bindAllValues($statement, $params){
    $allowedFields = ['MovieName', 'MovieDuration', 'ReleaseDate','CodDirector'];
    
    foreach($params as $param => $value){
    if(in_array($param, $allowedFields)){
    error_log("bind $param $value");
    $statement->bindValue(':'.$param, $value);
    }
    }
    
    return $statement;
}

function getMovie($db, $Cod) {
    $statement = $db->prepare("SELECT movie.*,MovieName, MovieDuration, ReleaseDate
          FROM movie 
         WHERE CodMovie=:Cod");
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
function deleteMovie($db, $Cod) {
    $sql = "DELETE FROM movie where CodMovie=:CodMovie";
    $statement = $db->prepare($sql);
    $statement->bindValue(':CodMovie', $Cod);
    $statement->execute();
    return $statement->rowCount();
}


/**
 * Update Record
 *
 * @param $input
 * @param $db
 * @param $id
 * @return integer number of updated records
 */
function updateMovie($input, $db, $Cod){

    $fields = getParams($input);

    $sql = " UPDATE movie 
          SET $fields 
          WHERE CodMovie = $Cod ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $Cod;
}

function getDirectors($db, $CodMovie) {
    $statement = $db->prepare("SELECT director.*, movie.*
          FROM director left join movie on  movie.CodDirector = director.CodDirector
         WHERE movie.CodMovie = :CodMovie");
    $statement->bindParam(':CodMovie', $CodMovie, PDO::PARAM_INT );
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
} 

function getParams($input) {
    $allowedFields = ['MovieName', 'MovieDuration', 'ReleaseDate', 'CodDirector'];
    
    foreach($input as $param => $value){
    if(in_array($param, $allowedFields)){
    $filterParams[] = "$param=:$param";
    }
    }
    
    return implode(", ", $filterParams);
}