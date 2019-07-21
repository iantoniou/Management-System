<?php
require_once 'private/classes/Users.class.php';
$users = new Users();
$users->logout();
header("location: index.php");
?>
