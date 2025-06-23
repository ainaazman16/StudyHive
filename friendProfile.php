<?php
session_start();
include('connect.php');

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

if (!isset($_GET['friend_ID'])) {
    die("Friend ID not provided.");
}

$friendID = intval($_GET['friend_ID']);

// Fetch friend's info
$stmt = $conn->prepare("SELECT user_Fname, user_Name, profile_picture FROM user WHERE user_ID = ?");
if (!$stmt) {
    die("Friend info query failed: " . $conn->error);
}
$stmt->bind_param("i", $friendID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Friend not found.");
}
$friend = $result->fetch_assoc();
$stmt->close();

// Fetch friend's notes
$stmt2 = $conn->prepare("SELECT note_Name, file_type, upload_date, download_count FROM notes WHERE user_ID = ?");
if (!$stmt2) {
    die("Note query failed: " . $conn->error);
}
$stmt2->bind_param("i", $friendID);
$stmt2->execute();
$notesResult = $stmt2->get_result();
$stmt2->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($friend['user_Fname']) ?>'s Profile</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f9f9f9;
      font-family: Arial, sans-serif;
    }
    .section {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      text-align: center;
    }
    .section h1 {
      margin-bottom: 20px;
    }
    .profile-pic {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
      border: 2px solid #ddd;
    }
    .notes-container {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    .note-card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      text-align: left;
    }
    .note-card h3 {
      margin: 0 0 8px;
      color: #333;
    }
    .note-card small {
      color: #666;
    }
  </style>
</head>
<body>
<?php include('head.php'); ?>

<div class="section">
  <h1><?= htmlspecialchars($friend['user_Fname']) ?>'s Profile</h1>


  <h2>Uploaded Notes</h2>
  <div class="notes-container">
    <?php if ($notesResult->num_rows > 0): ?>
      <?php while ($note = $notesResult->fetch_assoc()): ?>
        <div class="note-card">
          <h3><?= htmlspecialchars($note['note_Name']) ?></h3>
          <p>Type: <?= htmlspecialchars($note['file_type']) ?></p>
          <small>Uploaded: <?= htmlspecialchars($note['upload_date']) ?></small><br>
          <small>Downloads: <?= htmlspecialchars($note['download_count']) ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>This user has not uploaded any notes yet.</p>
    <?php endif; ?>
  </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
