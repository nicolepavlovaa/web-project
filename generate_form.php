<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>Generate form</title>
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body class="page">
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

<script type="text/javascript">
    window.addEventListener("load", function() {
        fileInput = document.getElementById("file-input");
        fileInput2 = document.getElementById("file-input-2");
        let reader = new FileReader();

        fileInput.onchange = function(event) {
            let fileList = fileInput.files;
            let JsonObj;

            f = fileList.item(0);
            name = f.name;

            // display the filename
            fileName = document.getElementById("filename");
            fileName.innerHTML = name;
            fileName.style.backgroundColor = "white";

            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    // Render thumbnail.
                    JsonObj = JSON.parse(e.target.result);
                    document.getElementById('gform').value = JSON.stringify(JsonObj, null, 2);
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsText(f);
        }

        fileInput2.onchange = function(event) {
            let fileList = fileInput2.files;
            let obj;

            f = fileList.item(0);
            name = f.name;

            // display the filename
            fileName = document.getElementById("filename-2");
            fileName.innerHTML = name;
            fileName.style.backgroundColor = "white";

            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    // Render thumbnail.
                    obj = e.target.result;
                    document.getElementById('form-content').value = obj;
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsText(f);
        }
    })
</script>

<?php
include("include/form_generator_helpers.php");
include("include/authenticate.php");

$token = $_COOKIE['auth'];
$is_jwt_valid = is_jwt_valid($token);

if ($is_jwt_valid) {

    $json = json_decode($_POST["gform"], true);
    $title = $json["form_title"];
    $description = $json["form_description"];
    $counter = 0;

    $content = $_POST["form-content"];
    $rows = explode("\n", $content);


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo
        "<form id='form' class='form' method='POST'>
    <div class='description-wrapper'>
        <div class='input-title'>
            <p class='form-question'>$title</p>
        </div>
        <div>
            <p class='form-question'>$description</p>
        </div>
    </div>
    <div id='containter'>";
        $res = split_inputs($rows);
        echo $res;
        echo '</div>
    </div>
</form>';

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
            fwrite($file, trim($_POST["gform"]));
            fclose($file);
        }
    }
} else {
    header("location: login");
}
?>