<?php
require("connect.php");

$msg = "";
$showResetForm = false;

if (isset($_POST['check_username'])) {
    $username = $_POST['username'];

    $sql = "SELECT * FROM user WHERE user_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $msg = "✅ Username found. Please reset your password.";
        $showResetForm = true;
    } else {
        $msg = "❌ Username not found.";
    }
}

if (isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $newpass = $_POST['new_password'];
    $confirmpass = $_POST['confirm_password'];

    if ($newpass !== $confirmpass) {
        $msg = "❌ Passwords do not match.";
        $showResetForm = true;
    } else {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET password = ? WHERE user_Name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed, $username);

        if ($stmt->execute()) {
            $msg = "✅ Password has been updated successfully.";
            $showResetForm = false;
        } else {
            $msg = "❌ Error updating password.";
            $showResetForm = true;
        }
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
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
    }

    .box {
      background: #ffe0ff;
      padding: 40px 30px;
      border-radius: 20px;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      color: #660066;
      margin-bottom: 25px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
      color: #660066;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1px solid #ccc;
      background-color: #f7f7f7;
    }

    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background-color: #cc66cc;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
      margin-top: 15px;
    }

    input[type="submit"]:hover {
      background-color: #b94cb9;
    }

    .msg {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
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
    <section?>
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
        <a class="back-btn" href="loginPage.php">← Back</a>
  <div class="container">
    <div class="box">
      <h2>Reset Password</h2>

      <div class="msg"><?= $msg ?></div>

      <?php if (!$showResetForm): ?>
        <form method="POST">
          <div class="form-group">
            <label><h4>Check Your username before resetting your password:</h4></label>
            <label for="username">Username:</label>
            <input type="text" name="username" required placeholder="Enter your username">
          </div>
          <input type="submit" name="check_username" value="Check Username">
        </form>
      <?php endif; ?>

      <?php if ($showResetForm): ?>
        <form method="POST">
          <input type="hidden" name="username" value="<?= htmlspecialchars($_POST['username']) ?>">
          <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required placeholder="New Password">
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required placeholder="Confirm Password">
          </div>
          <input type="submit" name="reset_password" value="Reset Password">
        </form>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php include('footer.php');?>
</body>
</html>
