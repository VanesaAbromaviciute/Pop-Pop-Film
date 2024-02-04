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
if($url == $urlPrefix . '/users' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List Users");
    $users = getAllUsers($dbConn);
    echo json_encode($users);
    return;
}

if(preg_match("/users\/([0-9]+)\/review/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("List user reviews");

    $userCod = $matches[1];
    $comments = getReviews($dbConn, $userCod);
    echo json_encode($comments);
    return;
}


if($url == $urlPrefix . '/user' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create user");
    $input = $_POST;
    $userCod = addUser($input, $dbConn);
    if($userCod){
        $input['CodUser'] = $userCod;
        $input['link'] = "/users/$userCod";
    }

    echo json_encode($input);

}

if(preg_match("/users\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update user");

    $input = $_GET;
    $userCod = $matches[1];
    updateUser($input, $dbConn, $userCod);

    $user = getUser($dbConn, $userCod);
    echo json_encode($user);
}

if(preg_match("/users\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get User");

    $userCod = $matches[1];
    $user = getUser($dbConn, $userCod);

    echo json_encode($user);
}

if(preg_match("/users\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $userCod = $matches[1];
    error_log("Delete User: ". $userCod);
    $deletedCount = deleteUser($dbConn, $userCod);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'CodUser'=> $userCod,
        'deleted'=> $deleted
    ]);
}

/**
 * Get record based on ID
 *
 * @param $db
 * @param $Cod
 *
 * @return mixed Associative Array with statement fetch
 */
function getUser($db, $CodUser) {
    $statement = $db->prepare("SELECT * FROM user where CodUser=:CodUser");
    $statement->bindValue(':CodUser', $CodUser);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Delete record based on COD
 *
 * @param $db
 * @param $Cod
 * 
 * @return integer number of deleted records
 */
function deleteUser($db, $CodUser) {
    $sql = "DELETE FROM user where CodUser=:CodUser";
    $statement = $db->prepare($sql);
    $statement->bindValue(':CodUser', $CodUser);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Get all records
 *
 * @param $db
 * @return mixed fetchAll result
 */
function getAllUsers($db) {
    $statement = $db->prepare("SELECT * FROM user");
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
function addUser($input, $db){

    $sql = "INSERT INTO user 
          (UserName, UserLastname, UserNickname, UserAge) 
          VALUES 
          (:UserName, :UserLastname, :UserNickname, :UserAge)";

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
    $allowedFields = ['UserName', 'UserLastname', 'UserNickname', 'UserAge'];

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
    $allowedFields = ['UserName', 'UserLastname', 'UserNickname', 'UserAge'];

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
 * @param $Cod
 * @return integer number of updated records
 */
function updateUser($input, $db, $CodUser){

    $fields = getParams($input);

    $sql = "
          UPDATE user
          SET $fields 
          WHERE CodUser=$CodUser
           ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $CodUser;
}

/**
 * Get all posts of the user
 *
 * @param $db
 * @param $userCod
 * @return mixed fetchAll result
 */
function getReviews($db, $userCod) {
    $statement = $db->prepare("
        SELECT posts.*, users.name as user_name, users.email as user_email
          FROM posts left join users on posts.user_id = users.id
         WHERE user_Cod = $userCod");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

