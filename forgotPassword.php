<?php
require("connect.php");

$msg = "";
$showResetForm = false;

if (isset($_POST['check_email'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM user WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $msg = "Email found. Please enter your new password.";
        $showResetForm = true;
    } else {
        $msg = "❌ Email not found in the system.";
    }
}

if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];
    $newpass = $_POST['new_password'];

    $hashedPassword = password_hash($newpass, PASSWORD_DEFAULT);
    $sql = "UPDATE user SET password=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        $msg = "✅ Password has been updated successfully.";
        $showResetForm = false;
    } else {
        $msg = "❌ Error updating password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
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

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      color: #660066;
      margin-bottom: 6px;
    }

    .form-actions {
      text-align: center;
      margin-top: 20px;
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
    input[type="email"],
    input[type="password"] {
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
    .msg {
      margin-top: 15px;
      color: #330033;
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
          <a class="back-btn" href="loginPage.php">← Back</a>
  <div class="box">
    <h2>Reset Your Password</h2>

    <?php if (!$showResetForm): ?>
      <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <input type="submit" name="check_email" value="Check Email">
      </form>
    <?php endif; ?>

    <?php if ($showResetForm): ?>
      <form method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_POST['email']) ?>">
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="submit" name="reset_password" value="Reset Password">
      </form>
    <?php endif; ?>

    <div class="msg"><?= $msg ?></div>
  </div>
  </section>
</body>
</html>
