<?php
session_start();

if (isset($_SESSION['user_Name'])) {
    $_SESSION = array();
    session_destroy();
    echo "<meta http-equiv=\"refresh\" content=\"3;URL=index.php\">";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to StudyHive</title>
  <style>
    body {
      background-color: #660066;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    .navbar {
      background-color: #660066;
      position: sticky;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 10px;
      height: 60px;
      z-index: 999;
    }

    .navbar .logo {
      height: 60px;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      margin: 0;
      padding: 0;
    }

    .navbar li {
      margin-left: 10px;
    }

    .navbar a {
      text-decoration: none;
      color: white;
      padding: 14px 16px;
      display: block;
      font-size: 14px;
      font-weight: bold;
      text-transform: uppercase;
      position: relative;
      transition: all 0.3s ease;
      background-color: transparent;
    }

    .navbar a:hover {
      transform: translateY(-5px);
      background-color: transparent;
    }

    .navbar a::after {
      content: '';
      position: absolute;
      bottom: 4px;
      left: 50%;
      transform: translateX(-50%) scaleX(0);
      transform-origin: center;
      width: 70%;
      height: 3px;
      background-color: white;
      transition: transform 0.3s ease;
    }

    .navbar a:hover::after,
    .navbar a.active::after {
      transform: translateX(-50%) scaleX(1);
    }

    .navbar a.active {
      transform: translateY(-5px);
      background-color: transparent;
    }

    .topic {
      background-color: #ec97ec;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      font-size: 230%;
      color: #5e1b5e;
      text-align: center;
      padding: 5px 0;
    }

    .topic img {
      width: 245px;
      height: auto;
      margin-bottom: 5px;
      margin-top: 5px;
    }

    .content {
      background-color: white;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
    }

    .title {
      display: flex;
      max-width: 1000px;
      background-color: white;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .title-box {
      background-color: #f7d7f7;
      color: #660066;
      padding: 30px;
      width: 35%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: bold;
      text-align: center;
    }

    .title-box span {
      color: #3d0d3d;
    }

    .features {
      padding: 30px;
      width: 65%;
    }

    .features strong {
      display: block;
      margin-top: 20px;
      font-size: 18px;
      color: #660066;
    }

    .features p {
      font-size: 16px;
      color: #333;
      line-height: 1.6;
    }

    @media (max-width: 768px) {
      .navbar a {
        padding: 12px;
        font-size: 12px;
      }

      .topic img {
        width: 180px;
      }

      .title {
        flex-direction: column;
      }

      .title-box, .features {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <main>
    <section>
      <div class="navbar">
        <img src="images/whiteLogo.png" alt="Logo" class="logo">
        <ul>
          <li><a href="loginPage.php">Login</a></li>
          <li><a href="signupPage.php">Sign Up</a></li>
        </ul>
      </div>

      <div class="topic">
        <img src="images/whiteLogo.png" alt="logo" class="logo" />
        <h2>WELCOME TO STUDY HIVE</h2>
      </div>
    </section>

    <section class="content">
      <div class="title">
        <div class="title-box">
          <h2>What is<br /><span>Study Hive?</span></h2>
        </div>
        <div class="features">
          <strong>❇ Sharing, accessing and organizing study materials</strong>
          <p>
            StudyHive is a web-based platform designed to serve Malaysian
            university students by providing a centralized space for sharing,
            accessing and organizing study materials.
          </p>

          <strong>☺ Download notes, rate and review content</strong>
          <p>
            Download the study materials for sharing with other friends in the
            system and download the materials from other users.
          </p>

          <strong>✓ Reduce paper waste</strong>
          <p>
            With academic note-sharing systems, StudyHive aims to reduce paper
            waste, preserve valuable notes and foster collaborative learning
            among students.
          </p>
        </div>
      </div>
    </section>
  </main>

  <?php include('footer.php'); ?>
</body>
</html>
