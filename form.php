<?php
include(__DIR__ . "/config.php");
include(__DIR__ . "/private/authenticate.php");
include(__DIR__ . "/private/parsers.php");
include(__DIR__ . "/private/generate_components.php");
include(__DIR__ . "/private/queries.php");
include(__DIR__ . "/private/form_generator_helpers.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  $content = load_form_contents($token);
  $title = $content[0];
  $description = $content[1];
  $rows = $content[2];
  $display_edit_button = $content[3];
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
  <button id='delete-form' name='delete-form' onclick="deleteForm()" class='btn'>Delete form</button>
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
  $res = split_inputs($rows);
  echo $res;
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
$userid = 'tiliev';
$gathered_stems_names = get_names_array($rows);

$t = time();
$date = date('m/d/Y h:i:s a', $t);
$result_form_answered = __DIR__ . "/answers/answer_" . $form_id . "" . $userid . "" . $t . ".csv";
$file = fopen($result_form_answered, "w") or die("Unable to open file." . $result_form_answered);
$data = [['form_id, stem, answer, answered_on, answered_by']];
foreach ($gathered_stems_names as $i => $row) {
  $csv_stem = $row[0];
  $csv_answer = $_POST[$row[1]];
  $csv_row = array($form_id, $csv_stem, $csv_answer, $date, $userid);
  array_push($data, $csv_row);
}
foreach ($data as $row) {
  fputcsv($file, $row);
}
fclose($file);