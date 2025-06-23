<?php
session_start();
include("connect.php");

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Fetch all users except deactivated ones
$sql = "SELECT user_ID, user_Fname, user_Name, email, role FROM user WHERE role != 'deactivated'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <style>
        /* Your existing styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
        }

        .header {
            background-color: #ec97ec;
            color: #5e1b5e;
            text-align: center;
            padding: 20px;
        }

        .navbar {
            background-color: #660066;
            padding: 10px;
            display: flex;
            justify-content: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: #990099;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #ec97ec;
            color: #5e1b5e;
        }

        .btn-delete {
            background-color: #e11d48;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #be123c;
        }

        .message {
            text-align: center;
            color: green;
            margin-bottom: 10px;
        }

        .error {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Admin Panel - Manage Users</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['user_Name']) ?>!</p>
</div>

<div class="navbar">
    <a href="adminDashboard.php">Dashboard</a>
    <a href="adminUsers.php">Manage Users</a>
    <a href="adminNotes.php">Manage Notes</a>
    <a href="adminReports.php">Reported Content</a>
    <a href="adminFeedback.php">Feedback</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

    <?php if (isset($_GET['success'])): ?>
        <p class="message"><?= htmlspecialchars($_GET['success']) ?></p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <h2>All Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['user_ID'] ?></td>
                <td><?= htmlspecialchars($row['user_Fname']) ?></td>
                <td><?= htmlspecialchars($row['user_Name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <?php if ($_SESSION['user_ID'] != $row['user_ID']): ?>
                        <form method="POST" action="adminDeleteUser.php" onsubmit="return confirm('Are you sure you want to delete this user permanently?');" style="display:inline;">
                            <input type="hidden" name="user_ID" value="<?= $row['user_ID'] ?>">
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    <?php else: ?>
                        <span style="color: gray;">(You)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>