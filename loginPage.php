<?php
session_start();
if(isset($_SESSION['username']))
{
  $_SESSION = array();
  session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Study Hive</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    .header {
      background-color: #cc66cc;
      padding: 30px;
      text-align: center;
      color: white;
    }

    .header h1 {
      margin: 0;
      font-size: 32px;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px 0;
    }

    .login-box {
      background-color: #ffe0ff;
      width: 400px;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    .login-box h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #330033;
    }

    .login-box label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #660066;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #f7f7f7;
    }

    .login-box .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      color: #660066;
      margin-bottom: 20px;
    }

    .login-box input[type="checkbox"] {
      margin-right: 5px;
    }

    .login-box input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #cc66cc;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-box input[type="submit"]:hover {
      background-color: #b94cb9;
    }

    .login-box .signup-link {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }

    .login-box .signup-link a {
      color: #660066;
      text-decoration: none;
      font-weight: bold;
    }

    .login-box .or {
      text-align: center;
      margin: 20px 0;
      color: #999;
    }

    .login-box .social-icons {
      text-align: center;
    }

    .social-icons img {
      width: 30px;
      margin: 0 10px;
      cursor: pointer;
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

      .back-btn {
        display: inline-block;
        margin-top: 20px; /* Adjust the value as needed */
        margin-left: 20px;
        font-size: 16px;
        color: #660066;
        text-decoration: none;
        font-weight: bold;
      } 
      

      
  </style>
</head>
<body>
  <section>
    <div class="navbar">
          <img src="images/whiteLogo.png" alt="Logo" class="logo">
          <ul>
          <li><a href="#namafile">Features</a></li>
          <li><a href="#namafile">Help</a></li>
          <li><a href="#namafile">Contact Us</a></li>
          <li><a href="loginPage.php">Login</a></li>
          <li><a href="signupPage.php">Sign Up</a></li>
        </ul>
        </div>
        <div class="topic">
          <image src="images/whiteLogo.png" alt="logo" class="logo"></image>
          <h2>WELCOME TO STUDY HIVE</h2>
        </div>
        <a class="back-btn" href="index.php">← Back</a>
  <div class="container">
    <div class="login-box">
      <h2>Log in</h2>
      <form action="login.php" method="POST">
        <label for="email">Email</label>
        <input type="text" id="email" name="user_Name" placeholder="Enter your email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password">

        <div class="remember-forgot">
          <label><input type="checkbox"> Remember me</label>
          <a href="#">Forgot password?</a>
        </div>

        <input type="submit" value="Sign in" name="submit">
      </form>

      <div class="signup-link">
        Don’t have an account? <a href="signupPage.php">Sign up</a>
      </div>

      <div class="or">— OR —</div>

      <div class="social-icons">
        <img src="https://img.icons8.com/color/48/google-logo.png" alt="Google">
        <img src="https://img.icons8.com/color/48/facebook-new.png" alt="Facebook">
        <img src="https://img.icons8.com/ios-filled/50/github.png" alt="GitHub">
      </div>
    </div>
  </div>
</section>
  <?php
    include('footer.php');
    ?>
</body>
</html>
