### Installation

#### 1. In the root folder create env.php with the following content:
```
<?php
$_ENV["SERVERNAME"] = "...";
$_ENV["USERNAME"] = "...";
$_ENV["PASSWORD"] = "...";
$_ENV["DATABASE"] = "webProject";
```

#### 2. Start MySQL and Apache

#### 3. Open the sql terminal and run the following script:
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

#### 4. Configure the Apache port to be 8080

#### 5. Create directories - 'answer' and 'generated'

#### 6. Go to `http://localhost:8080/index`

#### 7. A form can be generated when you click 'Create new form'. You can upload a settings.json file and a form.txt file.


### Instructions for form generation:

- You need two files, one of them is a .txt file, the other one is a .json file
- The .json file is used to configure the form settings. You need the following fields in it: form_title, form_description, creator, created; creator and created are automatically added to the .json file when 'Generate' is clicked. Later the creator field will be used to grant you access to edit the form. The password field can be added if you want to protect the form and only people who enter the right password will be able to fill it.
- The .txt file contains the form content. Use the following syntax:
  - new line to separate one 'part' of the form from the others
  - in each 'part' of the form you can have multiple html elements, you can separate them with ;
  - each html element must have the field tag, without it it won't be a valid element; if the element tag is input, type and name are required - type can be text, numbe and all other valid html input types, name is the name you'll want later to be used when the form results are submitted by a user. It's important that if you have radio butttons and you want them to be in the same form group, they need to have the same name.
  - Other possible fields are: label, stem, src, href, value
    - Stem can be considered to be the question(or title) in a section, depends on how you structure your form.
    - Label can be added to an html element in order to give some information what is it about
    - src can be used for a tag=img for example or other tags that can accept src as an attribute
    - href for tags that can accept href as an attribute
    - style to override the element's current style/class
    - if the tag is p, the value is put between the opening and closing tag, otherwise it's put as a value of the element's attribute 'value'
  - All fields concerning the same html element / tag can be separated using ,
  - If you want a ; or , to appear in the stem, you can put {} around the stem's value. 

##### Example txt:
```
stem=what do you like?,tag=input,type=text,name=input_name,label=input_label;tag=img,label=img_label,src=assets/logo.png
stem=my question is this,tag=textarea,name=input_area,label=my text area
stem=choose from the following,tag=input,type=radio,name=options,label=option one,value=one;tag=input,type=radio,name=options,label=option 2,value=two
stem=choose date,tag=input,type=date,name=date,label=this is a date
stem=choose month,tag=input,type=month,name=month,label=this is month
stem=choose datetime,tag=input,type=datetime-local,name=local,label=this is datetime
stem=choose file,tag=input,type=file,name=file,label=mylabel
```

##### Example json:

```
{
  "form_title": "Title",
  "form_description": "Description",
  "creator": "npavlova@gmail.com",
  "created": "Friday 28th of January 2022 01:20:25 AM",
  "password": "12345"
}
```

### Added in last version:
- Display form description, date last edited and creator in index page.
- The creator of a form has the ability to edit the form.
- A user can be asked to enter a password in order to open a form.
- 'results' and 'generated' folders are now private.