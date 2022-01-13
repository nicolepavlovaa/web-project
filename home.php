<?php
 include("authenticate.php");

 $token = $_COOKIE['auth'];
 $is_jwt_valid = is_jwt_valid($token);
 
 if ($is_jwt_valid) {
    // Logged 
    echo 'logged penis';
}
else {
  header("location: login");
}
