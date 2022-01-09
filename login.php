<?php
   include("config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      //$myemail = mysqli_real_escape_string($db,$_POST['email']);
      //$mypassword = mysqli_real_escape_string($db,$_POST['password']);
      $myemail = trim($_POST["email"]);
      $mypassword = trim($_POST["password"]);
      echo $myemail, $mypassword;
      
      //$sql = "SELECT password FROM users WHERE email = ?";
      $sql = "SELECT password FROM users WHERE email = '".$myemail."'";
      $stmt = $db->query($sql);

      var_dump($stmt);
      //$stmt->execute([$myemail]);
      //$result = $stmt->store_result();
      $result = $stmt->fetch_all(MYSQLI_ASSOC);
      $password = $result[0]["password"];
      var_dump($password);
      

      // If result matched $myusername and $mypassword, table row must be 1 row
		
       if(password_verify($mypassword, $password)) {
          $_SESSION['login_user'] = $myemail;
         
          header("location: forms.php");
       }else {
          $error = "Your Login Name or Password is invalid";
       }
    }
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link href="./styles.css" rel="stylesheet" />
</head>

<body class="page">
  <form id="form" class="form" method="POST">
    <div id="container" class="container">
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="email">Email</label>
          <input type="text" class="input" name="email" id="email" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="password">Password</label>
          <input type="password" class="input" name="password" id="password" />
        </div>
      </div>
      <div class="buttons-and-text">
        <button type="submit" name="submit" value="submit" class="btn">Login</button>
        <p>Not yet a member? <a href="register.php">Register</a></p>
      </div>
  </form>
  <span>
  </span>
</body>

</html>