<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $allowedTypes = ["pdf", "jpg", "jpeg", "png"];
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        header("Location: uploadPage.php?error=" . urlencode("Invalid file type. Only PDF, JPG, JPEG, PNG allowed."));
        exit();
    }

    $targetFile = $targetDir . time() . "_" . $fileName;

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        header("Location: uploadPage.php?error=" . urlencode("Failed to move uploaded file."));
        exit();
    }

    // Example insert logic (simplified)
    $noteName = $_POST['note_name'] ?? '';

    $sql = "INSERT INTO notes (note_Name, file_path, file_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $noteName, $targetFile, $fileType);

    if ($stmt->execute()) {
        header("Location: uploadPage.php?success=1");
        exit();
    } else {
        header("Location: uploadPage.php?error=" . urlencode("Database error: " . $stmt->error));
        exit();
    }
} else {
    header("Location: uploadPage.php?error=" . urlencode("No file uploaded or invalid request."));
    exit();
}
?>
