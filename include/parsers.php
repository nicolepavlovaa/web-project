<?php
function parse_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validate_password($password)
{
  $res = "";
  $error = "";
  if (empty(parse_input($password))) {
    $error = "Please enter a password.";
  } elseif (strlen(parse_input($password)) < 6) {
    $error = "Password must have atleast 6 characters.";
  } else {
    $res = parse_input($password);
  }
  return [$res, $error];
}

function validate_confirm_password($confirm_password, $password)
{
  $confirm_password_err = "";
  if (empty(parse_input($confirm_password))) {
    $confirm_password_err = "Please confirm password.";
  } else {
    $confirm_password = parse_input($confirm_password);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }
  return $confirm_password_err;
}
