<?php
session_start();
include("connect.php");

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Fetch all reviews with note title
$query = "
    SELECT review.review_ID, review.rating, review.is_helpful, review.review_text, review.note_ID, notes.note_Name
    FROM review
    LEFT JOIN notes ON review.note_ID = notes.note_ID
    ORDER BY review.review_ID DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Feedback</title>
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
  position: relative;
  text-decoration: none;
  color: white;
  padding: 14px 16px;
  display: block;
  font-size: 14px;
  font-weight: bold;
  text-transform: uppercase;
  transition: color 0.3s ease;
}

.navbar a::after {
  content: "";
  position: absolute;
  bottom: 6px; /* space below text */
  left: 50%;
  transform: translateX(-50%) scaleX(0);
  transform-origin: center;
  width: 60%;  /* underline is 60% of the word width */
  height: 3px;
  background-color: white;
  transition: transform 0.3s ease;
}

.navbar a:hover::after,
.navbar a.active::after {
  transform: translateX(-50%) scaleX(1);
}

    .navbar a:hover {
      transform: scale(1.09);
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
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #ec97ec;
            color: #5e1b5e;
        }
    </style>
</head>
<body>

<header class="header">
    <img src="images/whiteLogo.png" alt="Logo" style="width: 160px;">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['user_Name']) ?>!</p>
</header>

<div class="navbar">
    <a href="adminDashboard.php">Dashboard</a>
    <a href="adminUsers.php">Manage Users</a>
    <a href="adminNotes.php">Manage Notes</a>
    <a href="adminReports.php">Reported Content</a>
    <a href="adminFeedback.php" class="active">Feedback</a>
    <a href="logout.php">Log out</a>
</div>

<div class="container">
    <h2>Feedback</h2>
    <table>
        <tr>
            <th>Review ID</th>
            <th>Note Title</th>
            <th>Rating</th>
            <th>Feedback</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['review_ID'] ?></td>
                <td><?= htmlspecialchars($row['note_Name']) ?: 'Unknown' ?></td>
                <td><?= $row['rating'] ?>/5</td>
                <td><?= $row['review_text'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
