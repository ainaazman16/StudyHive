<?php
// profilePage.php
session_start();
include("connect.php");

// Fetch last 3 uploaded notes by this user (if needed in future)
// $userID = $_SESSION['user_ID'];
// $myNotes = $conn->prepare(...);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile - StudyHive</title>
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    h1 {
      font-size: 60px;
      text-align: center;
      color: #4b004b;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      margin-top: 30px;
    }

    .center-container {
      display: flex;
      justify-content: center;
      padding: 40px 20px;
    }

    .card {
      width: 500px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      padding: 20px 25px;
      text-align: left;
    }

    .card h3 {
      margin-top: 0;
      font-size: 22px;
      color: #4b004b;
    }

    .card p {
      font-size: 15px;
      color: #333;
      margin: 12px 0;
    }

    .card a {
      color: #660066;
      text-decoration: underline;
      font-weight: bold;
      font-size: 14px;
    }

    .card a:hover {
      text-decoration: none;
    }
  </style>
</head>
<body>

<?php include('head.php'); ?>

<h1>User's Profile</h1>

<div class="center-container">
  <div class="card">
    <h3>Connections</h3>

  </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
