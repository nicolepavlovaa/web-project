<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Generate form</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script type="text/javascript" src="js/generate_form.js"></script>
    <script type="text/javascript" src="js/helpers.js"></script>
</head>

<body class="page">
    <a href="forms" class="logo">
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
            <textarea class="textarea" id="gform" name="gform" rows="30" cols="120">
            </textarea>
            <textarea class="textarea" id="form-content" name="form-content" rows="30" cols="300">
            </textarea>
        </div>
        <button type="submit" name="submit" value="submit" class="btn">Generate</button>
    </form>
</body>

</html>


<?php
include("include/form_generator_helpers.php");
include("include/authenticate.php");
include("include/queries.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {
    $email = get_user_email($token);
    $json = json_decode($_POST["gform"], true);
    $title = $json["form_title"];
    $description = $json["form_description"];
    $counter = 0;

    $content = $_POST["form-content"];
    $rows = explode("\n", $content);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();
        $filename = __DIR__ . "/generated/result_$timestamp.txt";
        $json_settings = __DIR__ . "/generated/result_$timestamp.json";
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
} else {
    header("location: login");
}
?>