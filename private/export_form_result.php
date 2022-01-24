<?php

function get_names_array($rows)
{
  $gathered_stems_names = [[]];
  foreach ($rows as $single_row_str) {
    $gathered_row = [];
    $single_row = explode(',', $single_row_str);
    foreach ($single_row as $i => $element) {

      $key_value = explode("=", $element);
      if ($key_value[0] == 'stem') {
        array_push($gathered_row, $key_value[1]);
      }
      if ($key_value[0] == 'name' and sizeof($gathered_row) == 1) {
        array_push($gathered_row, $key_value[1]);
      }
    }
    array_push($gathered_stems_names, $gathered_row);
    var_dump($gathered_row);
    echo '</br>';
  }
  return $gathered_stems_names;
}
