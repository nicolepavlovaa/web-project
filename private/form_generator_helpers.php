<?php
function create_form_files($email)
{
  $date = new DateTime();
  $timestamp = $date->getTimestamp();
  $filename = __DIR__ . "/../generated/result_$timestamp.txt";
  $json_settings = __DIR__ . "/../generated/result_$timestamp.json";

  if (!file_exists($filename)) {
    $file = fopen($filename, "w") or die("Unable to open file.");
    fwrite($file, trim($_POST["form-content"]));
    fclose($file);
  }
  if (!file_exists($json_settings)) {
    $file = fopen($json_settings, "w") or die("Unable to open file.");
    $json_post = json_decode($_POST["gform"], true);
    $json_post["creator"] = $email;
    fwrite($file, trim(json_encode($json_post)));
    fclose($file);
  }
}

function load_form_contents($token)
{
  $form_id = $_GET['form_id'];
  $form_json = '';
  $form_txt = '';
  $files = scandir('generated/');

  foreach ($files as $file) {
    if ($file == "result_$form_id.txt") {
      $form_txt = $file;
    } elseif ($file == "result_$form_id.json") {
      $form_json = $file;
    }
  }

  $title = "";
  $description = "";

  $content = file_get_contents("generated/$form_txt");
  $rows = explode("\n", $content);

  $info = file_get_contents("generated/$form_json");
  $json_content = json_decode($info, true);
  $display_edit_button = false;

  foreach ($json_content as $json_key => $value) {
    if ($json_key == "form_title") {
      $title = $value;
    } elseif ($json_key == "form_description") {
      $description = $value;
    } elseif ($json_key == "creator" && $value == get_user_email($token)) {
      $display_edit_button = true;
    }
  }

  return [$title, $description, $rows, $display_edit_button];
}

function specialsplit($string, $char)
{
  $level = 0;       // number of nested sets of brackets
  $ret = array(''); // array to return
  $cur = 0;         // current index in the array to return, for convenience

  for ($i = 0; $i < strlen($string); $i++) {
    switch ($string[$i]) {
      case '{':
        $level++;
        $ret[$cur] .= '{';
        break;
      case '}':
        $level--;
        $ret[$cur] .= '}';
        break;
      case $char:
        if ($level == 0) {
          $cur++;
          $ret[$cur] = '';
          break;
        }
        // else fallthrough
      default:
        $ret[$cur] .= $string[$i];
    }
  }

  return $ret;
}

function getRowElements($row)
{
  $matches = specialsplit($row, ',');
  return $matches;
}

function chooseClass($tag, $type)
{
  // add rest
  if ($tag == "textarea" || ($tag == "input" && ($type == "text" || $type == "password" || $type == "number" || $type == "email" || $type == "date" || $type == "month" || $type == "datetime-local"))) {
    return "input";
  }
  if ($tag == "input" && $type == "file") {
    return "file";
  }
  return "";
}

function getTypeValue($type)
{
  return trim(explode('=', $type)[1], "'");
}

function split_inputs($rows)
{
  $result = '';
  foreach ($rows as $row) {
    if (trim($row) != '') {
      $result = $result . "<div class='description-wrapper'>";
      $inputs = specialsplit($row, ';');
      $curr = parser($inputs);
      $result = $result . $curr;
      $result = $result . "</div>";
    }
  }
  return $result;
}

function parser($rows)
{
  global $counter;
  $result = '';
  foreach ($rows as $row) {
    $elements = getRowElements($row);
    $stem = '';
    $tag = '';
    $type = '';
    $src = '';
    $name = '';
    $label = '';
    $value = '';

    foreach ($elements as $el) {
      $pair = specialsplit($el, '=');

      if (count($pair) == 2) {
        $key = trim($pair[0]);
        $value = trim($pair[1]);

        // is this all? add styles
        if ($key == "tag") {
          $tag = $value;
        } elseif ($key == "stem") {
          $stem = $value;
        } elseif ($key == "type") {
          $type = "$key='$value'";
        } elseif ($key == "src") {
          $src = "$key='$value'";
        } elseif ($key == "name") {
          $name = "$key='$value'";
        } elseif ($key == "label") {
          $label = $value;
        } elseif ($key == "value") {
          $value = "$key='$value'";
        }
      }
    }
    $class = chooseClass($tag, getTypeValue($type));
    $class_attr = $class == "" ?: "class='$class'";
    $el = "<$tag $type id='el-$counter' $class_attr $name $src $value></$tag>";
    if ($tag != '') {
      $result = $result . "<p class='form-question-title'>$stem</p>";
      if ($label != "" && !($tag == "input" && getTypeValue($type) == "radio")) {
        $class = chooseClass("label", getTypeValue($type));
        $class_name = $class == "" ? "class='form__label'" : "class='$class'";
        $label_el = "<label $class_name for='el-$counter'>$label</label>";
        $result = $result . $label_el;
      }
      if ($tag == "input" && getTypeValue($type) == "radio") {
        $result = $result . "<div class='radio'>";
        $result = $result . $el;
        $counter++;
        if ($label != '') {
          $label_el = "<label class='form__label' for='el-$counter'>$label</label>";
          $result = $result . $label_el;
        }
        $result = $result . "</div>";
      } else {
        $result = $result . $el;
        $counter++;
      }
    }
  }
  return $result;
}
