<?php
// adminPanel.php
session_start();
include("connect.php");

// Only admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - StudyHive</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { color: #660066; }
    .section { margin-bottom: 30px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
    th { background-color: #f0e6f8; }
    .btn { padding: 5px 10px; background-color: #660066; color: #fff; text-decoration: none; border-radius: 4px; }
    .btn.delete { background-color: #aa0033; }
    .btn:hover { opacity: 0.9; }

    .navbar {
      background-color: #660066;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 10px;
      height: 60px;
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
    }

    .navbar a:hover {
      background-color: #990099;
    }

    .topic {
      background-color: #ec97ec;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      font-size: 230%;
      text-decoration: none;
      color: #5e1b5e;
      text-align: center;
      height: 400px;
      padding-top: 5px;
      padding-bottom: 5px;
    }

    .topic img {
      width: 245px;
      height: auto;
      margin-bottom: 5px;
      margin-top: 5px;
    }
  </style>
  </style>
</head>
<body>

    <?php
    include('head.php');
    ?>
   <h1>Admin Panel</h1>

  <div class="section">
    <h2>Manage Users</h2>
    <?php
    $users = $conn->query("SELECT user_ID, user_Name, email, role FROM `user`");
    if ($users->num_rows > 0): ?>
      <table>
        <thead>
          <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php while($u = $users->fetch_assoc()): ?>
          <tr>
            <td><?= $u['user_ID'] ?></td>
            <td><?= htmlspecialchars($u['user_Name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>

          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No users found.</p>
    <?php endif; ?>
  </div>

  <div class="section">
    <h2>Manage Notes</h2>
    <?php
    $notes = $conn->query("SELECT note_ID, note_Name, user_ID, download_count FROM notes ORDER BY upload_date DESC");
    if ($notes->num_rows > 0): ?>
      <table>
        <thead>
          <tr><th>ID</th><th>Note Name</th><th>Uploader ID</th><th>Downloads</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php while($n = $notes->fetch_assoc()): ?>
          <tr>
            <td><?= $n['note_ID'] ?></td>
            <td><?= htmlspecialchars($n['note_Name']) ?></td>
            <td><?= $n['user_ID'] ?></td>
            <td><?= $n['download_count'] ?></td>
            <td>
              <a class="btn delete" href="adminDeleteNote.php?note_ID=<?= $n['note_ID'] ?>">Delete Note</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No notes found.</p>
    <?php endif; ?>
  </div>

</body>
</html>
