<?php
require("connect.php");

$nm = $_POST['user_Fname'];
$eml = $_POST['email'];
$username = $_POST['user_Name'];
$pass = $_POST['password'];

$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

// Handle profile picture upload
$targetDir = "uploads/";
$profilePicName = basename($_FILES["profile_picture"]["name"]);
$targetFilePath = $targetDir . $profilePicName;
$imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

// Only allow certain file formats
$allowedTypes = array("jpg", "jpeg", "png", "gif");

if (in_array($imageFileType, $allowedTypes)) {
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
        // Insert data into database
        $sql = "INSERT INTO user (user_Fname, email, user_Name, password, profile_picture)
                VALUES ('$nm', '$eml', '$username', '$hashedPassword', '$profilePicName')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully!";
            echo "<meta http-equiv='refresh' content='3;URL=index.php'>";
        } else {
            echo "Database Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
} else {
    echo "Only JPG, JPEG, PNG & GIF files are allowed.";
}

$conn->close();
?>
