<?php
include(__DIR__ . "/config.php");
include(__DIR__ . "/private/parsers.php");
include(__DIR__ . "/private/queries.php");
include(__DIR__ . "/private/parsers.php");

// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $res = get_user_by_email($db, $param_email);
  $email = $res[0];
  $email_err = $res[1];

  // Validate password
  $result = validate_password(parse_input($_POST["password"]));
  $password = $result[0];
  $password_err = $result[1];

  // Validate confirm password
  $confirm_password_err = validate_confirm_password(parse_input($_POST["confirm_password"]), $password);

  // Check input errors before inserting in database
  if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
    register_user($db, $param_email, $param_password, $param_fk, $param_name, $email, $password);
  }

  // Close connection
  $db->close();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link href="css/styles.css" rel="stylesheet" />
</head>

<body class="page">
  <form id="form" class="form" method="POST">
    <div id="container" class="container">
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="email">Email</label>
          <input type="text" class="input" name="email" id="email" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="name">Name</label>
          <input type="text" class="input" name="name" id="name" />
        </div>
      </div>
      <div class=" fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="fn">FN</label>
          <input type="text" class="input" name="fn" id="fn" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="password">Password</label>
          <input type="password" class="input" name="password" id="password" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="confirm-password">Confirm password</label>
          <input type="password" class="input" name="confirm_password" id="confirm-password" />
        </div>
      </div>
    </div>
    <div class="buttons-and-text">
      <button type="submit" name="submit" value="submit" class="btn">Register</button>
      <p>Already have an account? <a href="login">Login</a></p>
    </div>
  </form>
  <span>
  </span>
</body>

</html>