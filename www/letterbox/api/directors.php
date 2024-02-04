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
if($url == $urlPrefix . '/directors' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List of directors");
    $directors = getAllDirectors($dbConn);
    echo json_encode($directors);

}


if($url == $urlPrefix . '/directors' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create director");
    $input = $_POST;
    $Cod = addDirector($input, $dbConn);
    if($Cod){
        $input['CodDirector'] = $Cod;
        $input['link'] = "/comments/$Cod";
    }

    echo json_encode($input);
    return;
}

if(preg_match("/directors\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update director");

    $input = $_GET;
    $Cod = $matches[1];
    updateDirector($input, $dbConn, $Cod);

    $director = getDirector($dbConn, $Cod);
    echo json_encode($director);
    return;
}

if(preg_match("/directors\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get directors");

    $CodDirector= $matches[1];
    $director = getDirector($dbConn, $CodDirector);

    echo json_encode($director);
    return;
}

if(preg_match("/directors\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $Cod = $matches[1];
    error_log("Delete director: ". $Cod);
    $deletedCount = deleteDirector($dbConn, $Cod);
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
function getAllDirectors($db) {
    $statement = $db->prepare("SELECT director.*,  DirectorAge , DirectorName,DirectorLastname
          FROM director ");
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
function getDirector($db, $Cod) {
    $statement = $db->prepare("SELECT director.*, DirectorAge, DirectorName, DirectorLastname
          FROM director
          WHERE CodDirector=:Cod");
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
function deleteDirector($db, $Cod) {
    $sql = "DELETE FROM director where CodDirector=:Cod";
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
function addDirector($input, $db){

    $sql = "INSERT INTO director
          (DirectorAge, DirectorName, DirectorLastname) 
          VALUES 
          (:DirectorAge, :DirectorName, :DirectorLastname)";

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
    $allowedFields = ['DirectorAge','DirectorName', 'DirectorLastname'];

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
    $allowedFields = ['DirectorAge','DirectorName', 'DirectorLastname'];

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
function updateDirector($input, $db, $Cod){

    $fields = getParams($input);

    $sql = "
          UPDATE director
          SET $fields 
          WHERE CodDirector=$Cod
           ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $Cod;
}
