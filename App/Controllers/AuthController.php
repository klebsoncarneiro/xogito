<?php
    namespace App\Controllers;

    class AuthController {

        /**
         * login
         * 
         * Third login step, creating a token
         * @param void
         * @return string
        */   
        public function login(){

            if (!empty($_POST['email'])){
                //Application Key
                $key = TOKEN_SECRET;

                //Header Token
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                //Payload - Content
                $payload = [
                    'exp' => time() + 3600, //1 hour
                    'app' => 'xogito',
                    'email' => $_POST['email']
                ];

                //JSON
                $header = json_encode($header);
                $payload = json_encode($payload);

                //Base 64
                $header = base64_encode($header);
                $payload = base64_encode($payload);

                //Sign
                $sign = hash_hmac('sha256', $header . "." . $payload, $key, true);
                $sign = base64_encode($sign);

                //Token
                $token = $header . '.' . $payload . '.' . $sign;

                return $token;
            }
            throw new \Exception('Not authenticated');
        }

        /**
         * checkAuth
         * 
         * Verifies if the token e valid
         * @param void
         * @return boolean
        */  
        public static function checkAuth(){
            $http_header = apache_request_headers();
            $bearer = explode (' ', $http_header['Authorization']);
            $token = explode('.', $bearer[1]);
            $header = $token[0];
            $payload = $token[1];
            $sign = $token[2];

            //Validate
            $valid = hash_hmac('sha256', $header . "." . $payload, TOKEN_SECRET, true);
            $valid = base64_encode($valid);

            if ($sign === $valid){
                return true;
            }
            return false;           
        }
        
    }