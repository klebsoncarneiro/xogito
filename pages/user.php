<?
session_start();
$base_dir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/xogito/';

if (empty($_SESSION['logged_in'])){
    header("Location: ../");
    die;
}

$id = (int) $_GET['id'];

if ($_SESSION['id'] != $id && $_SESSION['is_admin'] == false){
  header("Location: ../");
  die;
}

if (!empty($_POST['name']) || !empty($_POST['active'])){//update
  $name = pg_escape_string($_POST['name']);
  $active = pg_escape_string($_POST['active']);

  $postdata = http_build_query(
    array(
        'name' => $name,
        'active' => $active
    )
  );

  $opts = array('http' =>
    array(
        'method'  => 'PUT',
        'content' => $postdata,
        'header' => "Content-type: application/x-www-form-urlencoded\r\n"
        . "Content-Length: " . strlen($postdata) . "\r\n",
    )
  );

  $context = stream_context_create($opts);

  $json = file_get_contents('http://localhost/xogito/api/user/'.$id, false, $context);

  $json_return = json_decode($json);

  if ($json_return->status == 'error'){
    $_SESSION['error'] = $json_return->data;
  } 
}

$json = file_get_contents('http://localhost/xogito/api/user/'.$id);

$json_return = json_decode($json);

if ($json_return->status == 'error'){

  $_SESSION['error'] = $json_return->data;

  header("Location: ../");
  die;

} else {

  $json_data = json_decode($json)->data;

  $dados = get_object_vars($json_data);
  
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../styles/style.css" />
    <title>Xogito API Klebson - Dashboard</title>
</head>
<body>

  <h1>Edit User</h1>
  <h2>Logged User: <?= $_SESSION['name'] ?> (<?= $_SESSION['is_admin']=='t'?'Admin':'Basic User' ?>)</h2>
  <form action="" method="post">
    <?php
    if ($_SESSION['id'] == $id) : //can update their own name
    ?>
      <label for="name">Name:</label> <input type="text" id="name" name="name" required value="<?=$dados['name']?>" /> <br />
    <?php
    else:
    ?>
    <label for="name">Name:</label> <?=$dados['name']?><br />
    <?php
    endif;
    ?>
    <label for="email">E-mail:</label> <?=$dados['email']?> <br />
    <label for="password">Password:</label> <?=str_repeat('*', strlen($dados['password'])) ?><br />
    <label for="is_admin">Is Admin?</label> <?= $dados['is_admin'] == true ? 'Yes' : 'No' ?><br />
    <?php if ($_SESSION['is_admin'] == true): ?>
      <label for="active">Active?</label> 
      <input type="radio" id="active" name="active" value="on" <? if ($dados['active'] == true){ echo 'checked'; } ?> />Yes 
      <input type="radio" id="active" name="active" value="off" <? if ($dados['active'] == false){ echo 'checked'; } ?> />No<br />
    <?php else: ?>
      <label for="active">Active?</label> <?= $dados['active'] == 't' ? 'Yes' : 'No' ?><br />
    <?php endif; ?>
    <button>Update</button>
    <?php     
    if (isset($_SESSION['error'])){
        echo "<p style='color:red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    ?>
  </form>

  <?php include "../includes/menu.php"; ?>
    
</body>
</html>