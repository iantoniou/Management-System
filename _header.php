<?php
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
if(isset($_SESSION['email'], $_SESSION['first_name'], $_SESSION['last_name'])) {
?>
<?php include_once "_css.php"?>
<header>
  <div class="log_container">
    <div class="top_bar">
      Welcome
      <span class="client_actions"><?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name']; ?></span>
      | <a class="client_actions" href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</header>
<?php } ?>
