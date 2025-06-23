<?php
session_start();
include("connect.php");

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Get total stats
$userRes = $conn->query("SELECT COUNT(*) FROM user");
$totalUsers = ($userRes && $userRes->num_rows > 0) ? $userRes->fetch_row()[0] : 0;

$noteRes = $conn->query("SELECT COUNT(*) FROM notes");
$totalNotes = ($noteRes && $noteRes->num_rows > 0) ? $noteRes->fetch_row()[0] : 0;

$downloadRes = $conn->query("SELECT COUNT(*) FROM note_downloads");
$totalDownloads = ($downloadRes && $downloadRes->num_rows > 0) ? $downloadRes->fetch_row()[0] : 0;

$reportRes = $conn->query("SELECT COUNT(*) FROM report_note");
$totalReports = ($reportRes && $reportRes->num_rows > 0) ? $reportRes->fetch_row()[0] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - StudyHive</title>
    <style>
        /* Use the same base styles from your index.php */

        body {
            background-color: white;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: white;
        }

        .navbar {
            background-color: #660066;
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            height: 60px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
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
            transition: background-color 0.3s ease;
        }

        .navbar a:hover {
            background-color: #990099;
        }

        .header {
            background-color: #ec97ec;
            color: #5e1b5e;
            text-align: center;
            padding: 30px 20px;
            font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 2.8em;
            font-weight: bold;
        }

        .header p {
            font-size: 1.2em;
            margin-top: 10px;
            color: #3d0d3d;
        }

        .container {
            font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
            max-width: 1000px;
            margin: 40px auto;
            background-color:  #660066;
            color: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 30px;
        }

        .card {
            background-color: #f7d7f7;
            border-radius: 15px;
            padding: 25px 35px;
            width: 200px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(102, 0, 102, 0.3);
            transition: transform 0.3s ease;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
        }


        .card:hover {
            transform: scale(1.05);
        }

        .card h2 {
            margin: 0 0 10px 0;
            font-size: 3em;
            color: #660066;
        }

        .card p {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #3d0d3d;
        }
    </style>
</head>
<body>

    <header class="header">
        <img src="images/whiteLogo.png" alt="Logo" style="width: 170px; height: auto;">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['user_Name']) ?>!</p>
    </header>

    <nav class="navbar">
        <ul>
            <li><a href="adminDashboard.php">Dashboard</a></li>
            <li><a href="adminUsers.php">Manage Users</a></li>
            <li><a href="adminNotes.php">Manage Notes</a></li>
            <li><a href="adminReports.php">Reported Content</a></li>
            <li><a href="adminFeedback.php">Feedback</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>

    <main class="container">
        <h1 style="text-align:center;">System Overview</h1>
        <div class="card-container">
        <a href="adminUsers.php" class="card-link">
            <div class="card">
                <h2><?= $totalUsers ?></h2>
                <p>Total Users</p>
            </div>
        </a>

        <a href="adminNotes.php" class="card-link">
            <div class="card">
            <h2><?= $totalNotes ?></h2>
            <p>Total Notes</p>
            </div>
        </a>

        <a href="adminAnalytics.php" class="card-link">
            <div class="card">
            <h2><?= $totalDownloads ?></h2>
            <p>Total Downloads</p>
            </div>
        </a>

        <a href="adminReports.php" class="card-link">
            <div class="card">
            <h2><?= $totalReports ?? 0 ?></h2>
            <p>Reported Notes</p>
            </div>
        </a>
        </div>
    </main>

    <?php
        include('footer.php');
    ?>
</body>
</html>
