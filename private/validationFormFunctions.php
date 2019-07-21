<?php

function validateRequiredFields($value) {
  $error = false;
  if(!isset($value) || empty($value) || $value == '') {
    $error = true;
  }
  return error;
}

?>
