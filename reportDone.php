<?php
  $reportType = isset($_GET['report_type']) ? htmlspecialchars($_GET['report_type']) : 'No report type selected';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HOME</title>
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

    .report-box {
      max-width: 600px;
      margin: 30px auto;
      padding: 30px;
      border-radius: 12px;
      background-color: #f7f3fc;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .report-type{
      text-align: left;
      color: #4b004b;
    }

    .report-box h3 {
      color: #4b004b;
      margin-bottom: 15px;
    }

    .report-box h4 {
      font-weight: normal;
      color: #333;
      line-height: 1.6;
    }

    .right-icon {
      text-align: center;
      width: 120px;
      height: auto;
      margin: 15px auto;
    }

  </style>
  include("reportNotes.php");
</head>
<body>

<?php
  include('head.php');
  ?>

  <h1>Report Notes</h1>

  <section class= "report-box">
    <form action= "reportNotes.php" method="get">
    <h3 class="report-type"><b>Report type :</b><br>
    <?php 
      echo htmlspecialchars($reportType); 
    ?></h3>
    <form>

    <img src="images/rightIcon.jpg" class= "right-icon"/>
    <h3><b>Report Sumbitted Successfully</b></h3>
    <h4>Thank you for your feedback. Your report has been received and will be 
      reviewed by our team to ensure the learning environment remains safe and 
      respectful for everyone. We appreciate you helping us keep the platform clean and positive.</h4> 
  </section>
</body>
</html>
