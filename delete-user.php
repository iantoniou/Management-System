<?php
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
$users->deleteUser();
?>
