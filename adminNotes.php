<?php
session_start();
include("connect.php");

// Check admin access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Fetch notes and join user info
$query = "
    SELECT notes.note_ID, notes.note_Name, notes.upload_Date, notes.file_path, user.user_Name
    FROM notes
    JOIN user ON notes.user_ID = user.user_ID
    ORDER BY notes.upload_Date DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Notes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
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
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #ec97ec;
            color: #5e1b5e;
        }

        .btn {
            padding: 6px 10px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .btn-delete {
            background-color: #e11d48;
        }

        .btn-download {
            background-color: #22c55e;
            text-decoration: none;
        }

        a.btn-download {
    color:rgb(0, 38, 206);
    font-weight: bold;
    text-decoration: underline;
    background-color: transparent;
    border: none;
}

    </style>
</head>
<body>

<header class="header">
        <img src="images/whiteLogo.png" alt="Logo" style="width: 170px; height: auto;">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['user_Name']) ?>!</p>
    </header>

<div class="navbar">
    <a href="adminDashboard.php">Dashboard</a>
    <a href="adminUsers.php">Manage Users</a>
    <a href="adminNotes.php">Manage Notes</a>
    <a href="adminReports.php">Reported Content</a>
    <a href="adminFeedback.php">Feedback</a>
    <a href="logout.php">Log out</a>
</div>

<div class="container">
    <h2>Manage Notes</h2>

    <table>
        <tr>
            <th>Note ID</th>
            <th>Title</th>
            <th>Uploader</th>
            <th>File</th>
            <th>Actions</th>
        </tr>
        <?php 
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['note_ID'] ?></td>
                <td><?= htmlspecialchars($row['note_Name']) ?></td>
                <td><?= htmlspecialchars($row['user_Name']) ?></td>
                <td>
                    <?php if (!empty($row['file_path'])): ?>
                        <a class="btn btn-download" href="uploads/<?= htmlspecialchars($row['file_path']) ?>" download>
                            <?= basename($row['file_path']) ?>
                        </a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>

                <td>
                    <form method="POST" action="adminDeleteNote.php" onsubmit="return confirm('Delete this note?');">
                        <input type="hidden" name="note_ID" value="<?= $row['note_ID'] ?>">
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
