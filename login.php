<?php
session_start();
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['user_Name']) || empty($_POST['password'])) {
        echo "<script>alert('Please enter both username and password.'); window.location='loginPage.php';</script>";
        exit();
    }

    $username = $_POST['user_Name'];
    $password = $_POST['password'];

    // Prevent login for deactivated users
    $sql = "SELECT * FROM user WHERE user_Name = ? AND role != 'deactivated'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_ID'] = $user['user_ID'];
            $_SESSION['user_Name'] = $user['user_Name'];
            $_SESSION['user_Fname'] = $user['user_Fname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if (isset($_POST['remember'])) {
                setcookie("remember_username", $user['user_Name'], time() + (7 * 24 * 60 * 60), "/");
                setcookie("remember_password", base64_encode($_POST['password']), time() + (7 * 24 * 60 * 60), "/");
            } else {
                setcookie("remember_username", "", time() - 3600, "/");
                setcookie("remember_password", "", time() - 3600, "/");
            }

            if ($user['role'] === 'admin') {
                header("Location: adminDashboard.php");
            } else {
                header("Location: homePage.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location='loginPage.php';</script>";
        }
    } else {
        echo "<script>alert('User does not exist or is deactivated. Please contact admin.'); window.location='loginPage.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
