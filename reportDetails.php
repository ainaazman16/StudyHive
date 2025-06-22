<?php
session_start();
if (isset($_GET['report_type'])) {
  $_SESSION['report_type'] = $_GET['report_type'];
}
$reportType = isset($_SESSION['report_type']) ? $_SESSION['report_type'] : 'No report type selected';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Details</title>
  <style>
    body { 
        background-color: #ffffff; 
        margin: 0; 
        font-family: Arial, Helvetica, sans-serif; 
        background-size : 90%;
    }

    h1 { 
        font-size: 60px; 
        text-align: center; 
        color: #4b004b; 
    }

    .back-btn { 
        margin: 20px; 
        display: inline-block; 
        font-size: 16px; 
        color: #660066; 
        text-decoration: none; 
        font-weight: bold; 
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
    <?php
    include('head.php');
    ?>
    
    <a class="back-btn" href="reportNotes.php">&larr; Back</a>
    <h1>Report Notes</h1>
    <h3 class="title-reason"><?php echo htmlspecialchars($reportType); ?></h3>

    <?php if ($reportType === 'Inappropriate Language'): ?>
    <ul class="list-report">
        <li><a href="reportDone.php?detail=Swear words or curse words">Swear words or curse words</a></li>
        <li><a href="reportDone.php?detail=Slurs or discriminatory language">Slurs or discriminatory language</a></li>
        <li><a href="reportDone.php?detail=Language meant to insult or degrade someone">Language meant to insult or degrade someone</a></li>
    </ul>
    <?php elseif ($reportType === 'Harassment or Bullying'): ?>
    <ul class="list-report">
        <li><a href="reportDone.php?detail=Mean or hurtful messages">Mean or hurtful messages</a></li>
        <li><a href="reportDone.php?detail=Calling someone bad names">Calling someone bad names</a></li>
        <li><a href="reportDone.php?detail=Telling someone to hurt themselves">Telling someone to hurt themselves</a></li>
    </ul>
    <?php elseif ($reportType === 'Irrelevant or Spam Content'): ?>
    <ul class="list-report">
        <li><a href="reportDone.php?detail=Not related to the topic">Not related to the topic</a></li>
        <li><a href="reportDone.php?detail=Sharing ads or links">Sharing ads or links</a></li>
        <li><a href="reportDone.php?detail=Posting the same thing many times">Posting the same thing many times</a></li>
    </ul>
    <?php elseif ($reportType === 'Plagiarized or Copyrighted Material'): ?>
    <ul class="list-report">
        <li><a href="reportDone.php?detail=Copied from another source">Copied from another source</a></li>
        <li><a href="reportDone.php?detail=Sharing content without permission">Sharing content without permission</a></li>
        <li><a href="reportDone.php?detail=Using someone else\'s work as own">Using someone else’s work as own</a></li>
    </ul>
    <?php endif; ?>
</body> 
</html>
