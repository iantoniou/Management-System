<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
include_once "_header.php";
$users->prePopulatedForm();
include_once "_footer.php";
?>
