<?
session_start();

if (empty($_SESSION['logged_in'])){
    header("Location: ../");
    die;
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../../styles/style.css" />
    <title>Xogito API Klebson - Dashboard</title>
</head>
<body>

  <h1>Dashboard</h1>
  <h2>Logged User: <?= $_SESSION['name'] ?> (<?= $_SESSION['is_admin']=='t'?'Admin':'Basic User' ?>)</h2>

  <?php include "includes/menu.php"; ?>
    
</body>
</html>