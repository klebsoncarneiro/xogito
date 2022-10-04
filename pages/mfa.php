<?
session_start();
if (empty($_SESSION['id']) || empty($_SESSION['mfa'])){
    header("Location: ./");
    die;
}

unset($_SESSION['error']);

if (!empty($_POST['code'])){
  $id = pg_escape_string($_SESSION['id']);
  $code = pg_escape_string($_POST['code']);

  $postdata = http_build_query(
    array(
        'id' => $id,
        'code' => $code
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

  $json = file_get_contents('http://localhost/xogito/api/mfa/', false, $context);

  $json_return = json_decode($json);

  if ($json_return->status == 'error'){
    $_SESSION['error'] = $json_return->data;

    header("Location: ../");
    die;
  }
  $_SESSION['logged_in'] = $_SESSION['id'];
  header("Location: ../dashboard/");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../styles/style.css" />
    <title>Xogito API Klebson - Multi Factor Authentication</title>
</head>
<body>

  <h1>Multi Factor Authentication</h1>

  <p>Please fill in the form below the code you received in your e-mail.</p>
  <?php 
    if ($_SESSION['email_success'] == false){
      echo "<p>For testing purposes only (MailJet error). Type ".$_SESSION['mfa'] ." below:</p>";
    }
  ?>
  
  <form action="" method="post">
    <label for="code">Code:</label> <input type="text" id="code" name="code" required /> <br /> 
    <button>Enter</button>
    <?php     
    if (isset($_SESSION['error'])){
        echo "<p style='color:red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
  </form>
  
</body>
</html>