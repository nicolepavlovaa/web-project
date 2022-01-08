<?php
include("config.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $questions = $_POST['question'];
  $answers = $_POST['answers'];
  $types = $_POST['checkbox'];


  mysqli_query($db, "INSERT INTO forms(title, description, created_by) VALUES ('$title', '$description', 'tediliev');");
  $form_id = (int)mysqli_insert_id($db);

  if (isset($questions) && isset($title)) {
    for ($i = 0; $i < count($questions); $i++) {
      $question = $questions[$i];
      $is_multiple_choice = $types[$i] == 'on' ?  1 : 0;
      $question_answers = isset($answers[$i]) ? $answers[$i] : "";
      // TODO: change later to real user id
      $user_id = 1;
      mysqli_query($db, "INSERT INTO questions(stem, type, form_id) VALUES ('$question', $is_multiple_choice, $form_id);");
    }
  }
}

?>

<script type="text/javascript">
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
  <a href="home.php" class="logo">
    <img src="./logo.png" />
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
</body>

</html>