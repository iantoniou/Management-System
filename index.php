<?php
session_start();
require_once 'private/classes/Database.class.php';
$db = new Database();
require_once 'private/classes/Users.class.php';
$users = new Users();
$users->attemptLogin();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
    <?php include_once "_css.php"?>
  </head>
  <body>
    <div class="container">
      <div class="main_cont thank_you">

          <div class="col-xs-12 col-sm-12 text-center section_title page_title">USER LOGIN</div>

          <div class="col-xs-12 col-sm-12 col-md-12 text-center sign_up_form">
              <form action="" class="sign_in" id="sign_in">
                  <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                      <label for="" class="checkout-sign-in-label">E-MAIL - <div class="required-text">required field</div>
                      </label>
                      <input type="text" class="checkout-input required" name="email">
                      <div class="error_msg"></div>
                  </div>
                  <div class="col-xs-12 col-md-12 checkout-sign-up-label">
                      <label for="" class="checkout-sign-in-label">PASSWORD - <div class="required-text">required field</div>
                      </label>
                      <input type="password" class="checkout-input required" name="password">
                      <div class="error_msg"></div>
                  </div>
                  <div class="col-xs-12 col-md-12 text-center">
                      <button class="black_btn" type="submit">LOGIN</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <div class="modal fade" id="signInModal" tabindex="-1" role="dialog" aria-labelledby="signInModalLabel">
      <div class="modal-dialog myModalWidth" role="document">
          <div class="modal-content myModalContent">
              <div class="modal-header myModalHeader">
                  <button type="button" class="close myClose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">
                    &times;</span></button>
                  <h4 class="modal-title" id="signInModalLabel"></h4>
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
  <?php include_once "_footer.php" ?>
  </body>
  <?php include_once "_js.php" ?>
</html>
