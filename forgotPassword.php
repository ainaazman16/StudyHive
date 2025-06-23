<?php
session_start();
require("connect.php");

$msg = "";
$showForm = true;
$showLoginButton = false;

if (isset($_POST['reset'])) {
    $username = $_POST['username'] ?? '';
    $newpass = $_POST['new_password'] ?? '';
    $confirmpass = $_POST['confirm_password'] ?? '';

    if ($newpass !== $confirmpass) {
        $msg = "❌ Passwords do not match.";
    } else {
        // Check if username exists
        $check = $conn->prepare("SELECT * FROM user WHERE user_Name = ?");
        if (!$check) {
            $msg = "Prepare failed: " . $conn->error;
        } else {
            $check->bind_param("s", $username);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows === 1) {
                // Username found — reset password
                $hashed = password_hash($newpass, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE user SET password = ? WHERE user_Name = ?");
                if (!$update) {
                    $msg = "Prepare failed: " . $conn->error;
                } else {
                    $update->bind_param("ss", $hashed, $username);
                    if ($update->execute()) {
                        $msg = "✅ Password updated successfully.";
                        $showForm = false;
                        $showLoginButton = true;
                    } else {
                        $msg = "❌ Failed to update password.";
                    }
                }

            } else {
                $msg = "❌ Username not found.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <style>
    body {
      font-family: Arial;
      background-color: #f8f2f9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .box {
      background: #fff0ff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      width: 400px;
      text-align: center;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    button {
      background-color: #660066;
      color: white;
      padding: 10px 20px;
      margin-top: 15px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .msg {
      margin-top: 15px;
      font-weight: bold;
      color: #4b004b;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Password</h2>
    <div class="msg"><?= $msg ?></div>

    <?php if ($showForm): ?>
      <form method="post">
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit" name="reset">Reset Password</button>
      </form>
    <?php endif; ?>

    <?php if ($showLoginButton): ?>
      <form action="loginPage.php" method="get">
        <button type="submit">Return to Login</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
