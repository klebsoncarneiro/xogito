<?php

namespace App\Services;

use App\Models\Login;

class LoginService {


  /**
   * post
   * 
   * Service verb post to access login function of Login Model
   * with $_POST variable inside
   * @param void
   * @return mixed
  */    
  public function post(){

    $email = pg_escape_string($_POST['email']);
    $password = pg_escape_string($_POST['password']);
    return Login::login($email, $password);

  }

  /**
   * get
   * 
   * Service verb get to access setMFA function of Login Model
   * @param int $id
   * @return int
  */       
  public function get(int $id){

    return Login::setMFA($id);

  }

}