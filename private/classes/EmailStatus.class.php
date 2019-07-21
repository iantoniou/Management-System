<?php
require_once 'private/classes/Database.class.php' ;
$db = new Database();

class EmailStatus {

  public function approvedEmail() {
    global $db;
      if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['approved_hash']) && !empty($_GET['approved_hash'])) {
        $approved_hash = trim($_GET['approved_hash']);
        $email = isset($_SESSION['email']) ? trim($_SESSION['email']) : null;
        $first_name = isset($_SESSION['first_name']) ? trim($_SESSION['first_name']) : null;
        $last_name = isset($_SESSION['last_name']) ? trim($_SESSION['last_name']) : null;
        $id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $sql =
                "
                SELECT R.approved_hash, R.approved, U.id, U.email, R.apply_by
                  FROM requests AS R
                INNER JOIN users AS U ON R.apply_by = U.id
                WHERE U.id = '$id' AND U.email = '$email' AND R.approved_hash = '$approved_hash' AND approved = '0'
                ";
        $search = $db->select($sql);
        $match = $search->num_rows;
        if($match > 0) {
          $query = $db->connection->query(
            "
            UPDATE requests
            SET approved = '1'
            WHERE apply_by = '$id' AND approved_hash = '$approved_hash' AND approved = '0'
            "
          );
          echo "The request has been approved. An email has been sent to {$first_name} {$last_name} for this reason.";
          if($query) {
            $to = $_GET['email'];
            $title = "Request Approved";
            $header = "From: iantoniou6@gmail.com\r\n";
            $header .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message = "
            <html>
              <head>
                <title>Request Approved</title>
              </head>
              <body>
              Dear {$first_name} {$last_name},<br /> Your request has been approved.
              </body>
            </html>
            ";
            mail($to, $title, $message, $header);
          } else {
            echo "Problem approving the request";
          }

        } else {
          echo "The url is either invalid or you already have approved this request";
        }
      } else {
        echo "Invalid approach, please use the link that has been send to your email";
      }
  }

  public function rejectedEmail() {
    global $db;
      if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['rejected_hash']) && !empty($_GET['rejected_hash'])) {
        $rejected_hash = trim($_GET['rejected_hash']);
        $email = isset($_SESSION['email']) ? trim($_SESSION['email']) : null;
        $first_name = isset($_SESSION['first_name']) ? trim($_SESSION['first_name']) : null;
        $last_name = isset($_SESSION['last_name']) ? trim($_SESSION['last_name']) : null;
        $id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $sql =
                "
                SELECT R.rejected_hash, R.rejected, U.id, U.email, R.apply_by
                  FROM requests AS R
                INNER JOIN users AS U ON R.apply_by = U.id
                WHERE U.id = '$id' AND U.email = '$email' AND R.rejected_hash = '$rejected_hash' AND rejected = '0'
                ";
        $search = $db->select($sql);
        $match = $search->num_rows;
        if($match > 0) {
          $query = $db->connection->query(
            "
            UPDATE requests
            SET rejected = '1'
            WHERE apply_by = '$id' AND rejected_hash = '$rejected_hash' AND rejected = '0'
            "
          );
          echo "The request has been rejected. An email has been sent to {$first_name} {$last_name} for this reason.";
          if($query) {
            $to = $_GET['email'];
            $title = "Request Rejected";
            $header = "From: iantoniou6@gmail.com\r\n";
            $header .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message = "
            <html>
              <head>
                <title>Request Rejected</title>
              </head>
              <body>
              Dear {$first_name} {$last_name},<br /> Your request has been rejected.
              </body>
            </html>
            ";
            mail($to, $title, $message, $header);
          } else {
            echo "Proble rejecting the request";
          }
        } else {
          echo "The url is either invalid or you already have rejected this request";
        }
      } else {
        echo "Invalid approach, please use the link that has been send to your email";
      }
  }
}

?>
