<?php
include("config.php");
include("include/authenticate.php");

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
  <script type="text/javascript" src="js/helpers.js"></script>
</head>

<body class="page">
  <main>
    <a href="forms" class="logo">
      <img src="assets/logo.png" />
    </a>
    <a href="create_form" class="link">
      <button type="submit" class="btn">Create new form</button>
    </a>
    <ol id="forms" class="gradient-list">
      <?php
      foreach ($pairs as $key => $value) {
        $json_file = '';
        foreach ($value as $filename) {
          if (explode(".", $filename)[1] == "json") {
            $json_file = $filename;
          }
        }

        $file = file_get_contents("generated/$json_file");
        $json_content = json_decode($file, true);
        foreach ($json_content as $json_key => $value) {
          if ($json_key == "form_title") {
            echo "<li onclick='openForm($key);' id=$key>$value</li>";
          }
        }
      }
      ?>
    </ol>
  </main>
</body>

</html>