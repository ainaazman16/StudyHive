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
$stmt = $conn->prepare("SELECT user_Fname, user_Name FROM user WHERE user_ID = ?");
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
    .note-card {
      background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:12px;
      border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.05);
    }
    .note-card h3 { margin:0 0 8px; }
  </style>
</head>
<body>
<?php include('head.php'); ?>

<div class="section">
  <h1><?= htmlspecialchars($friend['user_Fname']) ?>'s Profile</h1>

  <!-- Profile Picture -->
  <?php if (!empty($friend['profile_picture'])): ?>
    <img src="<?= htmlspecialchars($friend['profile_picture']) ?>" alt="Profile Picture" width="100">
  <?php endif; ?>

  <h2>Uploaded Notes</h2>
  <?php if ($notesResult->num_rows > 0): ?>
    <ul>
      <?php while ($note = $notesResult->fetch_assoc()): ?>
        <div>
        <li>
          <strong><?= htmlspecialchars($note['note_Name']) ?></strong><br>
          <?= nl2br(htmlspecialchars($note['file_type'])) ?><br>
          <small><?= htmlspecialchars($note['upload_date']) ?></small>
        </li>
        </div>
        
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <p>This user has not uploaded any notes yet.</p>
  <?php endif; ?>
</div>

<?php include('footer.php'); ?>
</body>
</html>
