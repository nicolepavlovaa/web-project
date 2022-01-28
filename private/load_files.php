<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
$id = $_POST["id"];

if (is_file("../generated/result_$id.json")) {
  $info = file_get_contents("../generated/result_$id.json");
  $json_content = json_decode($info, true);
} else {
  die("File with id $id does not exist.");
}

if (is_file("../generated/result_$id.txt")) {
  $content = file_get_contents("../generated/result_$id.txt");
} else {
  die("File with id $id does not exist.");
}

echo json_encode(Array('json' => $json_content, 'txt' => $content));
