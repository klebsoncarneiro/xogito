<?php

namespace App\Services;

use App\Models\Mfa;

class MfaService {

   /**
   * post
   * 
   * Service verb post to access verify function of Mfa Model and returns a token
   * with $_POST variable inside
   * @param void
   * @return string
   */       
    public function post(){

        $id = pg_escape_string($_POST['id']);
        $code = pg_escape_string($_POST['code']);
        $email = pg_escape_string($_POST['email']);

        $retorno = Mfa::verify($id, $code);   

        $postdata = http_build_query(
        array(
            'email' => $email
        )
        );
    
        $opts = array('http' =>
        array(
            'method'  => 'POST',
            'content' => $postdata,
            'header' => "Content-type: application/x-www-form-urlencoded\r\n"
            . "Content-Length: " . strlen($postdata) . "\r\n",
        )
        );
    
        $context = stream_context_create($opts);
    
        $retorno = file_get_contents('http://localhost/xogito/auth/login', false, $context);
    
        $json_return = json_decode($retorno);
    
        if ($json_return->status == 'error'){
            throw new \Exception ('Authentication Error');
        } else {
            return $json_return->data;//token
        }

    }

}