### 1. In the root folder create env.php with the following content:
```
<?php
$_ENV["SERVERNAME"] = "...";
$_ENV["USERNAME"] = "...";
$_ENV["PASSWORD"] = "...";
$_ENV["DATABASE"] = "...";
```

### 2. Start MySQL and Apache

#### 2.1. Open the sql terminal and run this script:
```
CREATE SCHEMA IF NOT EXISTS webProject DEFAULT CHARACTER SET utf8 ;
USE webProject ;


-- -----------------------------------------------------
-- Table webProject.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS webProject.`users` (
  email VARCHAR(255) NOT NULL,
  fk VARCHAR(255) NULL DEFAULT NULL,
  name VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (email));


-- -----------------------------------------------------
-- Table webProject.`forms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS webProject.`forms` (
  id INT(11) NOT NULL AUTO_INCREMENT,
  title TEXT NULL DEFAULT NULL,
  created_by VARCHAR(255) NOT NULL,
  description TEXT NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT forms_ibfk_1 FOREIGN KEY (created_by) REFERENCES webProject.`users` (email));


-- -----------------------------------------------------
-- Table webProject.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS webProject.`questions` (
  id INT(11) NOT NULL AUTO_INCREMENT,
  form_id INT(11) NULL DEFAULT NULL,
  stem TEXT NULL DEFAULT NULL,
  type TINYINT(1) NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT questions_ibfk_1 FOREIGN KEY (form_id) REFERENCES webProject.`forms` (id));


-- -----------------------------------------------------
-- Table webProject.`answers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS webProject.`answers` (
  id INT(11) NOT NULL AUTO_INCREMENT,
  question_id INT(11) NULL DEFAULT NULL,
  answer TEXT NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT answers_ibfk_1 FOREIGN KEY (question_id) REFERENCES webProject.`questions` (id));


-- -----------------------------------------------------
-- Table webProject.`form_results`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS webProject.`form_results` (
  id INT(11) NOT NULL AUTO_INCREMENT,
  answer TEXT NULL DEFAULT NULL,
  answered_by VARCHAR(255) NULL DEFAULT NULL,
  question_id INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT form_results_ibfk_1 FOREIGN KEY (answered_by) REFERENCES webProject.`users` (email),
  CONSTRAINT form_results_ibfk_2 FOREIGN KEY (question_id) REFERENCES webProject.`questions` (id));


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
```

### 3. Configure the Apache port to be 8080

#### 3.1. Create directories - 'answer' and 'generated'

#### 3.2. Go to `http://localhost:8080/``

### 4. A form can be generated when you click 'Create new form'. You can upload a settings.json file and a form.txt file.

### EXAMPLE FILES FOR GENERARTION:

#### Example txt:
```
stem=what do you like?,tag=input,type=text,name=input_name,label=input_label;tag=img,label=img_label,src=assets/logo.png
stem=my question is this,tag=textarea,name=input_area,label=my text area
stem=choose from the following,tag=input,type=radio,name=options,label=option one,value=one;tag=input,type=radio,name=options,label=option 2,value=two
stem=choose date,tag=input,type=date,name=date,label=this is a date
stem=choose month,tag=input,type=month,name=month,label=this is month
stem=choose datetime,tag=input,type=datetime-local,name=local,label=this is datetime
stem=choose file,tag=input,type=file,name=file
```

#### Example json:

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