<?php
session_start();
include("connect.php");

// Check if admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Fetch users
$query = "SELECT * FROM user";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
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

        .btn-role {
            background-color: #3b82f6;
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

<div class="container">
    <h2>Manage Users</h2>

    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['user_ID'] ?></td>
                <td><?= htmlspecialchars($row['user_Name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= $row['role'] ?></td>
                <td>
                    <form method="POST" action="adminDeleteUser.php" style="display:inline-block;">
                        <input type="hidden" name="user_ID" value="<?= $row['user_ID'] ?>">
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this user?')">Delete</button>
                    </form>

                    <form method="POST" action="adminChangeRole.php" style="display:inline-block;">
                        <input type="hidden" name="user_ID" value="<?= $row['user_ID'] ?>">
                        <button type="submit" class="btn btn-role">Change Role</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

 <?php
        include('footer.php');
    ?>

</body>
</html>
