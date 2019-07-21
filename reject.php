<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/EmailStatus.class.php';
$emailStatus = new EmailStatus();
$emailStatus->rejectedEmail();
?>
