<?php

class SubmitRequest {
  private $id;
  private $vacationStart;
  private $vacationEnd;
  private $reason;


  public function submitRequest() {
    global $db;
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [];
      $id = isset($_REQUEST['id']) ? (int)($_REQUEST['id']) : null;
      $vacationStart = isset($_POST['vacation_start']) ? $_POST['vacation_start'] : null;
      $vacationEnd = isset($_POST['vacation_end']) ? $_POST['vacation_end'] : null;
      $reason = isset($_POST['reason']) ? $_POST['reason'] : null;
      $apply_by = isset($_SESSION['id']) ? $_SESSION['id'] : null;
      $first_name = isset($_SESSION['first_name']) ? trim($_SESSION['first_name']) : '';
      $email = isset($_SESSION['email']) ? trim($_SESSION['email']) : '';
      $last_name = isset($_SESSION['last_name']) ? trim($_SESSION['last_name']) : '';
      $approved_hash = MD5(rand(1000,5000));
      $rejected_hash = MD5(rand(1000,5000));
      $approved = isset($_POST['approved']) ? 1 : 0;
      $rejected = isset($_POST['rejected']) ? 1 : 0;
      $data['vacation_start'] = $vacationStart;
      $data['vacation_end'] = $vacationEnd;
      $data['reason'] = $reason;
      $data['apply_by'] = $apply_by;
      $data['approved_hash'] = $approved_hash;
      $data['rejected_hash'] = $rejected_hash;
      $data['approved'] = $approved;
      $data['rejected'] = $rejected;

      $table = 'requests';
      $data = [
        'vacation_start' => $vacationStart,
        'vacation_end' => $vacationEnd,
        'reason' => $reason,
        'apply_by' => $apply_by,
        'approved_hash' => $approved_hash,
        'rejected_hash' => $rejected_hash,
        'approved' => $approved,
        'rejected' => $rejected
      ];

      $sql =
            "
            SELECT vacation_start, vacation_end
                FROM requests
            WHERE apply_by = '$apply_by' AND vacation_start = '$vacationStart' AND vacation_end = '$vacationEnd'
            ";
      $result = $db->select($sql);
      if($result->num_rows > 0) {
        echo "Cannot send a request for the same period";
      } else {
        $request = $db->insert($table, $data);
        if($request) {
          $to = 'managementsystem24@gmail.com';
          $title = "New request from {$first_name } {$last_name}";
          $header = "From: no-reply@weblanguages.org\r\n";
          $header .= "Content-Type: text/html; charset=UTF-8\r\n";
          $message = "
          <html>
          <head>
          <title>New request from {$first_name } {$last_name}</title>
          </head>
          <body>
          Dear supervisor,<br /> employee {$first_name} {$last_name} requested for some time off, starting on
          {$vacationStart} and ending on {$vacationEnd}, starting the reason {$reason}. <br />
          Click on one of the below links to approve or reject the application:<br />Please click this link to approve
          request: http:management-system.weblanguages.org/approve.php?email={$email}&approved_hash={$approved_hash}
          <br />or click this link to reject the request:
          http:management-system.weblanguages.org/reject.php?email={$email}&rejected_hash={$rejected_hash}
          </body>
          </html>
          ";
          mail($to, $title, $message, $header);
        }
      }
      header("location:/employee-home.php");
    }
  }

  private function emptyRequests() {
    $emptyRequests = [
      'vacation_start' => null,
      'vacation_end' => null,
      'reason' => null,
      'apply_by' => null,
      'approved_hash' => null,
      'rejected_hash' => null,
      'approved' => null,
      'rejected' => null
    ];
    return $emptyRequests;
  }

  public function allRequests($id) {
    global $db;
    $sql =
          "
          SELECT * FROM requests WHERE id = $id
          ";
    $result = $db->select($sql);
    if(count($result) > 0) {
      $result = $result[0];
    } else {
      $result = $this->emptyRequests();
    }
    return $result;
  }

  public function attemptSubmitRequest() {
    //$sent = $this->attemptSend();
    $attemptSubmit = $this->submitRequest();
    /*if(isset($_REQUEST['id']) && intval($_REQUEST['id'] > 0)) $rec = $this->allRequests($_REQUEST['id']);
    elseif(isset($attemptSubmit) && !is_null($attemptSubmit['id']))  $rec = $this->allRequests($attemptSubmit['id']);
    else $rec = $this->emptyRequests();*/
    if($attemptSubmit) {
      ?>
      <p>Thank you, your request has been submitted!!</p>
      <?php
    } else {
      ?>
      <html>
        <head>
          <title>Submit Request</title>
          <?php include_once "_css.php"?>
        </head>
        <body>
          <div class="container">
            <div class="main_cont thank_you">

                <div class="col-xs-12 col-sm-12 text-center section_title page_title">SUBMIT REQUEST</div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center sign_up_form">
                    <form action="submit-request.php" class="sign_up" id="submitRequest" method="post">
                      <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>" />
                        <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                            <label for="" class="checkout-sign-in-label">VACATION START - <div class="required-text">required field</div>
                            </label>
                            <input id="clientName" name="vacation_start" type="date" class="checkout-input required lettersOnly">
                            <div class="error_msg"></div>
                        </div>
                        <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                            <label for="" class="checkout-sign-in-label">VACATION END - <div class="required-text">required field</div>
                            </label>
                            <input id="clientSurname" name="vacation_end" type="date" class="checkout-input required lettersOnly">
                            <div class="error_msg"></div>
                        </div>
                        <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                            <label for="" class="checkout-sign-in-label">REASON - <div class="required-text">required field</div>
                            </label>
                            <textarea id="email" name="reason" class="checkout-textarea required validateEmail"></textarea>
                            <div class="error_msg"></div>
                        </div>
                        <div class="col-xs-12 col-md-12 text-center">
                            <button class="black_btn" type="submit">SUBMIT REQUEST</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="signUpModal" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel">
            <div class="modal-dialog myModalWidth" role="document">
                <div class="modal-content myModalContent">
                    <div class="modal-header myModalHeader">
                        <button type="button" class="close myClose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">
                          &times;</span></button>
                        <h4 class="modal-title" id="signUpModalLabel"></h4>
                    </div>
                    <div class="modal-body myModalBody">
                        <p></p>
                    </div>
                    <div class="modal_ok">
                        <button type="button" class="btn btn-primary black_btn">OK</button>
                    </div>
                </div>
            </div>
        </div>
      </body>
    <?php include_once "_js.php" ?>
  </html>
      <?php
    }
  }

  public function listRequests() {
    global $db;
    $sql =
          "
          SELECT R.id, R.vacation_start, R.vacation_end, R.reason
          FROM requests AS R ORDER BY id
          ";
    $requests = $db->select($sql);
    if(count($requests) > 0) {
      ?>
      <div class="list-requests">
        <ul>
        <?php foreach($requests as $request) { ?>
          <li>ID: <?php echo $request['id']; ?></li>
          <li>Date from(vacation start): <?php echo $request['vacation_start']; ?></li>
          <li>Date to(vacation end): <?php echo $request['vacation_end']; ?></li>
          <li>Reason: <?php echo $request['reason']; ?></li>
        <?php }  ?>
        </ul>
      </div>
      <?php
    }
  }
}

 ?>
