<?
session_start();
$base_dir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/xogito/';

if (empty($_SESSION['logged_in']) || $_SESSION['is_admin'] == false){
  header("Location: ../");
  die;
}

if (!empty($_POST['name'])){//insert
  $name = pg_escape_string($_POST['name']);
  $email = pg_escape_string($_POST['email']);
  $password = pg_escape_string($_POST['password']);
  $is_admin = pg_escape_string($_POST['is_admin']);

  $postdata = http_build_query(
    array(
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'is_admin' => $is_admin
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

  $json = file_get_contents('http://localhost/xogito/api/user/', false, $context);

  $json_return = json_decode($json);

  if ($json_return->status == 'error'){
    $_SESSION['error'] = $json_return->data;
  } else {
    $_SESSION['success'] = $json_return->data;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../styles/style.css" />
    <title>Xogito API Klebson</title>
</head>
<body>

  <h1>New User Page</h1>
  <h2>Logged User: <?= $_SESSION['name'] ?> (<?= $_SESSION['is_admin']=='t'?'Admin':'Basic User' ?>)</h2>

  <form action="" method="post">
    <label for="name">Name:</label> <input type="text" id="name" name="name" required /> <br />
    <label for="email">E-mail:</label> <input type="text" id="email" name="email" required /> <br />
    <label for="password">Password:</label> <input type="password" id="password" name="password" required /><br />
    <label for="is_admin">Is Admin?</label>
    <input type="radio" id="is_admin" name="is_admin" value="on" />Yes 
    <input type="radio" id="is_admin" name="is_admin" value="off" checked />No<br />     
    <button>Submit</button>
    <?php     
    if (isset($_SESSION['error'])){
        echo "<p style='color:red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
    <?php     
    if (isset($_SESSION['success'])){
        echo "<p style='color:green'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    ?>    
  </form>

  <?php include "includes/menu.php"; ?>
  
</body>
</html>