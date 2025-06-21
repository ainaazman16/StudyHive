<?php
session_start();
include('connect.php'); // must define $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['user_Name'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check for empty fields
    if (empty($username) || empty($password)) {
        echo "<script>
            alert('Please fill in both username and password.');
            window.location.href = 'index.php';
        </script>";
        exit;
    }

    // Check if username exists
    $stmt = $conn->prepare("SELECT user_ID, user_Fname, email, user_Name, password, role FROM `user` WHERE user_Name = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>
            alert('Username does not exist. You need to sign up first.');
            window.location.href = 'signupPage.php';
        </script>";
        exit;
    }

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_Name'] = $user['user_Name'];
        echo "<script>
            window.location.href = 'homePage.php';
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Incorrect password. Please try again.');
            window.location.href = 'index.php';
        </script>";
        exit;
    }

    $stmt->close();
}
?>