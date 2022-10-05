<?
session_start();

$base_dir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/xogito/';

if (empty($_SESSION['logged_in']) || $_SESSION['is_admin'] == false){
    header("Location: ../");
    die;
}

$opts = array('http' =>
  array(
      'method'  => 'GET',
      'header' =>  'Authorization: Bearer '.$_SESSION['token']
  )
);

$context = stream_context_create($opts);

$json = file_get_contents('http://localhost/xogito/api/user/', false, $context);

$json_return = json_decode($json);

if ($json_return->status == 'error'){

  $_SESSION['error'] = $json_return->data;

  header("Location: ../");
  die;

} else {

  $json_data = json_decode($json)->data;

  foreach($json_data as $data){
    $dados[] = get_object_vars($data);
  }
  
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../styles/style.css" />
  <title>Xogito API Klebson - Dashboard</title>
</head>
<body>

  <h1>Users List</h1>
  <h2>Logged User: <?= $_SESSION['name'] ?> (<?= $_SESSION['is_admin']=='t'?'Admin':'Basic User' ?>)</h2>

  <hr />
  <div>
    <table border="1">
      <tr>
        <th>Name</th>
        <th>E-mail</th>
        <th>Active</th>
        <th>Admin</th>
        <th>Action</th>
      </tr>      
      <?php foreach($dados as $dado): ?>
        <tr>
          <td><?=$dado['name']?></td>
          <td><?=$dado['email']?></td>
          <td><?=$dado['active'] == true ? 'Yes' : 'No' ?></td>
          <td><?=$dado['is_admin'] == true ? 'Yes' : 'No' ?></td>
          <td><a href="<?= $base_dir ?>pages/user.php?id=<?=$dado['id']?>">Edit</a></td>
        </tr>
      <?php endforeach; ?>
      
    </table>
  </div>
  <?php include "includes/menu.php"; ?>
    
</body>
</html>