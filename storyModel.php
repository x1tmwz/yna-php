<?php
include_once './db.php';
try {
    $tableName = "story_books";
    $con =DB::getDB();
} catch (Exception $ex) {
    echo $ex->getMessage() . " " . $email;
}
 
function checkIfTableExists()
{
    global $tableName;
        $sql = "SHOW TABLES LIKE '" . $tableName . "';";
        $result = query($sql,null);
        return count($result);
}

function query($sql,$data){
    global $con;
    if(!$sql){
        return false;
    }
    try {
        $q = $con->prepare($sql);
        if(isset($data)){
            $q->execute();
        }else{
            $q->execute($data);
        }
        $rows = $q->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    } catch (PDOException $e) {
        var_dump("Error: " .  $e->getMessage());
        return 'error';
    }
}

function createTable(){
    global $tableName;
    $isTableExists = checkIfTableExists() != 0;
    if ($isTableExists) {
        return;
    }
    $sql = "CREATE TABLE ".$tableName." (id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(50),author VARCHAR(50),conent LONGTEXT,created_date TIMESTAMP) CHARACTER SET = utf8";
    return query($sql,null);
}
function insert($title,$author,$content){
    global $tableName;
    $sql =  "INSERT INTO ".$tableName." (title,author,content) VALUES (:title,:author,:content);";
    return query($sql,array(
        ':title' => $title,
        ':author' => $author,
        ':content'=>$content
    ));

}
function update($title,$author,$content,$id){
    global $tableName;
    $sql="UPDATE".$tableName."SET title=:title author=:author content=:conent where id=:id";
    $data = array(
        ':title' => $title,
        ':author' => $author,
        ':content'=>$content,
        ':id'=>$id
    );
    return query($sql,$data);
}
function delete($id){
    global $tableName;
    $sql="DELETE from".$tableName."where id=:id";
    $data = array(
        ':id'=>$id
    );
    return query($sql,$data);
}
function selectStoryById($id){
    global $tableName;
    $sql="SELECT * from".$tableName."where id=:id";
    $data = array(
        ':id'=>$id
    );
    return query($sql,$data);
}
function selectRecentStories(){
    global $tableName;
    $sql="SELECT * from".$tableName." order by created_date DESC limit 20";
    return query($sql,null);
}
function selectAllStories(){
    global $tableName;
    $sql="SELECT * from".$tableName." order by created_date DESC";
    return query($sql,null);
}
?>