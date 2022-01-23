<?php
include(__DIR__ . "/env.php");

$servername = $_ENV["SERVERNAME"];
$username = $_ENV["USERNAME"];
$password = $_ENV["PASSWORD"];
$database = $_ENV["DATABASE"];

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}
