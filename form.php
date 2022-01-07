<?php
$servername = "localhost";
$username = "root";
$password = "AbC13579";
$database = "web_project";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  echo "$conn->connect_error";
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$form_id = $_GET['form_id'];
$form_data = mysqli_query($conn, "SELECT id, title, description FROM forms WHERE id=$form_id;");
$form = $form_data->fetch_assoc();
$data = mysqli_query($conn, "SELECT id,question,answers,form_id FROM questions WHERE form_id=$form_id;");
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Form</title>
  <link href="./styles.css" rel="stylesheet" />
</head>

<body class="page">
  <img src="./logo.png" class="logo" />
  <form id="form" class="form" method="POST">
    <div>
      <fieldset class="description-wrapper">
        <div class="input-title">
          <p class="form-question"><?php echo $form['title'] ?></p>
        </div>
        <div>
          <p class="form-question"><?php echo $form['description'] ?></p>
        </div>
      </fieldset>
      <div id="container">
        <?php
        $i = 0;
        $assoc = array();
        while ($row = $data->fetch_assoc()) {
          $question = $row['question'];
          // TODO: add multiple choice
          $answers = $row['answers'];
          $is_multiple_choice = $row['is_multiple_choice'];

          echo "
          <div class='form-question-wrapper'>
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
    <div class="buttons">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
  </form>
  <span>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      foreach ($assoc as $question_id => $answer) {
        // TODO: change later to real user id
        $user_id = 1;
        mysqli_query($conn, "INSERT INTO form_results(question_id, answered_by, answer) VALUES ($question_id, $user_id, '$answer');");
      }
    }
    ?>
  </span>
</body>

</html>