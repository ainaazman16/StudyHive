<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SignUp - Study Hive</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
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

    .back-btn {
      display: inline-block;
      margin-top: 20px;
      margin-left: 20px;
      font-size: 16px;
      color: #660066;
      text-decoration: none;
      font-weight: bold;
    }

    .container {
      width: 100%;
      display: flex;
      justify-content: center;
      padding: 50px 20px;
      box-sizing: border-box;
    }

    .signup-container {
      width: 100%;
      display: flex;
      justify-content: center;
    }

    .signup-box {
      background-color: #ffe0ff;
      width: 50vw; 
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      box-sizing: border-box;
    }

    .signup-box h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #330033;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      color: #660066;
      margin-bottom: 6px;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="number"],
    .form-group input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #f7f7f7;
    }

    .gender-options {
      display: flex;
      gap: 15px;
      margin-top: 5px;
    }

    .gender-options label {
      font-weight: normal;
      color: #330033;
    }

    .form-actions {
      text-align: center;
      margin-top: 20px;
    }

    .form-actions input[type="submit"],
    .form-actions input[type="reset"] {
      padding: 10px 20px;
      margin: 5px;
      background-color: #cc66cc;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form-actions input[type="submit"]:hover,
    .form-actions input[type="reset"]:hover {
      background-color: #b94cb9;
    }

    .footer {
      background-color: #660066;
      color: white;
      text-align: center;
      padding: 10px;
      margin-top: 40px;
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
    <img src="images/whiteLogo.png" alt="logo" class="logo">
    <h2>WELCOME TO STUDY HIVE</h2>
  </div>

  <a class="back-btn" href="loginPage.php">← Back</a>

  <div class="container">
    <div class="signup-container">
      <form id="form1" name="form1" method="post" action="user.php" enctype="multipart/form-data">
        <div class="signup-box">
          <h2>Create Your Account</h2>

          <div class="form-group">
            <label for="user_Fname">Full Name:</label>
            <input type="text" name="user_Fname" id="user_Fname" required />
          </div>

          <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required />
          </div>

          <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="number" name="phone" id="phone" required />
          </div>

          <div class="form-group">
            <label>Gender:</label>
            <div class="gender-options">
              <label><input type="radio" name="gender" value="female" required /> Female</label>
              <label><input type="radio" name="gender" value="male" /> Male</label>
            </div>
          </div>

          <div class="form-group">
            <label for="user_Name">Username:</label>
            <input type="text" name="user_Name" id="user_Name" required />
          </div>

          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required />
          </div>

          <div class="form-group">
            <label for="picture">Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" required />
          </div>

          <div class="form-actions">
            <input type="submit" name="submit" value="REGISTER" />
            <input type="reset" name="reset" value="CLEAR FORM" />
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
</body>
</html>
