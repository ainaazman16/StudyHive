<?php
session_start();
require("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nm = trim($_POST['user_Fname']);
    $eml = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $username = trim($_POST['user_Name']);
    $pass = $_POST['password'];
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // Upload image
    $targetDir = "uploads/";
    $profilePicName = basename($_FILES["profile_picture"]["name"]);
    $targetFilePath = $targetDir . $profilePicName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    // Validate image
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.history.back();</script>";
        exit();
    }

    // Check if username exists
    $checkUser = $conn->prepare("SELECT * FROM user WHERE user_Name = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already exists. Please choose another.'); window.history.back();</script>";
        exit();
    }
    $checkUser->close();


    
    // Save user
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO user (user_Fname, email, phone, gender, user_Name, password, profile_picture)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nm, $eml, $phone, $gender, $username, $hashedPassword, $profilePicName);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href='loginPage.php';</script>";
        } else {
            echo "<script>alert('Database error: {$stmt->error}'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Failed to upload profile picture.'); window.history.back();</script>";
    }

    $conn->close();
}
?>
