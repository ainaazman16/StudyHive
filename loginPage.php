<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
    body {
      justify-content: center;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #ea8dde; 
      color: white;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: #4b004b;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      text-align: center;
      width: 400px;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: white;
    }

    form {
      display: flex;
      flex-direction: column;
      align-items: center; 
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      margin: 10px auto;
      display: block;
    }

    input[type="submit"] {
      width: 80%;
      padding: 12px;
      background-color: #a000a0; 
      color: white;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      transition: 0.3s ease;
      margin: 10px auto;
      display: block;
    }

    input[type="submit"]:hover {
      background-color: #d147d1;
    }

    .center a {
      color: #ffccff;
      text-decoration: none;
    }

    .center a:hover {
      text-decoration: underline;
    }

    @media (max-width: 500px) {
      .login-container {
        width: 90%;
        padding: 25px;
      }
    }
  </style>
</head>
<body>
    <section>
  <div class="container">
  <div class="login-container">
    <h2>Welcome Back! Please enter your username and password.</h2>
    <form action="login.php"<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="POST">
      <table>
        <tr>
          <th>Username : </th>
          <td><input type="text" name="user_Name"></td>
        </tr>
        <tr>
          <th>Password : </th>
          <td><input type="password" name="password"></td>
        </tr>
        <tr>
          <td colspan="2">
            <input type="submit" value="Submit" name="submit">
          </td>
        </tr>
      </table>
    </form>
	<p class="center"><b>New user? <a href="signupPage.php">Sign-up now.</a></b></p>
  </div>
  </div>
</section>
</body>
</html>