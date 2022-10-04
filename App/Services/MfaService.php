<?php

namespace App\Services;

use App\Models\Mfa;

class MfaService {

   /**
   * post
   * 
   * Service verb post to access verify function of Mfa Model
   * with $_POST variable inside
   * @param void
   * @return int
   */       
    public function post(){

        $id = pg_escape_string($_POST['id']);
        $code = pg_escape_string($_POST['code']);

        return Mfa::verify($id, $code);

    }

}