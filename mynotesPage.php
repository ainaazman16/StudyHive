<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

$userID = $_SESSION['user_ID'];

// Fetch uploaded notes
$uploaded = $conn->prepare("SELECT * FROM notes WHERE user_ID = ? ORDER BY upload_date DESC");
$uploaded->bind_param("i", $userID);
$uploaded->execute();
$uploadedNotes = $uploaded->get_result();
$uploaded->close();

// Fetch downloaded notes with download date
$downloaded = $conn->prepare("SELECT n.*, d.download_date FROM notes n
    JOIN note_downloads d ON n.note_ID = d.note_ID
    WHERE d.user_ID = ?
    ORDER BY d.download_date DESC");
$downloaded->bind_param("i", $userID);
$downloaded->execute();
$downloadedNotes = $downloaded->get_result();
$downloaded->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes</title>
</head>
<body>
    
</body>
</html>
  <meta charset="UTF-8">
  <title>My Notes - StudyHive</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .section { max-width:800px; margin:20px auto; padding:10px; }
    h2 { color:#660066; }
    .note-card {
      background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:12px;
      border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.05);
    }
    .note-card h3 { margin:0 0 8px; }
    .btn { padding:6px 12px; background:#660066; color:#fff; text-decoration:none; border-radius:5px; display:inline-block; margin-top:8px; }
  </style>
</head>
<body>
<?php include("head.php"); ?>

<div class="section">
  <h2>Notes You've Uploaded</h2>
  <?php if ($uploadedNotes->num_rows > 0): ?>
    <?php while($note = $uploadedNotes->fetch_assoc()): ?>
      <div class="note-card">
        <h3><?= htmlspecialchars($note['note_Name']) ?></h3>
        <p><strong>Type:</strong> <?= strtoupper($note['file_type']) ?></p>
        <p><strong>Uploaded:</strong> <?= $note['upload_date'] ?></p>
        <p><strong>Downloads:</strong> <?= $note['download_count'] ?></p>
        <a class="btn" href="download.php?note_ID=<?= $note['note_ID'] ?>">Download</a>
        <a class="btn" href="editNote.php?note_ID=<?= $note['note_ID'] ?>" style="background:#007bff;">Edit</a>
        <a class="btn" href="deleteNote.php?note_ID=<?= $note['note_ID'] ?>" style="background:#dc3545;" onclick="return confirm('Are you sure you want to delete this note?');">Delete</a>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No uploaded notes yet.</p>
  <?php endif; ?>
</div>

<div class="section">
  <h2>Notes You've Downloaded</h2>
  <?php if ($downloadedNotes->num_rows > 0): ?>
    <?php while($note = $downloadedNotes->fetch_assoc()): ?>
      <div class="note-card">
        <h3><?= htmlspecialchars($note['note_Name']) ?></h3>
        <p><strong>Type:</strong> <?= strtoupper($note['file_type']) ?></p>
        <p><strong>Downloaded:</strong> <?= $note['download_date'] ?></p>
        <a class="btn" href="download.php?note_ID=<?= $note['note_ID'] ?>">Download Again</a>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>You haven't downloaded any notes yet.</p>
  <?php endif; ?>
</div>
</body>
</html>
