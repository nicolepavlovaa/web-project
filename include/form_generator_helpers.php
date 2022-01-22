<?php
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
  if ($tag == "label" && $type == "file") {
    return "file-generated";
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
