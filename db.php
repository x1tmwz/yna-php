<?php
class DB{

    private static $db;

    public static function getDB() {
        if (!self::$db) {
            try {
                $dbhost = "localhost";
                $dbname = "test";
                $dbuser = "root";
                $dbpass = "123456789";
                self::$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                echo 'Query failed' . $e->getMessage();
            }
        }
        return self::$db;
    }
}
?>