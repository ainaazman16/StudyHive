<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $allowedTypes = ["pdf", "jpg", "jpeg", "png"];
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $originalName = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        header("Location: uploadPage.php?error=" . urlencode("Invalid file type. Only PDF, JPG, JPEG, PNG allowed."));
        exit();
    }

    $storedFileName = time() . "_" . $originalName;
    $targetFile = $targetDir . $storedFileName;

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        header("Location: uploadPage.php?error=" . urlencode("Failed to move uploaded file."));
        exit();
    }

    $noteName = $_POST['note_name'] ?? '';
    $userID = $_SESSION['user_ID'];
    $uploadDate = date("Y-m-d H:i:s");

    // Optional: handle subject/course/uni/etc. if needed
    $sql = "INSERT INTO notes (note_Name, file_path, file_type, user_ID, upload_date, download_count)
            VALUES (?, ?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $noteName, $storedFileName, $fileType, $userID, $uploadDate);

        echo "<pre>";
        echo "userID = $userID\n";
        echo "noteName = $noteName\n";
        echo "file = $storedFileName\n";
        echo "type = $fileType\n";
        echo "uploadDate = $uploadDate\n";
        echo "</pre>";
        exit();


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
