<?php

namespace App\Models;
require_once 'DB.php';

class Login {
    private static $table = 'user_account';

    /**
     * login
     * 
     * First step to log into the system
     * @param string $email
     * @param string $password
     * @return mixed
    */
    public static function login(string $email, string $password){
        $conn = new DB();
        
        $select = 'SELECT id, is_admin, name
                   FROM '.self::$table.' 
                   WHERE email = :email 
                   AND   password = :password
                   AND   active';
        $qry = $conn->get()->prepare($select);        

        $qry->bindValue(':email', $email, \PDO::PARAM_STR);
        $qry->bindValue(':password', $password, \PDO::PARAM_STR);
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return $qry->fetch(\PDO::FETCH_ASSOC);
        } else {
            throw new \Exception ('Incorrect Email/Password or Inactive User');            
        }
    }


    /**
     * setMFA
     * 
     * Second step to log into the system (MFA)
     * @param int $id
     * @return int
    */
    public static function setMFA($id){
        $conn = new DB();

        $code = rand(111111, 999999);
        
        $sql = 'UPDATE '.self::$table.' SET mfa_code = '.$code.' WHERE id = :id';
        $qry = $conn->get()->prepare($sql);
        $qry->bindValue(':id', $id, \PDO::PARAM_INT);
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return $code;
        } else {
            throw new \Exception ('Error generating code');
        }
    }
}