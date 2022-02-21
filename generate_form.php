<?php
include(__DIR__ . "/private/form_generator_helpers.php");
include(__DIR__ . "/private/authenticate.php");
include(__DIR__ . "/private/queries.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = get_user_email($token);
        $errors = create_form_files($email);
        if (count($errors) == 0) {
            header("location: index");
        }
    }
} else {
    header("location: login");
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Generate form</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="js/generate_form.js"></script>
    <script type="text/javascript" src="js/helpers.js"></script>
</head>

<body class="page">
    <div id="open-user-menu" class="icon-wrapper" onclick="openModal()">
        <i class="fa-user-circle icon"></i>
    </div>
    <a href="index" class="logo">
        <img src="assets/logo.png" />
    </a>
    <form id="form" class="form" method="POST">
        <label class="form-question-title" for="gform">Type the code to generate html form and choose a settings file:</label><br>
        <div class="file-wrapper">
            <input type="file" id="file-input" />
            <label for="file-input" class="btn-2">upload json</label>
            <p class="form-question filename" id="filename"></p>
            <input type="file" id="file-input-2" />
            <label for="file-input-2" class="btn-2">upload form</label>
            <p class="form-question filename" id="filename-2"></p>
        </div>
        <div class="textarea-container">
            <textarea class="textarea" id="gform" name="gform" rows="30" cols="120"></textarea>
            <textarea class="textarea" id="form-content" name="form-content" rows="30" cols="300"></textarea>
        </div>
        <button type="submit" name="submit" value="submit" class="btn">Generate</button>
    </form>
    <span class=<?php if(isset($errors) && sizeof($errors) == 0) {echo "";} elseif(isset($errors) && sizeof($errors) > 0) {echo "error"; }?>>
        <?php
        if (isset($errors)) {
            $is_valid = sizeof($errors) == 0;
            if (!$is_valid) {
                foreach ($errors as $key => $value) {
                    echo "<p class='error-msg'>$value</p>";
                }
            }
            unset($errors);
        }
        ?>
    </span>
    <div id="background-overlay" class="overlay"></div>
    <div id="modal" class="modal" onclick="logout()">
        <div class="modal-content">
            <p>Logout</p>
        </div>
    </div>
</body>

</html>