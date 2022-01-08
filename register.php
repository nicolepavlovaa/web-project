
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <link href="./styles.css" rel="stylesheet" />
</head>

<body class="page">
  <form id="form" class="form" method="POST">
    <div id="container" class="container">
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="email">Email</label>
          <input type="text" class="input" name="email" id="email" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="username">Username</label>
          <input type="text" class="input" name="username" id="username" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="name">Name</label>
          <input type="text" class="input" name="name" id="name" />
        </div>
      </div>
      <div class=" fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="fn">FN</label>
          <input type="text" class="input" name="fn" id="fn" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="password">Password</label>
          <input type="password" class="input" name="password" id="password" />
        </div>
      </div>
    </div>
    <div class="buttons-and-text">
      <button type="submit" name="submit" value="submit" class="btn">Register</button>
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </form>
  <span>
  </span>
</body>

</html>

<?php

include("config.php");
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Prepare a select statement
        $sql = "SELECT email FROM users WHERE email = ?";
        
        if($stmt = $db->prepare($sql)){

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $email_err = "This email is already taken.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
           

            // Close statement
            $stmt->close();
        }
        else {
          echo '    stmt duha pak     ';
          echo $db->error;

        }
    
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    echo 'before if';
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        echo 'in if';
        // Prepare an insert statement
        $sql = "INSERT INTO users (email, password, fk, name) VALUES (?, ?, ?, ?)";
         
        if($stmt = $db->prepare($sql)){
          echo "stmt duha";
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssds", $param_email, $param_password, $param_fk, $param_name);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_fk = trim($_POST["fn"]);
            $param_name = trim($_POST["name"]);
            echo $param_email, $param_fk, $param_name, $param_password;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                echo $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $db->close();
}
?>

<!-- <script type="text/javascript">
  let counter = 0;
  window.addEventListener("load", function() {
    document.getElementById("add").addEventListener("click", function() {
      // Create a div
      let fieldset = document.createElement("fieldset");
      fieldset.setAttribute("id", `group-${counter}`);
      fieldset.style.cssText = 'border:none; background: white; border-radius: 1rem 1rem 1rem 1rem; margin-top: 1rem; padding: 2rem 2rem 2rem 3rem;';

      // Create a text input
      let questionWrapper = document.createElement("div");
      questionWrapper.classList.add('form-group');

      let question = document.createElement("input");
      question.classList.add('input');
      question.setAttribute("type", "text");
      question.setAttribute("id", `question-${counter}`);
      question.setAttribute("name", `question[]`);

      let questionLabel = document.createElement('label');
      questionLabel.classList.add('form__label');
      questionLabel.setAttribute("htmlFor", `question-${counter}`);
      questionLabel.appendChild(document.createTextNode('Question:'));

      questionWrapper.appendChild(questionLabel);
      questionWrapper.appendChild(question);

      // create a checkbox
      let checkboxWrapper = document.createElement('div');
      checkboxWrapper.classList.add('checkbox-group');

      let checkbox = document.createElement('input');
      checkbox.style.cssText = 'display: inline-block; vertical-align: middle;';
      checkbox.setAttribute("type", "checkbox");
      checkbox.setAttribute("name", `checkbox[]`);
      checkbox.setAttribute("id", `checkbox-${counter}`);

      let checkboxLabel = document.createElement('label');
      checkboxLabel.classList.add('form__label');
      checkboxLabel.htmlFor = `checkbox-${counter}`;
      checkboxLabel.appendChild(document.createTextNode('Question has multiple answers.'));

      checkboxWrapper.appendChild(checkbox);
      checkboxWrapper.appendChild(checkboxLabel);

      // create answers input
      let answersWrapper = document.createElement("div");

      let answers = document.createElement("textarea");
      answers.classList.add("input");
      answers.setAttribute("type", "text");
      answers.setAttribute("name", `answers[]`);
      answers.setAttribute("id", `answers-${counter}`);
      answers.style.display = 'none';

      let answersLabel = document.createElement('label');
      answersLabel.classList.add("form__label");
      answersLabel.setAttribute("htmlFor", `answers-${counter}`);
      answersLabel.setAttribute("id", `answers-label-${counter}`);
      answersLabel.appendChild(document.createTextNode('Answers:'));
      answersLabel.style.display = 'none';

      answersWrapper.appendChild(answersLabel);
      answersWrapper.appendChild(answers);

      // add the inputs to the div
      fieldset.appendChild(questionWrapper);
      fieldset.appendChild(checkboxWrapper);
      fieldset.appendChild(answersWrapper);

      //Append the div to the container div
      document.getElementById("container").appendChild(fieldset);
      counter++;
    });

    let form = document.getElementById('form');
    form.addEventListener('input', function(event) {
      for (let i = 0; i < counter; i++) {
        let checkbox = document.getElementById(`checkbox-${i}`);
        let answers = document.getElementById(`answers-${i}`);
        let answers_label = document.getElementById(`answers-label-${i}`);

        checkbox.addEventListener("input", function() {
          answers.style.display = checkbox.checked ? 'block' : 'none';
          answers_label.style.display = checkbox.checked ? 'block' : 'none';
        });
      }
    }, true);
  });
</script>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Project</title>
  <link href="./styles.css" rel="stylesheet" />
</head>

<body class="page">
  <form id="form" class="form" method="POST">
    <div>
      <div id="container">&nbsp;</div>
    </div>
    <div class="buttons">
      <input type="button" value="Add question" id="add" class="btn">
      <button type="submit" name="submit" value="submit" class="btn">Submit</button>
    </div>
  </form>
  <span>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $questions = $_POST['question'];
      $answers = $_POST['answers'];
      $types = $_POST['checkbox'];

      for ($i = 0; $i < count($questions); $i++) {
        // send data to server
        echo $questions[$i];
        echo '<br>';
        echo $answers[$i];
        echo '<br>';
        echo $types[$i];
        echo '<br>';
        echo '<br>';
        echo '<br>';
      }
    }
    ?>
  </span>
</body>

</html> -->