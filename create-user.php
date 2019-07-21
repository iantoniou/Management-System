<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
$users->attemptCreateUser();
include_once "_header.php";
include_once "_footer.php";
?>
