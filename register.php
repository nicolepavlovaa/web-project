<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link href="./styles.css" rel="stylesheet" />
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
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </form>
  <span>
  </span>
</body>

</html>

<?php

include("config.php");
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Prepare a select statement
  $sql = "SELECT email FROM users WHERE email = ?";

  if ($stmt = $db->prepare($sql)) {

    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $param_email);

    // Set parameters
    $param_email = trim($_POST["email"]);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
      // store result
      $stmt->store_result();

      if ($stmt->num_rows == 1) {
        $email_err = "This email is already taken.";
      } else {
        $email = trim($_POST["email"]);
      }
    } else {
      echo "Oops! Something went wrong. Please try again later.";
    }


    // Close statement
    $stmt->close();
  } else {
    echo '    stmt duha pak     ';
    echo $db->error;
  }


  // Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Password must have atleast 6 characters.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validate confirm password
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Please confirm password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }

  // Check input errors before inserting in database
  echo 'before if';
  if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
    echo 'in if';
    // Prepare an insert statement
    $sql = "INSERT INTO users (email, password, fk, name) VALUES (?, ?, ?, ?)";

    if ($stmt = $db->prepare($sql)) {
      echo "stmt duha";
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("ssds", $param_email, $param_password, $param_fk, $param_name);

      // Set parameters
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
      $param_fk = trim($_POST["fn"]);
      $param_name = trim($_POST["name"]);
      echo $param_email, $param_fk, $param_name, $param_password;

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // Redirect to login page
        header("location: login.php");
      } else {
        echo "Oops! Something went wrong. Please try again later.";
        echo $stmt->error;
      }

      // Close statement
      $stmt->close();
    }
  }

  // Close connection
  $db->close();
}
?>