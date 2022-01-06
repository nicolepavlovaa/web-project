<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <title>Register</title>
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
          <label class="form__label" htmlFor="username">Username</label>
          <input type="text" class="input" name="username" id="username" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="name">Name</label>
          <input type="text" class="input" name="name" id="name" />
        </div>
      </div>
      <div class=" fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="fn">FN</label>
          <input type="text" class="input" name="fn" id="fn" />
        </div>
      </div>
      <div class="fieldset">
        <div class="form-group">
          <label class="form__label" htmlFor="password">Password</label>
          <input type="password" class="input" name="password" id="password" />
        </div>
      </div>
    </div>
    <div class="buttons">
      <button type="submit" name="submit" value="submit" class="btn">Register</button>
    </div>
  </form>
  <span>
  </span>
</body>

</html>