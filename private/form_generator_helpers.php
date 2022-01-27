<?php
function isJson($string)
{
  json_decode($string);
  return json_last_error() === JSON_ERROR_NONE;
}

function create_form_files($email)
{
  $errors = [];
  $date = new DateTime();
  $timestamp = $date->getTimestamp();
  $filename = __DIR__ . "/../generated/result_$timestamp.txt";
  $json_settings = __DIR__ . "/../generated/result_$timestamp.json";

  $json_content = json_decode($_POST["gform"], true);
  $title = "";

  if ($json_content != null) {
    foreach ($json_content as $json_key => $value) {
      if ($json_key == "form_title") {
        $title = $value;
      }
    }
  }


  if (trim($_POST["form-content"]) == "") {
    $errors["content"] = "Content is required.";
  }

  if (trim($title) == "") {
    $errors["title"] = "Title is required.";
  }

  if (!isJson(trim($_POST["gform"]))) {
    $errors["settings"] = "Please provide a valid JSON";
  }

  if (count($errors) == 0) {
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
  return $errors;
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
  $has_password = false;
  $password = "";

  foreach ($json_content as $json_key => $value) {
    if ($json_key == "form_title") {
      $title = $value;
    } elseif ($json_key == "form_description") {
      $description = $value;
    } elseif ($json_key == "creator" && $value == get_user_email($token)) {
      $display_edit_button = true;
    } elseif ($json_key == "password") {
      $has_password = true;
      $password = $value;
    }
  }

  return [$title, $description, $rows, $display_edit_button, $has_password, $password];
}

function specialsplit($string, $char)
{
  $level = 0;       // number of nested sets of brackets
  $ret = array(''); // array to return
  $cur = 0;         // current index in the array to return, for convenience

  // todo: add the possibility for a sentence with comma or ; using {}
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
  //todo: add rest
  if ($tag == "textarea" || ($tag == "input" && ($type == "text" || $type == "password" || $type == "number" || $type == "email" || $type == "date" || $type == "month" || $type == "datetime-local"))) {
    return "input";
  }
  if ($tag == "input" && $type == "file") {
    return "file";
  }
  return "";
}

function getAttrValue($attr)
{
  $arr = explode('=', $attr);
  $res = "";
  if (count($arr) >= 2) {
    $res = trim(explode('=', $attr)[1], "'");
  }
  return $res;
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

function valid_tag($tag, $type, $name, $src)
{
  if (($tag == "input" && $type != "" && $name != "") || ($tag == "img" && $src != "") || ($tag != "input" && $tag != "img")) {
    return true;
  }
  return false;
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
    $href = '';
    $style = '';

    foreach ($elements as $el) {
      $pair = specialsplit($el, '=');

      if (count($pair) == 2) {
        $key = trim($pair[0]);
        $val = trim($pair[1]);

        // todo: catch errors
        if ($key == "tag") {
          $tag = $val;
        } elseif ($key == "stem") {
          $trimmed = trim(trim($val), "}");
          $trimmed = trim($trimmed, "{");
          $stem = $trimmed;
        } elseif ($key == "type") {
          $type = "$key='$val'";
        } elseif ($key == "src") {
          $src = "$key='$val'";
        } elseif ($key == "name") {
          $name = "$key='$val'";
        } elseif ($key == "label") {
          $label = $val;
        } elseif ($key == "value") {
          $trimmed = trim(trim($val), "}");
          $trimmed = trim($trimmed, "{");
          $value = "$key='$trimmed'";
        } elseif ($key == "href") {
          $href = "$key='$val'";
        } elseif ($key == "style") {
          $trimmed = trim(trim($val), "}");
          $trimmed = trim($trimmed, "{");
          $style = "$key='$trimmed'";
        }
      }
    }

    $class = chooseClass($tag, getAttrValue($type));
    $class_attr = $class == "" ?: "class='$class'";
    $el = "<$tag $type id='el-$counter' $class_attr $name $src $value $href $style></$tag>";

    if ($tag != "") {
      if ($stem != "") {
        $result = $result . "<p class='form-question-title'>$stem</p>";
      }

      if ($tag == "p") {
        $p_val = getAttrValue($val);
        $el = "<$tag id='el-$counter' $class_attr $href $style>$p_val</$tag>";
      }

      if (valid_tag($tag, getAttrValue($type), getAttrValue($name), getAttrValue($src))) {
        if ($label != "" && !($tag == "input" && getAttrValue($type) == "radio")) {
          $class = chooseClass("label", getAttrValue($type));
          $class_name = $class == "" ? "class='form__label'" : "class='$class'";
          $label_el = "<label $class_name for='el-$counter'>$label</label>";
          $result = $result . $label_el;
        }
        if ($tag == "input" && getAttrValue($type) == "radio") {
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
  }
  return $result;
}
