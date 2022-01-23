<?php
include(__DIR__ . "/config.php");
include(__DIR__ . "/private/authenticate.php");
include(__DIR__ . "/private/queries.php");
include(__DIR__ . "/private/parsers.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $myemail = parse_input($_POST["email"]);
  $mypassword = parse_input($_POST["password"]);

  $result = login_user($db, $myemail, $mypassword);
  $password = $result[0]["password"];

  // If result matched $myusername and $mypassword, table row must be 1 row
  if (password_verify($mypassword, $password)) {
    $_SESSION['login_user'] = $myemail;

    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
    $expires = time() + 3600;
    $payload = array('email' => $myemail, 'exp' => ($expires));

    $jwt = generate_jwt($headers, $payload);
    setcookie('auth', $jwt, $expires, '/');

    header("location: index");
  } else {
    $error = "Your Login Name or Password is invalid";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Login</title>
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
          <label class="form__label" htmlFor="password">Password</label>
          <input type="password" class="input" name="password" id="password" />
        </div>
      </div>
      <div class="buttons-and-text">
        <button type="submit" name="submit" value="submit" class="btn">Login</button>
        <p>Not yet a member? <a href="register.php">Register</a></p>
      </div>
  </form>
  <span>
  </span>
</body>

</html>