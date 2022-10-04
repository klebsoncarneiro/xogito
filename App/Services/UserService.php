<?php

namespace App\Services;

use App\Models\User;

class UserService {

   /**
   * get
   * 
   * Service verb get to access select and selectAll function of User Model
   * with $_POST variable inside
   * @param [int | void]
   * @return mixed
   */
    public function get($id = null){
        if ($id){
            return User::select($id);
        } else {
            return User::selectAll();
        }
    }

   /**
   * post
   * 
   * Service verb post to access insert function of User Model
   * with $_POST variable inside
   * @param void
   * @return string
   */
    public function post(){

        $name = pg_escape_string($_POST['name']);
        $email = pg_escape_string($_POST['email']);
        $password = pg_escape_string($_POST['password']);
        $is_admin = pg_escape_string($_POST['is_admin']);

        return User::insert($name, $email, $password, $is_admin);             
        
    }

    /**
   * post
   * 
   * Service verb put to access update function of User Model
   * with raw $post_vars variable inside
   * @param int $id
   * @return mixed
   */
    public function put($id){

        parse_str(file_get_contents("php://input"),$post_vars);

        $name = pg_escape_string($post_vars['name']);
        $active = pg_escape_string($post_vars['active']);

        return User::update($id, $name, $active);        

    }

}