<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/SubmitRequest.class.php';
$request = new SubmitRequest();
$request->attemptSubmitRequest();
include_once "_header.php";
include_once "_footer.php";
?>
