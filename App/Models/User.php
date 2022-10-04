<?php

namespace App\Models;
require_once 'DB.php';

class User {
    private static $table = 'user_account';

    /**
     * select
     * 
     * Returns all the data of a user 
     * @param int $id
     * @return mixed
    */
    public static function select(int $id){        
        $conn = new DB();
        
        $select = 'SELECT * FROM '.self::$table.' 
                   WHERE id = :id';
        $qry = $conn->get()->prepare($select);        

        $qry->bindValue(':id', $id, \PDO::PARAM_INT);
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return $qry->fetch(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception ('User not found');            
        }
    }

    /**
     * selectAll
     * 
     * Returns a list with some data of all the users
     * @param int $id
     * @return mixed
    */    
    public static function selectAll(){  
        $conn = new DB();

        $select = 'SELECT id, name, email, active, is_admin 
                   FROM '.self::$table;
        $qry = $conn->get()->prepare($select);
        
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return $qry->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception ('Users not found');            
        }              
    }

    /**
     * update
     * 
     * Updates the data of a user 
     * @param int $id
     * @param string $name
     * @param string $active
     * @return mixed
    */
    public static function update(int $id, string $name, string $active){
        $conn = new DB();

        $sql_name = "";
        if (!empty($name)){//only update if it's their own name
            $sql_name = " name = :name ";
        }        
        $sql_active = "";
        if (!empty($active)){
            if (!empty($name)){
                $sql_active = ", ";
            }
            $sql_active .= ' active = '.($active == 'on' ? 'true' : 'false');
        }

        $sql = 'UPDATE '.self::$table.'
                   SET 
                   '.$sql_name.'
                   '.$sql_active.'
                   WHERE id = :id';
        $qry = $conn->get()->prepare($sql);

        $qry->bindValue(':name', $name, \PDO::PARAM_STR);
        $qry->bindValue(':id', $id, \PDO::PARAM_INT);
        
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return $qry->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception ('Error on update');            
        }     
    }

    /**
     * insert
     * 
     * Creates a new user 
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $is_admin
     * @return string
    */    
    public static function insert($name, $email, $password, $is_admin){
        $conn = new DB();

        $sql_admin1 = "";
        if (!empty($is_admin) && $is_admin == 'on'){
            $sql_admin1 = ", is_admin";
            $sql_admin2 = ", :is_admin";            
        }

        $sql = 'INSERT INTO '.self::$table.'
                 (name, email, password '.$sql_admin1.') VALUES
                 (:name, :email, :password '.$sql_admin2.')';
        $qry = $conn->get()->prepare($sql);

        $qry->bindValue(':name', $name, \PDO::PARAM_STR);
        $qry->bindValue(':email', $email, \PDO::PARAM_STR);
        $qry->bindValue(':password', $password, \PDO::PARAM_STR);
        if (!empty($is_admin) && $is_admin == 'on'){
            $is_admin = true;
            $qry->bindValue(':is_admin', $is_admin, \PDO::PARAM_BOOL);
        }
        
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return 'User created successfully';
        } else {
            throw new \Exception ('Error on insert');            
        }     
    }
}