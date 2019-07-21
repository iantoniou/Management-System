<?php
require_once 'private/config.php';

class Database extends mysqli {
  public $connection;

  public function __construct() {
    $this->open_connection();
  }

  public function open_connection() {
    $this->connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $this->confirm_open_connection($this->connection);
    return $this->connection;
  }

  public function confirm_open_connection($connection) {
    if($this->connection->connect_errno) {
      $msg = "Database connection failed: ";
      $msg .= $this->connection->connect_error;
      $msg .= " (" . $this->connection->connect_errno . ")";
      exit($msg);
    }
  }

  public function close_connection() {
    if(isset($this->connection)) {
      $this->connection->close();
    }
  }

  public function select($sql) {
    $result = $this->connection->query($sql) or die($this->connection->error.__LINE__);
    if($result->num_rows > 0) {
      return $result;
    } else return false;
  }

  public function insert($table, $data) {
    $string =
            "
            REPLACE INTO $table (
            ";
    $string .= implode(",", array_keys($data)) . ') VALUES (';
    $string .= "'" . implode("','", array_values($data)) . "')";
    $result = $this->connection->query($string) or die($this->connection->error.__LINE__);
    if($result) {
      $this->id = $this->connection->insert_id;
    }
    return $result;
  }

  public function update($table, &$fields, $where) {
    $sql =
          "
          UPDATE $table SET
          ";
    foreach($fields as $key => $value) {
      $fields[$key] = " `$key` = '".$this->connection->real_escape_string($value)."' ";
    }
    $sql .= implode(" ,", array_values($fields))." WHERE ".$where.";";
    $fields = [];
  }
}

?>
