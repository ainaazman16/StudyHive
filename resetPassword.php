<?php
session_start();
require("connect.php");
$msg = "";
$showForm = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE reset_token = ? AND token_expiry > NOW()");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $token);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $showForm = true;
    } else {
        $msg = "❌ Invalid or expired token.<br>Debug: Token = $token";
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
        $update = $conn->prepare("UPDATE user SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
        $update->bind_param("ss", $hashed, $token);

        if ($update->execute()) {
            $msg = "✅ Password updated successfully.";
            $showForm = false;
            $showLoginButton = true;
        } else {
            $msg = "❌ Failed to update password.";
            $showForm = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Reset Password</title></head>
<body>
  <h2>Reset Password</h2>
  <div><?= $msg ?></div>

  <?php if ($showForm): ?>
    <form method="post">
      <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
      <input type="password" name="new_password" placeholder="New Password" required><br>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
      <button type="submit" name="reset">Reset Password</button>
    </form>
  <?php endif; ?>

  <!-- ✅ This will always check whether to show login button, even if $showForm is false -->
  <?php if (isset($showLoginButton) && $showLoginButton): ?>
    <form action="loginPage.php" method="get">
      <button type="submit" style="margin-top: 20px; padding: 10px 20px; background-color: #660066; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
        Return to Login
      </button>
    </form>
  <?php endif; ?>
</body>
</html>

