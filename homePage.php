<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css">
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


  </style>
</head>
<body>
    <?php
    include('head.php');
    ?>

  <h1>User's Dashboard</h1>

</body>
</html>
