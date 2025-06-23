<?php
session_start(); // You need this to use $_SESSION
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
  header("Location: loginPage.php");
  exit();
}

$userID = $_SESSION['user_ID'];

if (!isset($_GET['note_ID'])) {
  echo "Note not found.";
  exit();
}
$noteID = $_GET['note_ID'];

// Now it's safe to use $noteID and $userID
$checkHelpful = $conn->prepare("SELECT * FROM note_helpful WHERE note_ID = ? AND user_ID = ?");
$checkHelpful->bind_param("ii", $noteID, $userID);
$checkHelpful->execute();
$alreadyHelpful = $checkHelpful->get_result()->num_rows > 0;

// Then fetch the note info
$query = $conn->prepare("
  SELECT n.*, u.user_Fname, uni.uni_Name 
  FROM notes n
  LEFT JOIN user u ON n.user_ID = u.user_ID
  LEFT JOIN university uni ON n.uni_ID = uni.uni_ID
  WHERE n.note_ID = ?
");
if (!$query) {
  die("SQL Error: " . $conn->error);
}

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
<link ref="stylesheet" href="style.css">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($note['note_Name']) ?> - StudyHive</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="icon" type="image/png" href="images/logo.png">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f8f2f9;
    }
   
    h1{
        font-size: 60px;
        text-align: center;
        color: #4b004b;
        font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
    }

    .back-link {
      display: inline-block;
      margin: 25px auto 0 auto;
      max-width: 800px;
      padding-left: 15px;
      text-decoration: none;
      color: #660066;
      font-weight: bold;
      font-size: 15px;
      display: block;
    }

    .page-title {
        font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;


      max-width: 800px;
      margin: 30px auto 10px auto;
      color: #660066;
      font-size: 28px;
      text-align: center;
      font-weight: bold;
    }

    .container {
      max-width: 800px;
      margin: 0 auto 40px auto;
      background-color: #fff0ff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #4b004b;
      font-size: 28px;
      margin-bottom: 20px;
      text-align: center;
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

    .back-btn {
      display: inline-block;
      margin-top: 20px;
      margin-left: 20px;
      font-size: 16px;
      color: #660066;
      text-decoration: none;
      font-weight: bold;
    }

    .btn-rate {
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

    .btn-report {
      display: inline-block;
      background-color: #ed3232;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-rate:hover {
      background-color: #b94cb9;
    }

    .btn-report:hover {
      background-color: maroon;
    }

    .btn-helpful {
      background-color: #008000;
      display: inline-block;

      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-helpful:hover {
      background-color: #006400;
    }

    .btn-unhelpful {
      background-color:rgb(167, 20, 71);
  
      display: inline-block;

      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      text-decoration: none;
      font-weight: bold;
      transition: background-color 0.3s;
    }

    .btn-unhelpful:hover {
      background-color: #a52800;
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
  <?php include('head.php'); ?>

  <a class="back-btn" href="homePage.php">← Back to Dashboard</a>

  <!-- "Note Details" outside the pink box -->
  <h1>Note Details</h1>

  <div class="container">
    <h1><?= htmlspecialchars($note['note_Name']) ?></h1>
    <div class="note-detail"><strong>Author:</strong> <?= $note['user_Fname'] ?></div>
    <div class="note-detail"><strong>University:</strong> <?= $note['uni_Name'] ?></div>
    <div class="note-detail"><strong>Uploaded On:</strong> <?= $note['upload_date'] ?></div>
    <div class="note-detail"><strong>Downloads:</strong> <?= $note['download_count'] ?></div>
    <div class="note-detail"><strong>File Type:</strong> <?= strtoupper($note['file_type']) ?></div>
    <?php
    $helpfulCount = $conn->query("SELECT COUNT(*) FROM note_helpful WHERE note_ID = $noteID")->fetch_row()[0];
    ?>
    <div class="note-detail"><strong>Helpful Count:</strong> <?= $helpfulCount ?></div>


    <a href="download.php?note_ID=<?= $note['note_ID'] ?>" class="download-btn">Download This Note</a>
    <!-- Rate Note Button -->
    <form method="get" action="rateNotes.php" style="display:inline;">
      <input type="hidden" name="note_ID" value="<?= $note['note_ID'] ?>">
      <button type="submit" class="download-btn" style="background-color: #e38bce;">Rate Note</button>
    </form>
    <!-- Report Note Button -->
    <form method="get" action="reportNotes.php" style="display:inline;">
      <input type="hidden" name="note_ID" value="<?= $note['note_ID'] ?>">
      <button type="submit" class="download-btn" style="background-color: #ed3232;">Report</button>
    </form>
    <!-- Helpful / Unmark Helpful -->
    <?php if (!$alreadyHelpful): ?>
      <form method="post" action="markHelpful.php" style="display:inline;">
        <input type="hidden" name="note_ID" value="<?= $note['note_ID'] ?>">
        <button type="submit" class="action-btn btn-helpful">Helpful</button>
      </form>
    <?php else: ?>
      <form method="post" action="unmarkHelpful.php" style="display:inline;">
        <input type="hidden" name="note_ID" value="<?= $note['note_ID'] ?>">
        <button type="submit" class="action-btn btn-unhelpful">Unmark Helpful</button>
      </form>
    <?php endif; ?>


  </div>

  <?php include("footer.php"); ?>
</body>

</html>