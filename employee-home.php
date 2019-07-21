<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
require_once 'private/classes/SubmitRequest.class.php';
$request = new SubmitRequest();
require_once 'private/classes/ListRequests.class.php';
$listRequests = new ListRequests();
if(isset($_SESSION['email'], $_SESSION['first_name'], $_SESSION['last_name'])) {
?>
<html lang="en">
  <head>
    <title>Employee Home</title>
    <?php include_once "_css.php"?>
  </head>
  <body>
    <?php include_once "_header.php"?>
  <?php } ?>
  <div class="container">
    <div class="request-button-div">
      <a class="submit-request-button black_btn btn btn-primary" href="submit-request.php">Submit Request</a>
    </div>
    <?php
    $listRequests->displayRequests();
    ?>
  </div>
  <?php include_once "_footer.php"; ?>
  </body>
  <?php include_once "_js.php"; ?>
</html>
