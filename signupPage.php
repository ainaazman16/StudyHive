<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sign Up</title>
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
      <td colspan="3"><label>
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
