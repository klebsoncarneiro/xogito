<?php

namespace App\Models;
require_once 'DB.php';

class Mfa {
    private static $table = 'user_account';

    /**
     * verify
     * 
     * Verifies if the MFA code is valid 
     * @param int $id
     * @param string $code
     * @return int
    */
    public static function verify(int $id, string $code){
        $conn = new DB();
        
        $select = 'SELECT 1
                   FROM '.self::$table.' 
                   WHERE id = :id
                   AND   mfa_code = :code
                   AND   active';
        $qry = $conn->get()->prepare($select);        

        $qry->bindValue(':id', $id, \PDO::PARAM_INT);
        $qry->bindValue(':code', $code, \PDO::PARAM_STR);
        $qry->execute();
        
        if ($qry->rowCount() > 0){
            return $id;
        } else {
            throw new \Exception ('Incorrect Code');            
        }
    }
}