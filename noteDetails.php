<?php
include("connect.php");

if (!isset($_GET['note_ID'])) {
    echo "Note not found.";
    exit();
}

$noteID = $_GET['note_ID'];
$query = $conn->prepare("SELECT * FROM notes WHERE note_ID = ?");
$query->bind_param("i", $noteID);
$query->execute();
$result = $query->get_result();
$note = $result->fetch_assoc();

if (!$note) {
    echo "Note not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($note['note_Name']) ?> - StudyHive</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f8f2f9;
    }

    .header {
      background-color: #660066;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      max-width: 800px;
      margin: 40px auto;
      background-color: #fff0ff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h1 {
      color: #4b004b;
      font-size: 28px;
      margin-bottom: 20px;
    }

    .note-detail {
      margin-bottom: 20px;
      font-size: 16px;
      color: #333;
    }

    .note-detail strong {
      color: #4b004b;
    }

    .download-btn {
      display: inline-block;
      background-color: #cc66cc;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .download-btn:hover {
      background-color: #b94cb9;
    }

    .back-link {
      display: inline-block;
      margin-top: 30px;
      text-decoration: none;
      color: #660066;
      font-weight: bold;
    }

    .footer {
      margin-top: 60px;
      background-color: #660066;
      color: white;
      text-align: center;
      padding: 10px;
    }
  </style>
</head>
<body>

  <div class="header">
    <h2>StudyHive | Note Details</h2>
  </div>

  <div class="container">
    <h1><?= htmlspecialchars($note['note_Name']) ?></h1>

    <div class="note-detail"><strong>Uploaded On:</strong> <?= $note['upload_date'] ?></div>
    <div class="note-detail"><strong>Downloads:</strong> <?= $note['download_count'] ?></div>
    <div class="note-detail"><strong>File Type:</strong> <?= strtoupper($note['file_type']) ?></div>

    <a href="download.php?note_ID=<?= $note['note_ID'] ?>" class="download-btn">Download This Note</a><br>

    <a href="homePage.php" class="back-link">← Back to Dashboard</a>
  </div>

  <div class="footer">
    &copy; <?= date('Y') ?> StudyHive. All rights reserved.
  </div>

</body>
</html>
