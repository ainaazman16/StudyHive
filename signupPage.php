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
<div class="container">
<div class="login-container">
<form id="form1" name="form1" method="post" action="user.php" enctype="multipart/form-data">
  <table width="51%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3">PLEASE FILL THE FORM BELOW</td>
    </tr>
    <tr>
      <td width="30%">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>Name:</td>
      <td colspan="2"><label>
        <input type="text" name="user_Fname" id="user_Fname" />
      </label></td>
    </tr>
    <tr>
      <td>Email</td>
      <td colspan="2"><label>
        <input type="email" name="email" id="email" />
      </label></td>
    </tr>
    <tr>
      <td>Phone Number:</td>
      <td colspan="2"><label>
        <input type="number" name="phone" id="phone" />
      </label></td>
    </tr>
    <tr>
      <td>Gender:</td>
      <td colspan="2"><label>
        <input type="radio" name="gender" id="radio" value="female" />
      FEMALE 
      <input type="radio" name="gender" id="radio2" value="male" />
      MALE</label></td>
    </tr>
    <tr>
      <td>Username:</td>
      <td colspan="2"><label>
        <input type="text" name="user_Name" id="user_Name" />
      </label></td>
    </tr>
     <tr>
      <td>Password:</td>
      <td colspan="2"><label>
        <input type="password" name="password" id="password" />
      </label></td>
    </tr>
    <tr>
      <td colspan="3" style="text-align: center;"><label>
        <input type="submit" name="submit" id="submit" value="REGISTER" />
        <input type="reset" name="reset" id="reset" value="CLEAR FORM" />
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
</form>
</div>
</div>
</section>
</body>
</html>
