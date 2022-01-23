<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
$id = $_POST["id"];

if (is_file("../generated/result_$id.json")) {
  unlink("../generated/result_$id.json") or die('An error occurred while deleting the json file.');
} else {
  die("File with $id does not exist with.");
}

if (is_file("../generated/result_$id.txt")) {
  unlink("../generated/result_$id.txt") or die('An error occurred while deleting the txt file.');
} else {
  die("File with id $id does not exist.");
}
