<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    body {
      background-color: #660066;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background-color: #ec97ec;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      text-align: center;
      width: 400px;
    }

    .login-container label {
      display: block;
      margin-top: 15px;
      text-align: left;
      font-weight: bold;
      color: #3d0d3d;
      margin-left: 10%;
    }

    input[type="text"],
    input[type="password"] {
      width: 80%;
      padding: 10px;
      margin-top: 5px;
      border: none;
      border-radius: 5px;
      display: block;
      margin-left: auto;
      margin-right: auto;
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
      margin: 20px auto 10px auto;
      display: block;
    }

    input[type="submit"]:hover {
      background-color: #d147d1;
    }

    .center a {
      color: rgb(127, 61, 127);
      text-decoration: none;
    }

    .center a:hover {
      text-decoration: underline;
    }

    .navbar {
      background-color: #660066;
      position: sticky;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 10px;
      height: 60px;
    }

    .navbar .logo {
      height: 60px;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <img src="images/whiteLogo.png" alt="Logo" class="logo">
  </div>

  <section>
    <div class="container">
      <div class="login-container">
        <h2>Welcome Back! Please enter your username and password.</h2>
        <form action="login.php" method="POST">
          <label for="username">Username :</label>
          <input type="text" name="user_Name" id="username">

          <label for="password">Password :</label>
          <input type="password" name="password" id="password">

          <input type="submit" value="Submit" name="submit">
        </form>
        <p class="center"><b>New user? <a href="signupPage.php">Sign-up now.</a></b></p>
      </div>
    </div>
  </section>
</body>
</html>
