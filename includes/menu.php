<?php 
    $base_dir = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/xogito/';
?>
<div id="menu">
  <p>Menu:</p>
  <ul>
  <?php if ($_SESSION['is_admin'] == true): ?>
    <li><a href="<?= $base_dir ?>pages/users">List Users</a></li>
    <li><a href="<?= $base_dir ?>pages/newuser">New User</a></li>
  <?php endif; ?>
    <li><a href="<?= $base_dir ?>pages/user.php?id=<?=$_SESSION['id']?>">Edit my name</a></li>
    <li><a href="<?= $base_dir ?>pages/logout">Logout</a></li>
  </ul>
</div>