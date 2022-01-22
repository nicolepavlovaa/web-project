<?php
include("config.php");
include("include/authenticate.php");
include("include/parsers.php");
include("include/generate_components.php");
include("include/queries.php");
include("include/form_generator_helpers.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  $form_id = $_GET['form_id'];
  $form_json = '';
  $form_txt = '';
  $files = scandir('generated/');

  foreach ($files as $file) {
    if ($file == "result_$form_id.txt") {
      $form_txt = $file;
    } elseif ($file == "result_$form_id.json") {
      $form_json = $file;
    }
  }

  $title = "";
  $description = "";

  $content = file_get_contents("generated/$form_txt");
  $rows = explode("\n", $content);

  $info = file_get_contents("generated/$form_json");
  $json_content = json_decode($info, true);
  $display_edit_button = false;

  foreach ($json_content as $json_key => $value) {
    if ($json_key == "form_title") {
      $title = $value;
    } elseif ($json_key == "form_description") {
      $description = $value;
    } elseif ($json_key == "creator" && $value == get_user_email($token)) {
      $display_edit_button = true;
    }
  }
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
  <script type="text/javascript" srx="js/form.js"></script>
</head>

<body class="page">
  <a href="forms" class="logo">
    <img src="assets/logo.png" />
  </a>
  <?php
  echo
  "<form id='form' class='form' method='POST'>
    <button id='delete-form' onclick='deleteForm()' class='btn'>Delete form</button>
    <div class='description-wrapper'>
        <div class='input-title'>
            <p class='form-question'>$title</p>
        </div>
        <div>
            <p class='form-question'>$description</p>
        </div>
    </div>
    <div id='containter'>";
  $res = split_inputs($rows);
  echo $res;
  echo '</div>
  <div class="buttons-and-text">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
    </div>
</form>';
  ?>
</body>

</html>