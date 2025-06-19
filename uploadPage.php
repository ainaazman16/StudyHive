<?php
session_start();
include("connect.php");

$uploadSuccess = false;
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $fileName = basename($_FILES["file"]["name"]);
        $targetFile = $targetDir . $fileName;

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ["pdf", "jpg", "jpeg", "png"];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                $fileSize = $_FILES["file"]["size"];
                $noteName = $_POST['fileName'];
                $uploadDate = date("Y-m-d H:i:s");
                $userID = $_SESSION['user_ID'] ?? null;

                $sql = "INSERT INTO notes (note_Name, file_type, file_size, upload_date, download_count, user_ID)
                        VALUES (?, ?, ?, ?, 0, ?)";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssisi", $noteName, $fileType, $fileSize, $uploadDate, $userID);

                if ($stmt->execute()) {
                    $uploadSuccess = true;
                } else {
                    $errorMsg = "Database error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $errorMsg = "Failed to upload file.";
            }
        } else {
            $errorMsg = "Invalid file type. Only PDF, JPG, JPEG, PNG allowed.";
        }
    } else {
        $errorMsg = "No file uploaded or an error occurred.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UPLOAD NOTES</title>
  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    .topic {
      background-color: #ec97ec;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      font-size: 230%;
      text-decoration: none;
      color: #5e1b5e;
      text-align: center;
      height: 500px;
      padding-top: 10px;
      padding-bottom: 10px;
      position: relative;
    }

    .topic img {
      width: 350px;
      height: auto;
      margin-bottom: 10px;
      margin-top: 5px;
    }

    .search-container {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 20px;
    }

    .search-container input {
      width: 50%;
      max-width: 600px;
      padding: 12px 20px;
      border: none;
      border-radius: 30px 0 0 30px;
      font-size: 16px;
      outline: none;
    }

    .search-container button {
      background-color: white;
      border: none;
      border-left: 1px solid #ccc;
      padding: 12px 20px;
      border-radius: 0 30px 30px 0;
      cursor: pointer;
    }

    .search-container button img {
      width: 20px;
      height: 20px;
    }

    /* === Bottom Navigation Bar === */
    .bottom-nav {
      display: flex;
      align-items: center;
      background-color: #4b004b;
      padding: 20 20px;
      height: 60px;
    }

    .bottom-nav img.logo {
      height: 40px;
    }

    .nav-links {
      display: flex;
      margin-left: auto;
    }

    .nav-btn {
      background-color: #4b004b;
      color: white;
      padding: 23px 30px;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 12px;
      border-right: 2px solid #ffffff;
      transition: background-color 0.3s;
    }

    .nav-btn:last-child {
      border-right: none;
    }

    .nav-btn:hover {
      background-color: #e696ec;
    }

    .nav-btn.active {
      background-color: #e696ec;
      color: #ffffff;
    }

    h1{
        font-size: 60px;
        text-align: center;
        color: #4b004b;
        font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 20px;
      color: green;
    }

    .error {
      text-align: center;
      font-weight: bold;
      margin-top: 20px;
      color: red;
    }

    .upload-container {
      background-color: #f4caff;
      max-width: 500px;
      margin: 30px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .upload-box {
      border: 2px dashed #aaa;
      background-color: #fff;
      padding: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .form-group label {
      flex: 0 0 150px;
      margin-right: 10px;
      font-weight: bold;
      color: #4b004b;
    }

    .form-group input {
      flex: 1;
    }

    button[type="submit"],
    button[type="reset"] {
      padding: 10px 25px;
      background-color: #a94eb5;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button[type="reset"] {
      background-color: rgb(153, 137, 153);
    }

    input[type="text"], input[type="file"] {
      width: 50%;
      padding: 10px;
      margin: 8px 0 20px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

  </style>
</head>
<body>
  <?php
  include('head.php')
  ?>

  <h1>Upload Your Notes</h1>

  <div class="upload-container">
    <?php if ($uploadSuccess): ?>
      <div class="message">File uploaded successfully!</div>
    <?php elseif (!empty($errorMsg)): ?>
      <div class="error"><?= $errorMsg ?></div>
    <?php endif; ?>

    <form action="uploadPage.php" method="POST" enctype="multipart/form-data">
      <div class="upload-box">
        <p>Drop files here</p>
        <p><small>Supported: PNG, JPG, PDF</small></p>
        <input type="file" name="file" required />
      </div>

      <div class="form-group">
        <label for="fileName">File Name : </label>
        <input type="text" name="fileName" placeholder="file name..." required />
      </div>
      <div class="form-group">
        <label for="chapterName">Chapter Name : </label>
        <input type="text" name="chapterName" placeholder="chapter name..." required />
      </div>
      <div class="form-group">
        <label for="author">Author : </label>
        <input type="text" name="author" placeholder="author name..." required />
      </div>
      <div class="form-group">
        <label for="courseName">Course Name : </label>
        <input type="text" name="courseName" placeholder="course name..." required />
      </div>
      <div class="form-group">
        <label for="University">University :</label>
        <input type="text" name="University" placeholder="university name..." required />
      </div>

      <div class="btn-group">
        <button type="reset">Cancel</button>
        <button type="submit">Upload</button>
      </div>
    </form>
  </div>
</body>
</html>
