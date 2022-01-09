<?php
include("config.php");
include("authenticate.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
  $form_id = $_GET['form_id'];
  $form_data = mysqli_query($db, "SELECT id, title, description FROM forms WHERE id=$form_id;");
  $form = $form_data->fetch_assoc();
  $data = mysqli_query($db, "SELECT id, stem, form_id FROM questions WHERE form_id=$form_id;");

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($assoc as $question_id => $answer) {
      $user_email = get_user_email($token);
      mysqli_query($db, "INSERT INTO form_results(question_id, answered_by, answer) VALUES ($question_id, '$user_email', '$answer');");
    }
  }
} else {
  header("location: login.php");
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Form</title>
  <link href="./styles.css" rel="stylesheet" />
</head>

<body class="page">
  <a href="home.php" class="logo">
    <img src="./logo.png" />
  </a>
  <form id="form" class="form" method="POST">
    <div>
      <div class="description-wrapper">
        <div class="input-title">
          <p class="form-question"><?php echo $form['title'] ?></p>
        </div>
        <div>
          <p class="form-question"><?php echo $form['description'] ?></p>
        </div>
      </div>
      <div id="container">
        <?php
        $i = 0;
        $assoc = array();
        while ($row = $data->fetch_assoc()) {
          $question = $row['stem'];
          // TODO: add multiple choice
          $answers = $row['answers'];
          $is_multiple_choice = $row['is_multiple_choice'];

          echo "
          <div class='description-wrapper'>
            <p class='form-question'>$question</p>
            <div class='form-group'>
              <label class='form__label' htmlFor='answer-$i'>Answer:</label>
              <input type='text' class='input' name='user_answers[]' id='answer-$i' />
            </div>
          </div>";
          $id = $row['id'];
          $assoc[$id] = $_POST['user_answers'][$i];
          $i++;
        } ?>
      </div>
    </div>
    <div class="buttons-and-text">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
  </form>
</body>

</html>