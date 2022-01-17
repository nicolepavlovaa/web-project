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
        </div>
        <div class="textarea-container">
            <textarea class="textarea" id="gform" name="gform" rows="30" cols="120">
            </textarea>
            <textarea class="textarea" id="form-content" name="form-content" rows="30" cols="300">
stem=what do you like?,tag=input,type=text,name=input_name,label=input_label;tag=img,label=img_label,src=assets/logo.png
stem=my question is this,tag=textarea,name=input_area,label=my text area
stem=choose from the following,tag=input,type=radio,name=options,label=option one,value=one;tag=input,type=radio,name=options,label=option 2,value=two
stem=choose date,tag=input,type=date,name=date,label=this is a date
stem=choose month,tag=input,type=month,name=month,label=this is month
stem=choose datetime,tag=input,type=datetime-local,name=local,label=this is datetime
stem=choose file,tag=input,type=file,name=file
            </textarea>
        </div>
        <button type="submit" name="submit" value="submit" class="btn">Generate</button>
    </form>
</body>

</html>

<script type="text/javascript">
    window.addEventListener("load", function() {
        fileInput = document.getElementById("file-input");
        fileInput.onchange = function(event) {
            let fileList = fileInput.files;
            console.log(fileList);
            f = fileList.item(0);
            name = f.name;

            // display the filename
            fileName = document.getElementById("filename");
            fileName.innerHTML = name;
            fileName.style.backgroundColor = "white";

            let reader = new FileReader();
            let JsonObj;

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
    })
</script>

<?php
$json = json_decode($_POST["gform"], true);
$title = $json["form_title"];
$description = $json["form_description"];
$counter = 0;

$content = $_POST["form-content"];
$rows = explode("\n", $content);

function specialsplit($string, $char)
{
    $level = 0;       // number of nested sets of brackets
    $ret = array(''); // array to return
    $cur = 0;         // current index in the array to return, for convenience

    for ($i = 0; $i < strlen($string); $i++) {
        switch ($string[$i]) {
            case '{':
                $level++;
                $ret[$cur] .= '[';
                break;
            case '}':
                $level--;
                $ret[$cur] .= ']';
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
    return "";
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
        // fix ids
        $class = chooseClass($tag, trim(explode('=', $type)[1], "'"));
        $class_attr = $class == "" ?: "class='$class'";
        $el = "<$tag $type id='el-$counter' $class_attr $name $src $value></$tag>";
        if ($tag != '') {
            $result = $result . "<p class='form-question-title'>$stem</p>";
            if ($label != "" && !($tag == "input" && trim(explode('=', $type)[1], "'") == "radio")) {
                $label_el = "<label class='form__label' for='el-$counter'>$label</label>";
                $result = $result . $label_el;
            }
            if ($tag == "input" && trim(explode('=', $type)[1], "'") == "radio") {
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
    $json_settings = __DIR__ . "/form_settings/result_$timestamp.json";
    if (!file_exists($filename)) {
        $file = fopen($filename, "w") or die("Unable to open file.");
        echo fwrite($file, trim($_POST["form-content"]));
        fclose($file);
    }
    if (!file_exists($json_settings)) {
        $file = fopen($json_settings, "w") or die("Unable to open file.");
        echo fwrite($file, trim($_POST["gform"]));
        fclose($file);
    }
}
?>