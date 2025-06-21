<?php
include("connect.php");

// Fetch dropdown options
$subjectList = $conn->query("SELECT subject_ID, subject_Name FROM subject");
$courseList = $conn->query("SELECT course_ID, course_Name FROM course");
$uniList    = $conn->query("SELECT uni_ID, uni_Name FROM university");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Head</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .topic {
      background-color: #ec97ec;
      text-align: center;
      padding: 40px 20px;
      position: relative;
    }

    .topic img.logo {
      width: 250px;
      height: auto;
      margin-bottom: 20px;
    }

    .search-wrapper {
      display: flex;
      justify-content: center;
      margin-top: 10px;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background-color: #fff;
      border-radius: 999px;
      padding: 10px 20px;
      width: 70%;
      max-width: 800px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      position: relative;
    }

    .search-bar input[type="text"] {
      border: none;
      outline: none;
      flex: 1;
      padding: 10px;
      font-size: 16px;
    }

    .search-icon {
      margin-right: 10px;
      color: #999;
      font-size: 18px;
    }

    .filter-btn {
      background: none;
      border: none;
      font-size: 18px;
      cursor: pointer;
      margin-left: 10px;
      color: #660066;
    }

    .filter-options {
      position: absolute;
      top: 55px;
      left: 0;
      right: 0;
      background-color: #f9f9f9;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      z-index: 10;
      display: none;
      flex-direction: column;
      gap: 10px;
    }

    .filter-options select,
    .apply-filter {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
      width: 100%;
    }

    .apply-filter {
      background-color: #660066;
      color: white;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }

    .bottom-nav {
      display: flex;
      align-items: center;
      background-color: #4b004b;
      height: 60px;
      padding: 0 20px;
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
  </style>
</head>

<body>
  <div class="topic">
    <img src="images/whiteLogo.png" alt="logo" class="logo" />

    <!-- Search Bar UI -->
    <div class="search-wrapper">
      <form action="viewNotes.php" method="get" class="search-bar">
        <span class="search-icon"><i class="fa fa-search"></i></span>
        <input type="text" name="search" placeholder="Search notes by title, tag or keyword..." />

        <button type="button" class="filter-btn" onclick="toggleFilter()" title="Filter options">
          <i class="fa fa-sliders-h"></i>
        </button>

        <div class="filter-options" id="filterOptions">
          <select name="subject">
            <option value="">All Subjects</option>
            <?php while ($s = $subjectList->fetch_assoc()): ?>
              <option value="<?= $s['subject_ID'] ?>"><?= $s['subject_Name'] ?></option>
            <?php endwhile; ?>
          </select>

          <select name="course">
            <option value="">All Courses</option>
            <?php while ($c = $courseList->fetch_assoc()): ?>
              <option value="<?= $c['course_ID'] ?>"><?= $c['course_Name'] ?></option>
            <?php endwhile; ?>
          </select>

          <select name="university">
            <option value="">All Universities</option>
            <?php while ($u = $uniList->fetch_assoc()): ?>
              <option value="<?= $u['uni_ID'] ?>"><?= $u['uni_Name'] ?></option>
            <?php endwhile; ?>
          </select>

          <button type="submit" class="apply-filter">Apply</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Navigation Bar -->
  <div class="bottom-nav">
    <img src="images/whiteLogo.png" alt="Logo" class="logo" />
    <div class="nav-links">
      <a href="homePage.php" class="nav-btn active">Home</a>
      <a href="mynotesPage.php" class="nav-btn">My Notes</a>
      <a href="uploadPage.php" class="nav-btn">Upload</a>
      <a href="connectionPage.php" class="nav-btn">Connection</a>
      <a href="profilePage.php" class="nav-btn">Profile</a>
      <a href="logout.php" class="nav-btn">Log Out</a>
    </div>
  </div>

  <script>
    function toggleFilter() {
      const filters = document.getElementById('filterOptions');
      filters.style.display = filters.style.display === 'none' || filters.style.display === '' ? 'flex' : 'none';
    }
  </script>
</body>
</html>
