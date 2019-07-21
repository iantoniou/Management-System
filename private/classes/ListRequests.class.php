<?php
require_once 'Database.class.php';
$db = new Database();
require_once 'SubmitRequest.class.php';
$submitRequest = new SubmitRequest();

class ListRequests {

  public function displayRequests() {
    global $db;
    $id = $_SESSION['id'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    ?>
    <h1 class="list-requests-h1"><?php echo "{$first_name}'s {$last_name} approved requests" ?></h1>
    <?php
    $i = 1;
    $sql =
          "
          SELECT * FROM requests WHERE apply_by = '$id' AND approved = '1'
          ";
    $result = $db->select($sql);
    if(!$result) {
      echo "Nothing found";
    } else {

      if($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
          ?>
          <div class="list-requests">
            <ul>
              <li>ID<br /><?php echo $i; ?></li>
              <li>Date From<br /><?php echo $row['vacation_start']; ?></li>
              <li>Date To<br /><?php echo $row['vacation_end']; ?></li>
              <li>Reason<br /><?php echo $row['reason']; ?></li>
            </ul>
          </div>
          <?php
          $i++;
        }
      }
    }
  }

}

?>
