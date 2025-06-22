<?php
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
</head>
<body>
  <h2>Reset Password</h2>

  <div><?= $msg ?></div>

  <?php if ($showForm): ?>
    <form method="POST">
      <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <input type="submit" name="reset" value="Reset Password">
    </form>
  <?php endif; ?>
</body>
</html>
