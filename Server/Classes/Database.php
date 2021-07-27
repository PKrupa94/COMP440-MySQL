<?php

class Database{

    private $DATABASE_HOST = "localhost";
    private $DATABASE_NAME = "comp440_project";
    private $DATABASE_USER = "UserComp440";
    private $DATABASE_PASSWORD = "mysql12345";

    public function dbConn() {

        try {

            $conn = new PDO('mysql:host='.$this -> DATABASE_HOST.';dbname='.$this -> DATABASE_NAME,
                                          $this -> DATABASE_USER,           $this -> DATABASE_PASSWORD);
            $conn -> setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            return $conn;
        } catch( Exception $e ) {

            echo "Error: Could not connect- ".$e -> getMessage();
            exit;
        }
    }
}

?>
