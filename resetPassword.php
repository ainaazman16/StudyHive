<?php
require("connect.php");

$token = $_GET['token'] ?? '';

$stmt = $conn->prepare("SELECT * FROM user WHERE reset_token=? AND token_expiry > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newpass = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        if ($newpass !== $confirm) {
            echo "❌ Passwords do not match.";
        } else {
            $hashed = password_hash($newpass, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE user SET password=?, reset_token=NULL, token_expiry=NULL WHERE reset_token=?");
            $update->bind_param("ss", $hashed, $token);
            if ($update->execute()) {
                echo "✅ Password successfully reset. You may now <a href='loginPage.php'>login</a>.";
            } else {
                echo "❌ Failed to update password.";
            }
        }
    }
} else {
    echo "❌ Invalid or expired token.";
}
?>

<form method="POST">
  <label>New Password:</label>
  <input type="password" name="new_password" required>
  <label>Confirm Password:</label>
  <input type="password" name="confirm_password" required>
  <input type="submit" value="Reset Password">
</form>
