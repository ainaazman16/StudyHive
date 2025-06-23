<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require("connect.php");

$msg = "";
$showForm = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE reset_token=? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $showForm = true;
    } else {
        $msg = "❌ Invalid or expired token.";
    }
}

if (isset($_POST['reset'])) {
    $token = $_POST['token'];
    $newpass = $_POST['new_password'];
    $confirmpass = $_POST['confirm_password'];

    if ($newpass !== $confirmpass) {
        $msg = "❌ Passwords do not match.";
        $showForm = true;
    } else {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE user SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?");
        $update->bind_param("ss", $hashed, $token);

        if ($update->execute()) {
            $msg = "✅ Password updated successfully.";
            $showForm = false;
        } else {
            $msg = "❌ Failed to update password.";
            $showForm = true;
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
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .box {
      background: #ffe0ff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    input[type="password"], input[type="submit"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #cc66cc;
      color: white;
      border: none;
      font-weight: bold;
      cursor: pointer;
    }
    .msg {
      margin-top: 10px;
      color: #330033;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Password</h2>
    <div class="msg"><?= $msg ?></div>

    <?php if ($showForm): ?>
      <form method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <input type="submit" name="reset" value="Reset Password">
      </form>
    <?php endif; ?>
  </div>
  <?php include('footer.php'); ?>
</body>
</html>
