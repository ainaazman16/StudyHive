<?php
require("connect.php");

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM user WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $update = $conn->prepare("UPDATE user SET reset_token=?, token_expiry=? WHERE email=?");
        $update->bind_param("sss", $token, $expiry, $email);
        $update->execute();

        $resetLink = "http://yourdomain.com/resetPassword.php?token=$token";

        // Send email (simplified)
        $subject = "Password Reset - Study Hive";
        $message = "Click the link to reset your password: $resetLink";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "✅ Reset link sent. Please check your email.";
        } else {
            echo "❌ Failed to send email.";
        }
    } else {
        echo "❌ Email not found.";
    }
}
?>
