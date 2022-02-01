<?php
include(__DIR__ . "/config.php");
include(__DIR__ . "/private/authenticate.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  $files = scandir('generated/');
  $pairs = array();

  foreach ($files as $file) {
    if ($file != "." && $file != "..") {
      $key = trim(explode("_", $file)[1], ".json");
      $key = trim($key, ".txt");

      if (array_key_exists($key, $pairs)) {
        array_push($pairs[$key], $file);
      } else {
        $pairs[$key] = [$file];
      }
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
  <title>Home</title>
  <link href="css/home.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="js/index.js"></script>
  <script type="text/javascript" src="js/helpers.js"></script>
</head>

<body class="page">
  <div id="open-user-menu" class="icon-wrapper" onclick="openModal()">
    <i class="fa-user-circle icon"></i>
  </div>
  <main>
    <a href="index" class="logo">
      <img src="assets/logo.png" />
    </a>
    <a href="generate_form" class="link">
      <button type="submit" class="btn">Create new form</button>
    </a>
    <ol id="forms" class="gradient-list">
      <?php
      foreach ($pairs as $key => $value) {
        if ($key != '') {
          $json_file = '';
          foreach ($value as $filename) {
            if (explode(".", $filename)[1] == "json") {
              $json_file = $filename;
            }
          }

          $title = "";
          $description = "";
          $creator = "";
          $last_edit = "";
          $function = "openForm";

          $file = file_get_contents("generated/$json_file");
          $json_content = json_decode($file, true);

          foreach ($json_content as $json_key => $value) {
            if ($json_key == "form_title") {
              $title = $value;
            } elseif ($json_key == "password") {
              $function = "openLoginForm";
            } elseif ($json_key == "form_description") {
              $description = $value;
            } elseif ($json_key == "creator") {
              $creator = $value;
            } elseif ($json_key == "last_edit") {
              $last_edit = $value;
            }
          }

          $edit_button = trim($creator) == get_user_email($token) ? "<button class='btn-small' onClick='editForm($key)'>Edit</button>" : '';

          echo "<li id=$key>
                <div onclick='$function($key)'>
                  <p class='list-text'>$title</p>
                  <p class='list-text list-subtext'>Description: $description</p>
                  <p class='list-text list-subtext'>Creator: $creator</p>
                  <p class='list-text list-subtext'>Last edited: $last_edit</p>
                </div>
                $edit_button
              </li>";
        }
      }
      ?>
    </ol>
  </main>
  <div id="background-overlay" class="overlay"></div>
  <div id="modal" class="modal" onclick="logout()">
    <div class="modal-content">
      <p>Logout</p>
    </div>
  </div>
</body>

</html>