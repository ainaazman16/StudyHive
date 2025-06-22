<?php
require("connect.php");

$msg = "";

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $update = $conn->prepare("UPDATE user SET reset_token=?, token_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        $resetLink = "http://localhost/StudyHive/resetPassword.php?token=$token";
        $msg = "✅ A reset link has been generated.<br><a href='$resetLink'>Click here to reset your password</a>";
    } else {
        $msg = "❌ Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
</head>
<body>
  <h2>Forgot Password</h2>
  <form method="POST">
    <input type="email" name="email" placeholder="Enter your registered email" required>
    <input type="submit" value="Generate Reset Link">
  </form>
  <div><?= $msg ?></div>
</body>
</html>
