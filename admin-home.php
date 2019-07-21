<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
?>
<html lang="en">
  <head>
    <title>Admin Home</title>
  </head>
  <body>
    <?php
    include_once "_header.php";
    ?>
    <a class="create-user-button  black_btn btn btn-primary" href="create-user.php">Create User</a>
    <?php
    $users->listUsers();
    include_once "_footer.php";
    ?>
  </body>
  <?php include_once "_js.php"; ?>
</html>
