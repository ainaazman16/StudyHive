<?php
session_start();
include("connect.php");

$noteID = $_GET['note_ID'] ?? $_POST['note_ID'] ?? null;
if (!$noteID){
    die("Note ID missing.");
}

$userID = $_SESSION['user_ID'] ?? null;

$search = $_GET['search'] ?? $_POST['search'] ?? '';
$uni_ID = $_GET['uni_ID'] ?? $_POST['uni_ID'] ?? '';
$faculty_ID = $_GET['faculty_ID'] ?? $_POST['faculty_ID'] ?? '';
$course_ID = $_GET['course_ID'] ?? $_POST['course_ID'] ?? '';
$subject_ID = $_GET['subject_ID'] ?? $_POST['subject_ID'] ?? '';

$note = null;
if ($noteID) {
    $stmt = $conn->prepare("
        SELECT n.note_Name, n.file_type, n.upload_date, u.user_Fname
        FROM notes n
        LEFT JOIN user u ON n.user_ID = u.user_ID
        WHERE n.note_ID = ?
    ");
    $stmt->bind_param("i", $noteID);
    $stmt->execute();
    $result = $stmt->get_result();
    $note = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Notes</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: #ffffff;
      margin: 0;
    }

    h1 {
      text-align: center;
      color: #660066;
      margin-top: 30px;
    }

    .back-btn {
      display: block;
      margin: 20px;
      font-size: 16px;
      color: #660066;
      text-decoration: none;
      font-weight: bold;
      text-align: left;
    }

    .note-card {
      max-width: 600px;
      margin: 20px auto;
      background-color: #f7f0f7;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
      color: #333;
    }

    .report-card {
      max-width: 600px;
      margin: 20px auto;
      background-color: #f9f9f9;
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 20px;
    }

    .report-card h3 {
      text-align: center;
      color: #4a004a;
    }

    .list-report {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }

    .list-report li {
      background: #f2edf9;
      padding: 15px 20px;
      margin: 10px 0;
      border-radius: 8px;
      box-shadow: 0 2px 4px #ddd;
      font-weight: 500;
      transition: background 0.2s;
    }

    .list-report li:hover {
      background: #e0d4f5;
    }

    .list-report li a {
      text-decoration: none;
      color: #333;
      display: block;
    }
  </style>
</head>
<body>

<?php include('head.php'); ?>

<a class="back-btn" href="viewNotes.php?note_ID=<?= $noteID ?>&search=<?= $search ?>&uni_ID=<?= $uni_ID ?>&faculty_ID=<?= $faculty_ID ?>&course_ID=<?= $course_ID ?>&subject_ID=<?= $subject_ID ?>">&larr; Back</a>

<h1>Report Notes</h1>

<?php if ($note): ?>
  <div class="note-card">
    <h3><?= htmlspecialchars($note['note_Name']) ?></h3>
    <p><strong>Type:</strong> <?= strtoupper($note['file_type']) ?></p>
    <p><strong>Author:</strong> <?= htmlspecialchars($note['user_Fname']) ?></p>
    <p><strong>Uploaded:</strong> <?= $note['upload_date'] ?></p>
  </div>
<?php else: ?>
  <p style="color:red; text-align:center;">Note not found.</p>
<?php endif; ?>

<div class="report-card">
  <h3>Select a Reason to Report</h3>
  <ul class="list-report">
    <li><a href="reportDetails.php?note_ID=<?= $noteID ?>&report_type=Inappropriate Language">1. Inappropriate Language</a></li>
    <li><a href="reportDetails.php?note_ID=<?= $noteID ?>&report_type=Harassment or Bullying">2. Harassment or Bullying</a></li>
    <li><a href="reportDetails.php?note_ID=<?= $noteID ?>&report_type=Irrelevant or Spam Content">3. Irrelevant or Spam Content</a></li>
    <li><a href="reportDetails.php?note_ID=<?= $noteID ?>&report_type=Plagiarized or Copyrighted Material">4. Plagiarized or Copyrighted Material</a></li>
  </ul>
</div>

</body>
</html>
