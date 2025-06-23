<?php
session_start();
include("connect.php");

if ($_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

if (isset($_POST['user_ID'])) {
    $id = $_POST['user_ID'];

    $result = $conn->query("SELECT role FROM user WHERE user_ID = $id");
    $current = $result->fetch_assoc();

    $newRole = ($current['role'] === 'user') ? 'admin' : 'user';
    $conn->query("UPDATE user SET role = '$newRole' WHERE user_ID = $id");
}

header("Location: adminUsers.php");
exit();
?>
