<?php
session_start();
include("connect.php"); // Make sure this connects to your database

$reportType = $_SESSION['report_type'] ?? '';
$detail = $_GET['detail'] ?? '';
$noteID = $_GET['note_ID'] ?? null;
$userID = $_SESSION['user_ID'] ?? null;

if (!$noteID || !$userID) {
    die("Missing note ID or not logged in.");
}

// Insert the report
$stmt = $conn->prepare("INSERT INTO report_note (note_ID, user_ID, report_type, report_detail, report_date) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiss", $noteID, $userID, $reportType, $detail);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report Submitted</title>
  <style>
    body { background-color: #ffffff; margin: 0; font-family: Arial, Helvetica, sans-serif; }
    h1 { font-size: 60px; text-align: center; color: #4b004b; }
    .back-btn { margin: 20px; display: inline-block; font-size: 16px; color: #660066; text-decoration: none; font-weight: bold; }
    .report-box { max-width: 600px; margin: 30px auto; padding: 30px; border-radius: 12px; background-color: #f7f3fc; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); text-align: center; }
    .report-type { text-align: left; color: #4b004b; }
    .report-box h3 { color: #4b004b; margin-bottom: 15px; }
    .report-box h4 { font-weight: normal; color: #333; line-height: 1.6; }
    .right-icon { text-align: center; width: 120px; height: auto; margin: 15px auto; }
  </style>
</head>
<body>

<?php include('head.php'); ?>

<a class="back-btn" href="viewNotes.php">&larr; Back</a>

<h1>Report Notes</h1>

<section class="report-box">
  <h3 class="report-type"><b>Report type :</b><br><?= htmlspecialchars($reportType) ?></h3>
  <h3 class="report-type"><b>Report detail :</b><br><?= htmlspecialchars($detail) ?></h3>
  <img src="images/rightIcon.jpg" class="right-icon" />
  <h3><b>Report Submitted Successfully</b></h3>
  <h4>Thank you for your feedback. Your report has been received and will be reviewed by our team to ensure the learning environment remains safe and respectful for everyone.</h4>
</section>

</body>
</html>
