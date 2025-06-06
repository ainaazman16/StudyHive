<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section>
  <div class="container">
  <div class="login-container">
    <h2>Welcome. Please enter your username and password.</h2>
    <form action="login.php"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="POST">
      <table>
        <tr>
          <th>Username:</th>
          <td><input type="text" name="username"></td>
        </tr>
        <tr>
          <th>Password:</th>
          <td><input type="password" name="password"></td>
        </tr>
        <tr>
          <td colspan="2">
            <input type="submit" value="Submit" name="submit">
          </td>
        </tr>
      </table>
    </form>
	<p class="center"><b>New user? <a href="signup.php">Sign-up now..</a></b></p>
  </div>
  </div>
</section>
</body>
</html>