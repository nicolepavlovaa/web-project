<?php
function get_open_answer_component($question_id)
{
  $open_answer = "
          <div class='form-group'>
            <label class='form__label' htmlFor='answer-$question_id'>Answer:</label>
            <input type='text' class='input' name='user_answers-$question_id' id='answer-$question_id' />
          </div>";
  return $open_answer;
}

function get_closed_question_answers($db, $exact_question_id)
{
  $result = "";
  $answers_multiplechoice = mysqli_query($db, "SELECT id, answer, question_id FROM answers WHERE question_id=$exact_question_id;");
  foreach ($answers_multiplechoice->fetch_all() as $option) {
    $answer_id = $option[0];
    $value = $option[1];
    $question_id = $option[2];
    $new_str = "<div class='radio'><input type='radio' name='user_answers-$question_id' id='answer-$answer_id' value='$answer_id'> $value</div>";
    $result = $result . $new_str;
  }
  $result =  "<div class='form-group'>$result</div>";
  return $result;
}
