<?php

    require_once 'vendor/autoload.php';
    $base_dir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/xogito/';
    
    if ($_GET ['url']) {
        $url = explode('/', $_GET['url']);

        /*var_dump ($_POST);
        die;
*/
        if ($url[0] === 'api') {

            header('Content-Type: application/json');
            
            //Shift an element off the beginning of array
            array_shift($url);
            //e.g.: App\Services\\UserService
            $service = 'App\Services\\'.ucfirst($url[0]).'Service';
            //get,post,put,delete
            $method = strtolower($_SERVER['REQUEST_METHOD']);
                        
            try {                
                //Returns the shifted value, or null if array is empty or is not an array.
                array_shift($url);
                if (class_exists($service) && method_exists($service , $method)){
                    $response = call_user_func_array(array(new $service, $method), $url);               
                } else {
                    throw new \Exception ('Invalid Endpoint');  
                }
                echo json_encode(array ('status' => 'success', 'data' => $response));                
            } catch (\Exception $e ) {
                echo json_encode(array ('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);                
            }
            exit;
        } else if ($url[0] === 'auth') {
            header('Content-Type: application/json');
            
            //e.g.: App\Controllers\\AuthController
            $controller = 'App\Controllers\\'.ucfirst($url[0]).'Controller'; 
            
            try {           
                //Shift an element off the beginning of array
                array_shift($url);              
                //verb
                $method = $url[0];                       
              
                //Returns the shifted value, or null if array is empty or is not an array.
                array_shift($url);
                if (class_exists($controller) && method_exists($controller, $method)){
                    $response = call_user_func_array(array(new $controller, $method), $url);               
                } else {
                    throw new \Exception ('Invalid Endpoint');  
                }
                echo json_encode(array ('status' => 'success', 'data' => $response));                
            } catch (\Exception $e ) {
                echo json_encode(array ('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);                
            }
            exit;
        } else if ($url[0] === 'pages') {
            if (file_exists("pages/".$url[1].".php")){
                include "pages/".$url[1].".php";
                exit;
            }
        }
    } else {
        session_start();
        unset($_SESSION['id']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        unset($_SESSION['mfa']);
        unset($_SESSION['is_admin']);
        unset($_SESSION['logged_in']);
        unset($_SESSION['token']);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles/style.css" />
    <title>Xogito API Klebson</title>
</head>
<body>

  <h1>Login Page</h1>
  
  <form action="<?=$base_dir?>pages/login" method="post">
    <label for="email">E-mail:</label> <input type="text" id="email" name="email" required /> <br />
    <label for="password">Password:</label> <input type="password" id="password" name="password" required /><br />
    <button>Enter</button>
    <?php     
    if (isset($_SESSION['error'])){
        echo "<p style='color:red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
  </form>
  <p><a href="<?= $base_dir ?>pages/signup">Don't have an account? Sign up!</a></p>
   
</body>
</html>