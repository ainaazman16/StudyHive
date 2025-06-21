<?php
session_start();

require("connect.php");

// Capture and sanitize user input
$nm = trim($_POST['user_Fname']);
$eml = trim($_POST['email']);
$username = trim($_POST['user_Name']);
$pass = $_POST['password'];
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

// Profile picture handling
$targetDir = "uploads/";
$profilePicName = basename($_FILES["profile_picture"]["name"]);
$targetFilePath = $targetDir . $profilePicName;
$imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
$allowedTypes = ["jpg", "jpeg", "png", "gif"];

// Validate file type
if (!in_array($imageFileType, $allowedTypes)) {
    echo "<script>alert('Only JPG, JPEG & PNG files are allowed.'); window.history.back();</script>";
    exit();
}

// Check if username already exists
$checkUser = $conn->prepare("SELECT * FROM user WHERE user_Name = ?");
$checkUser->bind_param("s", $username);
$checkUser->execute();
$result = $checkUser->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Username already exists. Please choose another.'); window.history.back();</script>";
    exit();
}
$checkUser->close();

// Attempt to move uploaded file
if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO user (user_Fname, email, user_Name, password, profile_picture)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nm, $eml, $username, $hashedPassword, $profilePicName);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='loginPage.php';</script>";
    } else {
        echo "<script>alert('Database error: {$stmt->error}'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Failed to upload profile picture. Please try again.'); window.history.back();</script>";
}

$conn->close();
?>
