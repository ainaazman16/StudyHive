<?php
session_start();

if (isset($_GET['report_type'])) {
    $_SESSION['report_type'] = $_GET['report_type'];
}

$reportType = $_SESSION['report_type'] ?? 'No report type selected';
$noteID = $_GET['note_ID'] ?? null;

if (!$noteID || !is_numeric($noteID)) {
    die("❌ Note ID missing or invalid.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report Details</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { background-color: #ffffff; margin: 0; font-family: Arial, Helvetica, sans-serif; }
    h1 { font-size: 60px; text-align: center; color: #4b004b; }
    .back-btn { margin: 20px; display: inline-block; font-size: 16px; color: #660066; text-decoration: none; font-weight: bold; }
    .list-report { list-style: none; padding: 0; max-width: 500px; margin: 20px auto; }
    .list-report li { background: #f2edf9; padding: 15px 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 4px #ddd; font-weight: 500; transition: background 0.2s; }
    .list-report li:hover { background: #e0d4f5; }
    .list-report li a { text-decoration: none; color: #333; display: block; }
    .title-reason { text-align: center; margin-top: 40px; font-size: 1.2em; color: #222; }
    .note-card { max-width: 600px;
      margin: 20px auto; background-color: #f7f0f7; border: 1px solid #ccc; border-radius: 10px; padding: 20px; color: #333;}
  </style>
</head>
<body>

<?php include('head.php'); ?>

<a class="back-btn" href="reportNotes.php?note_ID=<?= urlencode($noteID) ?>">&larr; Back</a>

<h1>Report Notes</h1>
<h3 class="title-reason"><?= htmlspecialchars($reportType) ?></h3>

<div class="note-card">
  <h3>Note ID: <?= htmlspecialchars($noteID) ?></h3>
  <p><strong>Report Type:</strong> <?= htmlspecialchars($reportType) ?></p>
<ul class="list-report">
<?php
$reasons = [];

switch ($reportType) {
  case 'Inappropriate Language':
    $reasons = [
      "Swear words or curse words",
      "Slurs or discriminatory language",
      "Language meant to insult or degrade someone"
    ];
    break;

  case 'Harassment or Bullying':
    $reasons = [
      "Mean or hurtful messages",
      "Calling someone bad names",
      "Telling someone to hurt themselves"
    ];
    break;

  case 'Irrelevant or Spam Content':
    $reasons = [
      "Not related to the topic",
      "Sharing ads or links",
      "Posting the same thing many times"
    ];
    break;

  case 'Plagiarized or Copyrighted Material':
    $reasons = [
      "Copied from another source",
      "Sharing content without permission",
      "Using someone else’s work as own"
    ];
    break;

  default:
    echo "<li>No specific reasons available.</li>";
    break;
}

foreach ($reasons as $detail) {
  $urlDetail = urlencode($detail);
  echo "<li><a href='reportDone.php?detail=$urlDetail&note_ID=$noteID'>" . htmlspecialchars($detail) . "</a></li>";
}
?>
</ul>
</div>

</body>
</html>
