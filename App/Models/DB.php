<?php

namespace App\Models;

class DB {

    private $conn;

    /**
     * __construct
     * 
     * Connects to a database
     * @param void
     * @return void
    */
    function __construct(){
        try {
            $this->conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);            
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }   
    
    /**
     * get
     * 
     * Returns the private $conn variable
     * @param void
     * @return PDO
    */
    function get(){
        return $this->conn;
    }

}