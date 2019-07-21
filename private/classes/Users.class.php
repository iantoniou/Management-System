<?php
require_once 'Database.class.php';
$db = new Database();

class Users {
  private $id;
  private $first_name;
  private $last_name;
  private $email;
  private $password;
  private $role;

  public function __construct($args=[]) {
    $this->first_name = $args['first_name'] ?? '';
    $this->last_name = $args['last_name'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->role = $args['role'] ?? '';
  }

  public function attemptLogin() {
    global $db;
    if(isset($_POST['email'], $_POST['password'])) {
      $email = trim($_POST['email']);
      $password = trim($_POST['password']);
      $sql =
            "
            SELECT id, first_name, last_name, email, role
              FROM users
            WHERE email = '$email' AND password = MD5('$password') AND (role = 'Admin' OR role = 'Employee')
            LIMIT 1
            ";
      $result = $db->select($sql);
      if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $this->id = intval($user['id']);
        $this->first_name = stripslashes($user['first_name']);
        $this->last_name = stripslashes($user['last_name']);
        $this->email = stripslashes($user['email']);
        $this->role = stripslashes($user['role']);
        $_SESSION['id'] = $this->id;
        $_SESSION['email'] = $this->email;
        $_SESSION['first_name'] = $this->first_name;
        $_SESSION['last_name'] = $this->last_name;
        $_SESSION['role'] = $this->role;
        if($this->role == 'Admin') {
          $response['loggedInAdmin'] = 'You have successfully logged in.';
        } elseif($this->role == 'Employee') {
          $response['loggedInEmployee'] = 'You have successfully logged in.';
        } elseif($this->role != 'Admin' || $this->role != 'Employee') {
            $response['exist'] = 'The email or password is wrong<br>or they are both wrong';
        }
        echo json_encode($response);
        exit;
      }
    } else return false;
  }

  public function logout() {
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['first_name']);
    unset($_SESSION['last_name']);
    unset($_SESSION['email']);
    unset($this->id);
    unset($this->first_name);
    unset($this->last_name);
    unset($this->email);
    return true;
  }

  public function createUser() {
    global $db;
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [];
      $id = isset($_REQUEST['id']) ? (int)($_REQUEST['id']) : null;
      $first_name = isset($_POST['first_name']) && $_POST['first_name'] != '' ? $_POST['first_name'] : null;
      $last_name = isset($_POST['last_name']) && $_POST['last_name'] != '' ? $_POST['last_name'] : null;
      $email = isset($_POST['email']) && $_POST['email'] != '' ? $_POST['email'] : null;
      $password = isset($_POST['password']) && $_POST['password'] != '' ? $_POST['password'] : null;
      $role = isset($_POST['role']) && $_POST['role'] != '' ? $_POST['role'] : null;
      $data['first_name'] = $first_name;
      $data['last_name'] = $last_name;
      $data['email'] = $email;
      $data['password'] = $password;
      $data['role'] = $role;

      $table = 'users';
      $data = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => MD5($password),
        'role' => $role
      ];

      $admin = $db->insert($table, $data);
      if($admin) {
        $response['created'] = 'The user has successfully crated';
      } else {
        $response['exist'] = 'There is already user with this email</br>Try to create a user with different email.';
      }
      echo json_encode($response);
      exit();
    }
  }

  private function emptyUser() {
    $emptyUser = [
      'id' => null,
      'first_name' => null,
      'last_name' => null,
      'email' => null,
      'password' => null,
      'role' => null
    ];
    return $emptyUser;
  }

  public function allUsers($id) {
    global $db;
    $sql =
    "
    SELECT * FROM users WHERE id = $id
    ";
    $result = $db->select($sql);
    if(count($result) > 0) {
      $result = $result[0];
    } else {
      $result = $this->emptyUser();
    }
    return $result;
  }

  public function attemptCreateUser() {
    $attemptCreateUser = $this->createUser();
    if(isset($_REQUEST['id']) && intval($_REQUEST['id']) > 0) $record = $this->allUsers($_REQUEST['id']);
    elseif(isset($attemptCreateUser) && !is_null($attemptCreateUser['id'])) $record = $this->allUsers($attemptCreateUser['id']);
    else $record = $this->emptyUser();
    ?>
    <html lang="en">
      <head>
        <title>Create User</title>
        <?php include_once "_css.php"?>
      </head>
      <body>
        <div class="container">
          <div class="main_cont thank_you">

              <div class="col-xs-12 col-sm-12 text-center section_title page_title">CREATE USER</div>

              <div class="col-xs-12 col-sm-12 col-md-12 text-center sign_up_form">
                  <form action="create-user.php" class="sign_up" id="register">
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">FIRST NAME - <div class="required-text">required field</div>
                          </label>
                          <input id="clientName" name="first_name" type="text" value="<?php echo $record['first_name']; ?>"
                          class="checkout-input required lettersOnly">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">LAST NAME - <div class="required-text">required field</div>
                          </label>
                          <input id="clientSurname" name="last_name" type="text" value="<?php echo $record['last_name']; ?>"
                          class="checkout-input required lettersOnly">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">E-MAIL - <div class="required-text">required field</div>
                          </label>
                          <input id="email" name="email" type="text" value="<?php echo $record['email']; ?>"
                          class="checkout-input required validateEmail">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">PASSWORD - <div class="required-text">required field</div>
                          </label>
                          <input id="pass" name="password" type="password" value="<?php echo $record['password']; ?>"
                          class="checkout-input required">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                        <label for="" class="checkout-sign-in-label">USER TYPE - <div class="required-text">required field</div></label>
                          <label for="" class="checkout-sign-in-label">
                            <input type="radio" name="role" id="role1" value="Admin" checked />
                            ADMIN
                          </label>
                          <label for="" class="checkout-sign-in-label">
                            <input type="radio" name="role" id="role2" value="Employee" />
                            EMPLOYEE
                          </label>
                          <div class="error_msg"></div>
                      </div>
                      <!--<div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">ΕΠΑΝΑΛΗΨΗ ΚΩΔΙΚΟΥ - <div class="required-text">
                            υποχρεωτικό πεδίο</div></label>
                          <input id="Cpass" name="Cpass" type="password" class="checkout-input required">
                          <div class="checked"></div>
                          <div class="error_msg"></div>
                          <div class="success_msg text-success"></div>
                      </div>-->
                      <div class="col-xs-12 col-md-12 text-center">
                          <button class="black_btn" type="submit">CREATE USER</button>
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

  public function listUsers() {
    global $db;
    $i = 1;
    $sql =
      "
      SELECT * FROM users
      ";
      $users = $db->select($sql);
      $count = $users->num_rows;
      if(count($count) > 0) {
        while($row = $users->fetch_array()) {
        ?>
        <div class="list-users">
          <ul>
            <li>ID<br /><?php echo $i; ?></li>
            <li>First Name<br /><?php echo $row['first_name']; ?></li>
            <li>Last Name<br /><?php echo $row['last_name']; ?></li>
            <li>Email<br /><?php echo $row['email']; ?></li>
            <li>Role<br /><?php echo $row['role']; ?></li>
            <li>Action<br /><a href="update-user.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="delete-user.php?id=<?php echo $row['id']; ?>">Delete</a></li>
          </ul>
        </div>
        <?php
        $i++;
        }
      } else {
        echo "No records found!";
      }
    }

  public function prePopulatedForm() {
    $this->updateUser();
    global $db;
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql =
          "
          SELECT * FROM users WHERE id = '$id'
          ";
    $result = $db->select($sql);
    $data = $result->fetch_array();
    ?>

    <html lang="en">
      <head>
        <title>Update User</title>
        <?php include_once "_css.php"?>
      </head>
      <body>
        <div class="container">
          <div class="main_cont thank_you">

              <div class="col-xs-12 col-sm-12 text-center section_title page_title">UPDATE USER</div>

              <div class="col-xs-12 col-sm-12 col-md-12 text-center sign_up_form">
                  <form action="update-user.php" class="sign_up" id="prepopulated_form" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">FIRST NAME - <div class="required-text">required field</div>
                          </label>
                          <input id="clientName" name="first_name" type="text" value="<?php echo $data['first_name']; ?>"
                          class="checkout-input required lettersOnly">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">LAST NAME - <div class="required-text">required field</div>
                          </label>
                          <input id="clientSurname" name="last_name" type="text" value="<?php echo $data['last_name']; ?>"
                          class="checkout-input required lettersOnly">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">E-MAIL - <div class="required-text">required field</div>
                          </label>
                          <input id="email" name="email" type="text"  value="<?php echo $data['email']; ?>"
                          class="checkout-input required validateEmail">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">PASSWORD - <div class="required-text">required field</div>
                          </label>
                          <input id="pass" name="password" type="password" class="checkout-input required">
                          <div class="error_msg"></div>
                      </div>
                      <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                        <labeL for="" class="checkout-sign-in-label">USER TYPE - <div class="required-text">required field</div></label>
                          <label for="" class="checkout-sign-in-label">ADMIN
                            <input type="radio" name="role" id="role1" value="Admin" <?php if($data['role'] == 'Admin')
                            { echo "checked"; } ?> />
                          </label>
                          <label for="" class="checkout-sign-in-label">EMPLOYEE
                            <input type="radio" name="role" id="role2" value="Employee" <?php if($data['role'] == 'Employee')
                            { echo "checked"; } ?> />
                          </label>
                          <div class="error_msg"></div>
                      </div>
                      <!--<div class="col-xs-12 col-md-12 checkout-sign-up-label">
                          <label for="" class="checkout-sign-in-label">ΕΠΑΝΑΛΗΨΗ ΚΩΔΙΚΟΥ - <div class="required-text">
                            υποχρεωτικό πεδίο</div></label>
                          <input id="Cpass" name="Cpass" type="password" class="checkout-input required">
                          <div class="checked"></div>
                          <div class="error_msg"></div>
                          <div class="success_msg text-success"></div>
                      </div>-->
                      <div class="col-xs-12 col-md-12 text-center">
                          <button class="black_btn" type="submit">UPDATE USER</button>
                      </div>
                    <?php //} ?>
                  </form>
              </div>
          </div>
      </div>

      <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="signUpModalLabel">
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

  public function updateUser() {
    global $db;
    //$this->prePopulatedForm();
    if(isset($_REQUEST['email'])) {
      $id = $_POST['id'];
      $first_name = $_POST['first_name'];
      $last_name = $_POST['last_name'];
      $email = $_POST['email'];
      $password = MD5($_POST['password']);
      $role = $_POST['role'];

      if($password == '') {
        $sql =
        "
        UPDATE users
        SET first_name = '$first_name', last_name = '$last_name', email = '$email', role = '$role'
        WHERE id = '$id'
        ";
      } else {
        $sql =
        "
        UPDATE users
        SET first_name = '$first_name', last_name = '$last_name', email = '$email', password = '$password', role = '$role'
        WHERE id = '$id'
        ";
      }

      //$admin = $db->update($table, $data, $where);
      $result = $db->connection->query($sql);
      if($result) {
        //echo "Data updated";
        //$response['updated'] = 'Έχετε εγγραφεί επιτυχώς';
        header("location:admin-home.php");
      } else {
        echo "Data not upadated";
        //$response['notUpdated'] = 'Υπάρχει είδη πελάτης με αυτό το Email.</br>Μπορείτε να δοκιμάσετε να κάνετε εγγραφή με
        //διαφορετικό Email.';
      }
      //echo json_encode($response);
      //exit();
    }
  }

  public function deleteUser() {
    global $db;
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql =
        "
        DELETE FROM users WHERE id = '$id'
        ";
    $result = $db->connection->query($sql);
    if($result) {
      header("location: admin-home.php");
    }
  }
}

?>
