## 1. In the root folder create env.php with the following content:
```
<?php
$_ENV["SERVERNAME"] = "...";
$_ENV["USERNAME"] = "...";
$_ENV["PASSWORD"] = "...";
$_ENV["DATABASE"] = "...";
```

## 2. Start MySQL and Apache 
## 3. Go to `http://localhost:8080/``

## 4. A form can be generated when you click 'Create new form'. You can upload a settings.json file and a form.txt file.

## EXAMPLE FILES FOR GENERARTION:

### Example txt:

stem=what do you like?,tag=input,type=text,name=input_name,label=input_label;tag=img,label=img_label,src=assets/logo.png
stem=my question is this,tag=textarea,name=input_area,label=my text area
stem=choose from the following,tag=input,type=radio,name=options,label=option one,value=one;tag=input,type=radio,name=options,label=option 2,value=two
stem=choose date,tag=input,type=date,name=date,label=this is a date
stem=choose month,tag=input,type=month,name=month,label=this is month
stem=choose datetime,tag=input,type=datetime-local,name=local,label=this is datetime
stem=choose file,tag=input,type=file,name=file

### Example json:

```
{
  "form_title": "Title",
  "form_description": "Description",
  "background_color": "blue",
  "creator": "npavlova@gmail.com",
  "created": "some date",
  "override_answers": true
}
```