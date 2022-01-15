<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Generate form</title>
  <link href="css/styles.css" rel="stylesheet" />
</head>

<body class="page">
  <form id="form" class="form" method="POST">
    <label for="gform">Type the code to generate html form:</label><br>
    <textarea id="gform" name="gform" rows="20" cols="120">
        {
            "form_title":"",
            "form_description":"",
            "questions":{
                "0":{"stem":"Is this a question?", "html_tag":{"label":"blablalbal", "atr":{"type":"text", "class":"ok"}, "input":"text"},
                "1":{"stem":"What is learning?", "input":""}
            }
        }
    </textarea><br>
    <button type="submit" name="submit" value="submit" class="btn">Generate</button>
  </form>
</body>

</html>

<?php
$json = json_decode($_POST["gform"], true);
$questions = $json["questions"];
//var_dump($questions);
//echo json_last_error();
//echo json_last_error_msg();

echo 
'<form id="form" class="form" method="POST">
    <div>
        <div class="description-wrapper">
            <div class="input-title">
                <p class="form-question">' . $json["form_title"] . '</p> 
            </div>
            <div> <p class="form-question">' . $json["form_description"] . '</p> </div>
        </div>
        <div id="containter">';
        $i=0;
        foreach($questions as $question) {
            echo '
            <div class="description-wrapper">
            <p class="form-question">' . $question["stem"] . '</p>
            <input id=question_' . $i . ' name=question_' . $i . ' style="" type="' . $question["input"] . '">
            </input>
            </div>';
            $i++;
        }
        echo '</div>
    </div>
</form>';

?>
<!-- <input type="button">
<input type="checkbox">
<input type="color">
<input type="date">
<input type="datetime-local">
<input type="email">
<input type="file">
<input type="hidden">
<input type="image">
<input type="month">
<input type="number">
<input type="password">
<input type="radio">
<input type="range">
<input type="reset">
<input type="search">
<input type="submit">
<input type="tel">
<input type="text">
<input type="time">
<input type="url">
<input type="week"> -->
             <!-- <' . $question["html_tag"] . '> -->