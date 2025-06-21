<?php
session_start();
include('connect.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user_Name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please provide both username and password.";
    } else {
        // Fetch user by username
        $stmt = $conn->prepare("SELECT user_ID, user_Fname, email, user_Name, password, role FROM `user` WHERE user_Name = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Success
                $_SESSION['user_Name'] = $user['user_Name'];

                // Redirect to welcomePage.php for all users
                echo "<script>window.location.href='homePage.php';</script>";
                exit();
            } else {
                $error = "Incorrect password. Please try again.";
                echo "<script>window.location.href='index.php';</script>";
            }
        } else {
            $error = "Username does not exist. Please sign up first.";
            echo "<script>window.location.href='index.php';</script>";
        }

        $stmt->close();
    }

    // Show error via popup
    if (!empty($error)) {
        echo "<script>alert('$error'); window.location.href='index.php';</script>";
        exit();
    }
}
?>