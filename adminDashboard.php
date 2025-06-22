<?php
session_start();
include("connect.php");

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Get total stats
$totalUsers = $conn->query("SELECT COUNT(*) FROM user")->fetch_row()[0];
$totalNotes = $conn->query("SELECT COUNT(*) FROM notes")->fetch_row()[0];
$totalDownloads = $conn->query("SELECT COUNT(*) FROM note_downloads")->fetch_row()[0];
$totalReports = $conn->query("SELECT COUNT(*) FROM report_note")->fetch_row()[0]; // only if using report_note

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - StudyHive</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #ec97ec;
            color: black;
            padding: 20px;
            text-align: center;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .nav {
            background-color: #660066;
            padding: 10px;
            display: flex;
            justify-content: center;
        }

        .nav a {
            text-decoration: none;
            color: white;
            padding: 14px 16px;
            display: block;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
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

        .nav a:hover {
           background-color: #990099;
        }

        .container {
            padding: 30px;
        }

        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 25px;
            width: 220px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin: 10px 0;
            font-size: 32px;
            color: #111827;
        }

        .card p {
            color: #6b7280;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #e5e7eb;
            margin-top: 30px;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/whiteLogo.png" alt="Logo" class="logo">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['user_Name']) ?>!</p>
    </div>

    <div class="nav">
        <a href="adminDashboard.php">Dashboard</a>
        <a href="adminUsers.php">Manage Users</a>
        <a href="adminNotes.php">Manage Notes</a>
        <a href="adminReports.php">Reported Content</a>
        <a href="adminFeedback.php">Feedback</a>
        <a href="adminAnalytics.php">Analytics</a>
        <a href="adminSettings.php">Settings</a>
        <a href="logout.php">Log out</a>
    </div>

    <div class="container">
        <h2>System Overview</h2>
        <div class="card-container">
            <div class="card">
                <h2><?= $totalUsers ?></h2>
                <p>Total Users</p>
            </div>
            <div class="card">
                <h2><?= $totalNotes ?></h2>
                <p>Total Notes</p>
            </div>
            <div class="card">
                <h2><?= $totalDownloads ?></h2>
                <p>Total Downloads</p>
            </div>
            <div class="card">
                <h2><?= $totalReports ?? 0 ?></h2>
                <p>Reported Notes</p>
            </div>
        </div>
    </div>
     <?php
    include('footer.php');
    ?>
</body>
</html>
