<?php

// E.g. find if the user is logged in
if($_SESSION['login_user']) {
    // Logged 
    //form.php
    echo 'logged penis';
}
else {
    echo 'penis';
}

// Destroy the session
//if($log_out)
//    session_destroy();
?>
