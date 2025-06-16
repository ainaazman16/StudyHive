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
        background-color:#ec97ec;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
        text-align: center;
        width: 400px;
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

      .navbar ul {
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
      }

      .navbar li {
        margin-left: 10px;
      }

      .navbar a {
        text-decoration: none;
        color: white;
        padding: 14px 16px;
        display: block;
        font-size: 14px;
        font-weight: bold;
        text-transform: uppercase;
      }

      .navbar a:hover {
        background-color: #990099;
      }

      .topic {
        background-color: #ec97ec;
        font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
        font-size: 230%;
        text-decoration: none;
        color: #5e1b5e;
        text-align: center;
        height: 400px;
        padding-top: 5px;
        padding-bottom: 5px;
      }

      .topic img {
        width: 245px;
        height: auto;
        margin-bottom: 5px;
        margin-top: 5px;
      }

      .content {
        background-color: white;
        padding: 40px 20px;
        display: flex;
        justify-content: center;
      }

      .title {
        display: flex;
        max-width: 1000px;
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
      }

      .title-box {
        background-color: #f7d7f7;
        color: #660066;
        padding: 30px;
        width: 35%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
      }

      .title-box span {
        color: #3d0d3d;
      }

      .features {
        padding: 30px;
        width: 65%;
      }

      .features strong {
        display: block;
        margin-top: 20px;
        font-size: 18px;
        color: #660066;
      }

      .features p {
        font-size: 16px;
        color: #333;
        line-height: 1.6;
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