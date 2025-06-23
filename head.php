<?php
include("connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$subjectList = null;
$courseList = null;
$uniList = null;
$userData = null;
$currentPage = basename($_SERVER['PHP_SELF']);

try {
    $subjectList = $conn->query("SELECT subject_ID, subject_Name FROM subject");
    $courseList = $conn->query("SELECT course_ID, course_Name FROM course");
    $uniList    = $conn->query("SELECT uni_ID, uni_Name FROM university");

    if (isset($_SESSION['user_ID'])) {
        $stmt = $conn->prepare("SELECT user_Fname FROM user WHERE user_ID = ?");
        $stmt->bind_param("i", $_SESSION['user_ID']);
        $stmt->execute();
        $userData = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
} catch (Exception $e) {
    error_log("Database error in head.php: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudyHive</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .topic {
      background-color: #ec97ec;
      text-align: center;
      padding: 0px 0px; 
      position: relative;
    }

    .welcome-message h2 {
      font-family: 'Cambria', 'Georgia', serif;
    }

    .topic img.logo {
      width: 250px;
      height: auto;
      margin-bottom: 20px;
      max-width: 100%;
    }

    .search-wrapper {
      display: flex;
      justify-content: center;
      width: 100%;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background-color: #fff;
      border-radius: 999px;
      padding: 10px 20px;
      width: 90%;
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
      min-width: 0;
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
      background-color: #660066;
      position: sticky;
      top: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 10px;
      height: 60px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.5);
      z-index: 999;
    }

    .bottom-nav .nav-links {
      display: flex;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-links a {
      text-decoration: none;
      color: white;
      padding: 14px 16px;
      display: block;
      font-size: 14px;
      font-weight: bold;
      text-transform: uppercase;
      transition: background-color 0.3s ease;
    }

    .nav-links a:hover,
    .nav-links a.active {
      background-color: #990099;
    }

    .welcome-message {
      font-size: 20px;
      color: #4b004b;
      font-weight: 600;
      margin-top: 15px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    @media (max-width: 768px) {
      .topic img.logo {
        width: 180px;
      }

      .search-bar {
        width: 95%;
        padding: 8px 15px;
      }

      .nav-links a {
        padding: 12px;
        font-size: 12px;
      }
    }
  </style>
</head>

<body>
  <div class="topic">
    <img src="images/whiteLogo.png" alt="logo" class="logo" />

    <!-- Search Bar -->
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
            <?php if ($subjectList): while ($s = $subjectList->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($s['subject_ID']) ?>"><?= htmlspecialchars($s['subject_Name']) ?></option>
            <?php endwhile; endif; ?>
          </select>

          <select name="course">
            <option value="">All Courses</option>
            <?php if ($courseList): while ($c = $courseList->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($c['course_ID']) ?>"><?= htmlspecialchars($c['course_Name']) ?></option>
            <?php endwhile; endif; ?>
          </select>

          <select name="university">
            <option value="">All Universities</option>
            <?php if ($uniList): while ($u = $uniList->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($u['uni_ID']) ?>"><?= htmlspecialchars($u['uni_Name']) ?></option>
            <?php endwhile; endif; ?>
          </select>

          <button type="submit" class="apply-filter">Apply</button>
        </div>
      </form>
    </div>

    <!-- Welcome Message (Only on homePage) -->
    <?php if ($currentPage === 'homePage.php' && isset($userData['user_Fname'])): ?>
      <div class="welcome-message">
        <h2>Welcome back, <strong><?= htmlspecialchars($userData['user_Fname']) ?>!</strong></h2>
      </div>
    <?php endif; ?>
  </div>

  <!-- Navigation Bar -->
  <div class="bottom-nav">
    <div class="nav-links">
      <a href="homePage.php" class="<?= $currentPage == 'homePage.php' ? 'active' : '' ?>">Home</a>
      <a href="viewNotes.php" class="<?= $currentPage == 'viewNotes.php' ? 'active' : '' ?>">Browse Notes</a>
      <a href="mynotesPage.php" class="<?= $currentPage == 'mynotesPage.php' ? 'active' : '' ?>">My Notes</a>
      <a href="uploadPage.php" class="<?= $currentPage == 'uploadPage.php' ? 'active' : '' ?>">Upload</a>
      <a href="connectionPage.php" class="<?= $currentPage == 'connectionPage.php' ? 'active' : '' ?>">Connection</a>
      <a href="profilePage.php" class="<?= $currentPage == 'profilePage.php' ? 'active' : '' ?>">Profile</a>
      <a href="logout.php">Log Out</a>
    </div>
  </div>

  <script>
    function toggleFilter() {
      const filters = document.getElementById('filterOptions');
      filters.style.display = filters.style.display === 'none' || filters.style.display === '' ? 'flex' : 'none';
    }

    document.addEventListener('click', function (e) {
      const filter = document.getElementById('filterOptions');
      const btn = document.querySelector('.filter-btn');
      if (!filter.contains(e.target) && !btn.contains(e.target)) {
        filter.style.display = 'none';
      }
    });
  </script>
</body>
</html>
