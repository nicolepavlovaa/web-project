<?php
function get_user_by_email($db, $param_email)
{
  // Prepare a select statement
  $sql = "SELECT email FROM users WHERE email = ?";

  if ($stmt = $db->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $param_email);

    // Set parameters
    $param_email = trim($_POST["email"]);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
      // store result
      $stmt->store_result();

      if ($stmt->num_rows == 1) {
        $email_err = "This email is already taken.";
      } else {
        $email = trim($_POST["email"]);
      }
    } else {
      echo "Oops! Something went wrong. Please try again later.";
    }
    // Close statement
    $stmt->close();
  } else {
    echo $db->error;
  }
  return [$email, $email_err];
}

function register_user($db, $param_email, $param_password, $param_fk, $param_name, $email, $password)
{
  // Prepare an insert statement
  $sql = "INSERT INTO users (email, password, fk, name) VALUES (?, ?, ?, ?)";

  if ($stmt = $db->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("ssds", $param_email, $param_password, $param_fk, $param_name);

    // Set parameters
    $param_email = $email;
    $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
    $param_fk = trim($_POST["fn"]);
    $param_name = trim($_POST["name"]);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
      // Redirect to login page
      header("location: login");
    } else {
      echo "Oops! Something went wrong. Please try again later.";
      echo $stmt->error;
    }

    // Close statement
    $stmt->close();
  }
}

function login_user($db, $myemail, $mypassword)
{
  $sql = "SELECT password FROM users WHERE email = '" . $myemail . "'";
  $stmt = $db->query($sql);

  $result = $stmt->fetch_all(MYSQLI_ASSOC);
  return $result;
}

function get_form_info($db, $form_id)
{
  $form_data = mysqli_query($db, "SELECT id, title, description FROM forms WHERE id=$form_id;")->fetch_assoc();
  $questions = mysqli_query($db, "SELECT id, stem, form_id, type FROM questions WHERE form_id=$form_id;");

  return [$form_data, $questions];
}

function insert_answers($db, $answers_map, $user_email)
{
  foreach ($answers_map as $question_id => $answer) {
    mysqli_query($db, "INSERT INTO form_results(question_id, answered_by, answer) VALUES ($question_id, '$user_email', '$answer');");
  }
}

function create_form($db, $questions, $types, $answers, $title, $description, $email)
{
  mysqli_query($db, "INSERT INTO forms(title, description, created_by) VALUES ('$title', '$description', '$email');");
  $form_id = (int)mysqli_insert_id($db);

  if (isset($title) && isset($description)) {
    for ($i = 0; $i < count($questions); $i++) {
      $question = parse_input($questions[$i]);
      $is_multiple_choice = $types[$i] == 'on' ?  1 : 0;
      $question_answers = explode("\n", $answers[$i]);

      mysqli_query($db, "INSERT INTO questions(stem, type, form_id) VALUES ('$question', $is_multiple_choice, $form_id);");
      $question_id = (int)mysqli_insert_id($db);
      foreach ($question_answers as $answer) {
        mysqli_query($db, "INSERT INTO answers(question_id, answer) VALUES ($question_id, '$answer');");
      }
    }
  }
}
