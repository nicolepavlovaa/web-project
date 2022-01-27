<?php
include(__DIR__ . "/config.php");
include(__DIR__ . "/private/authenticate.php");
include(__DIR__ . "/private/form_generator_helpers.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  $content = load_form_contents($token);
  $title = $content[0];
  $description = $content[1];
  $has_password = $content[4];
  $password = $content[5];
} else {
  header("location: login");
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Form</title>
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="js/form.js"></script>
  <script type="text/javascript" src="js/helpers.js"></script>
</head>

<body class="page">
  <div id="open-user-menu" class="icon-wrapper" onclick="openModal()">
    <i class="fa-user-circle icon"></i>
  </div>
  <a href="index" class="logo">
    <img src="assets/logo.png" />
  </a>
  <?php
  echo
  "<form id='form' class='form' method='POST'>
    <div class='description-wrapper'>
        <div class='input-title'>
            <p class='form-question'>$title</p>
        </div>
        <div>
            <p class='form-question'>$description</p>
        </div>
    </div>
    <div id='containter'>";
  echo '<div class="fieldset">
  <div class="form-group">
    <label class="form__label" htmlFor="password">Password</label>
    <input type="password" class="input" name="password" id="password" />
  </div>
</div>
  ';
  echo '</div>
  <div class="buttons-and-text">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
    </div>
</form>';
  ?>
  <div id="background-overlay" class="overlay"></div>
  <div id="modal" class="modal" onclick="logout()">
    <div class="modal-content">
      <p>Logout</p>
    </div>
  </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $pass = $_POST['password'];

  if (($has_password && $pass == $password) || !$has_password) {
    $form_id = $_GET['form_id'];
    header("location: form?form_id=$form_id");
  }
}
