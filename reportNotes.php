<?php
  session_start();

  $backQuery = http_build_query([
    'search' => $_GET['search'] ?? '',
    'uni_ID' => $_GET['uni_ID'] ?? '',
    'faculty_ID' => $_GET['faculty_ID'] ?? '',
    'course_ID' => $_GET['course_ID'] ?? '',
    'subject_ID' => $_GET['subject_ID'] ?? ''
  ]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Notes</title>
  <style>
    body { 
      background-color: #ffffff; 
      margin: 0; font-family: Arial, Helvetica, sans-serif;
    }

    h1 { 
      font-size: 60px; 
      text-align: center; 
      color: #660066; 
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
      background-color: #f9f9f9;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      color: #333;
    }

    .list-report { 
      list-style: none; 
      padding: 0; 
      max-width: 500px; 
      margin: 20px auto; 
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

    .title-reason { 
      text-align: center; 
      margin-top: 40px; 
      font-size: 1.2em; 
      color: #222; 
    }
  </style>
</head>
<body>

  <?php include('head.php'); ?>

  <a class="back-btn" href="viewNotes.php?<?= $backQuery ?>">&larr; Back</a>

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

  <h3 class="title-reason">Select a reason</h3>

  <ul class="list-report">
    <li><a href="reportDetails.php?report_type=Inappropriate Language">1. Inappropriate Language</a></li>
    <li><a href="reportDetails.php?report_type=Harassment or Bullying">2. Harassment or Bullying</a></li>
    <li><a href="reportDetails.php?report_type=Irrelevant or Spam Content">3. Irrelevant or Spam Content</a></li>
    <li><a href="reportDetails.php?report_type=Plagiarized or Copyrighted Material">4. Plagiarized or Copyrighted Material</a></li>
  </ul>

</body>
</html>
