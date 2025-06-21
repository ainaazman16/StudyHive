<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .box {
      background: #ffe0ff;
      padding: 40px;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      text-align: center;
    }

    h2 {
      color: #660066;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    input[type="submit"] {
      margin-top: 20px;
      padding: 12px 20px;
      border: none;
      background-color: #cc66cc;
      color: white;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #b94cb9;
    }

    a {
      display: block;
      margin-top: 20px;
      color: #660066;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Your Password</h2>
    <form action="sendReset.php" method="POST">
      <input type="email" name="email" placeholder="Enter your registered email" required>
      <input type="submit" value="Send Reset Link">
    </form>
    <a href="loginPage.php">← Back to Login</a>
  </div>
</body>
</html>
