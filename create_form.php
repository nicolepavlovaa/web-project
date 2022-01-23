<?php
include(__DIR__ . "/config.php");
include(__DIR__ . "/private/authenticate.php");
include(__DIR__ . "/private/parsers.php");
include(__DIR__ . "/private/queries.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = get_user_email($token);

    $title = parse_input($_POST['title']);
    $description = parse_input($_POST['description']);

    $questions = $_POST['question'];
    $answers = $_POST['answers'];
    $types = $_POST['checkbox'];

    create_form($db, $questions, $types, $answers, $title, $description, $email);
  }
} else {
  header("location: login");
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Project</title>
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="js/create_form.js"></script>
  <script type="text/javascript" src="js/helpers.js"></script>
</head>

<body class="page">
  <div id="open-user-menu" class="icon-wrapper" onclick="openModal()">
    <i class="fa-user-circle icon"></i>
  </div>
  <a href="index" class="logo">
    <img src="assets/logo.png" />
  </a>
  <form id="form" class="form" method="POST">
    <div>
      <fieldset class="description-wrapper">
        <div class="input-title">
          <label class="form__label" htmlFor="title">Title</label>
          <input type="text" class="input" name="title" id="title" />
        </div>
        <div>
          <label class="form__label" htmlFor="description">Description</label>
          <input type="text" class="input" name="description" id="description" />
        </div>
      </fieldset>
      <div id="container">&nbsp;</div>
    </div>
    <div class="buttons-and-text">
      <input type="button" value="Add question" id="add" class="btn">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
  </form>
  <div id="background-overlay" class="overlay"></div>
  <div id="modal" class="modal" onclick="logout()">
    <div class="modal-content">
      <p>Logout</p>
    </div>
  </div>
</body>

</html>