<?php
include("config.php");
include("include/authenticate.php");
include("include/parsers.php");
include("include/generate_components.php");
include("include/queries.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  $form_id = $_GET['form_id'];
  $res = get_form_info($db, $form_id);

  $form_data = $res[0];
  $questions = $res[1];
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
</head>

<body class="page">
  <a href="forms" class="logo">
    <img src="assets/logo.png" />
  </a>
  <form id="form" class="form" method="POST">
    <div>
      <div class="description-wrapper">
        <div class="input-title">
          <p class="form-question"><?php echo $form_data['title'] ?></p>
        </div>
        <div>
          <p class="form-question"><?php echo $form_data['description'] ?></p>
        </div>
      </div>
      <div id="container">
        <?php
        $answers_map = array();
        while ($row = $questions->fetch_assoc()) {
          $question_id = $row['id'];
          $question = $row['stem'];
          $answers = $row['answers'];
          $is_multiple_choice = $row['type'];

          $answer_component = $is_multiple_choice ? get_closed_question_answers($db, $question_id) : get_open_answer_component($question_id);

          echo "
          <div class='description-wrapper'>
            <p class='form-question'>$question</p>
            $answer_component
          </div>";

          $answers_map[$question_id] = parse_input($_POST["user_answers-$question_id"]);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $user_email = get_user_email($token);
          insert_answers($db, $answers_map, $user_email);
        }
        ?>
      </div>
    </div>
    <div class="buttons-and-text">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
  </form>
</body>

</html>