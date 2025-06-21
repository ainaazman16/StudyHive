<?php
session_start();
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user_Name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE user_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // ✅ Set full user session
            $_SESSION['user_ID'] = $user['user_ID'];
            $_SESSION['user_Name'] = $user['user_Name'];
            $_SESSION['user_Fname'] = $user['user_Fname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // if you use admin/user roles

            // ✅ Remember Me Cookie (optional)
            if (isset($_POST['remember'])) {
                setcookie("remember_username", $user['user_Name'], time() + (7 * 24 * 60 * 60), "/");
            } else {
                setcookie("remember_username", "", time() - 3600, "/");
            }

            // ✅ Redirect to homepage
            header("Location: homePage.php");
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location='loginPage.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location='loginPage.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
