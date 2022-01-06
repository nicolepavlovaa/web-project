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