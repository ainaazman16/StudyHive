<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

$userID = $_SESSION['user_ID'];

// Fetch user name
$userQuery = $conn->prepare("SELECT user_Fname FROM user WHERE user_ID = ?");
$userQuery->bind_param("i", $userID);
$userQuery->execute();
$userResult = $userQuery->get_result();
$userData = $userResult->fetch_assoc();

// My Notes
$myNotes = $conn->prepare("SELECT note_ID, note_Name, upload_date, download_count, file_type 
                           FROM notes 
                           WHERE user_ID = ? 
                           ORDER BY upload_date DESC 
                           LIMIT 3");
$myNotes->bind_param("i", $userID);
$myNotes->execute();
$myNotesResult = $myNotes->get_result();

// Recommendations
$subjectSQL = "
  SELECT DISTINCT s.subject_ID
  FROM notes n
  LEFT JOIN subject s ON n.subject_ID = s.subject_ID
  LEFT JOIN note_downloads d ON n.note_ID = d.note_ID
  WHERE n.user_ID = ? OR d.user_ID = ?
";
$subjectStmt = $conn->prepare($subjectSQL);
$subjectStmt->bind_param("ii", $userID, $userID);
$subjectStmt->execute();
$subjectResult = $subjectStmt->get_result();

$subjectIDs = [];
while ($row = $subjectResult->fetch_assoc()) {
    $subjectIDs[] = $row['subject_ID'];
}
$subjectStmt->close();

if (count($subjectIDs) > 0) {
    $inClause = implode(',', array_fill(0, count($subjectIDs), '?'));
    $types = str_repeat('i', count($subjectIDs));
    $sql = "SELECT note_Name, note_ID FROM notes WHERE subject_ID IN ($inClause) AND user_ID != ? ORDER BY upload_date DESC LIMIT 3";
    $recStmt = $conn->prepare($sql);
    $params = array_merge($subjectIDs, [$userID]);
    $recStmt->bind_param($types . "i", ...$params);
} else {
    $sql = "SELECT note_Name, note_ID FROM notes ORDER BY download_count DESC LIMIT 3";
    $recStmt = $conn->prepare($sql);
}
$recStmt->execute();
$recommendations = $recStmt->get_result();

// Friends
$friendQuery = $conn->prepare("SELECT u.user_Fname, u.user_Name 
                               FROM user_friends f
                               JOIN user u ON u.user_ID = f.friend_ID
                               WHERE f.user_ID = ? AND f.status = 'accepted'
                               ORDER BY f.friend_date DESC LIMIT 3");
$friendQuery->bind_param("i", $userID);
$friendQuery->execute();
$friendsResult = $friendQuery->get_result();

// Recently Viewed
$viewQuery = $conn->prepare("SELECT n.note_Name, n.note_ID, MAX(v.view_date) as last_view
                             FROM note_views v
                             JOIN notes n ON v.note_ID = n.note_ID
                             WHERE v.user_ID = ?
                             GROUP BY n.note_ID
                             ORDER BY last_view DESC
                             LIMIT 3");
$viewQuery->bind_param("i", $userID);
$viewQuery->execute();
$viewedResult = $viewQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home - StudyHive</title>
  <link rel="stylesheet" >
  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    h1 {
      font-size: 40px;
      text-align: center;
      color: #4b004b;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      margin-top: 10px;
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
      padding: 30px;
      max-width: 1300px;
      margin: 0 auto;
    }

    .card {
      display: flex;
      flex-direction: column;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      border-radius: 5px;
      background-color: #fff;
      height: 250px;
      padding: 15px;
      transition: transform 0.2s ease;
    }

    .card h3 {
      margin-top: 0;
      font-size: 18px;
      color: #4b004b;
    }

    .card ul {
      padding-left: 20px;
      margin-top: 10px;
      font-size: 14px;
    }

    .card ul li {
      margin-bottom: 5px;
    }

    .card-link {
      text-decoration: none;
      color: inherit;
    }

    .card-link:hover .card {
      transform: scale(1.02);
      box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .card.recently-viewed {
      grid-column: 1 / -1;
      max-width: 600px;
      justify-self: center;
    }

    @media screen and (max-width: 1000px) {
      .cards-container {
        grid-template-columns: repeat(2, 1fr);
      }
      .card.recently-viewed {
        grid-column: 1 / -1;
      }
    }

    @media screen and (max-width: 600px) {
      .cards-container {
        grid-template-columns: 1fr;
      }
    }

    .footer {
      background-color: #660066;
      color: white;
      text-align: center;
      padding: 10px;
      margin-top: 40px;
    }
  </style>
</head>
<body>
<?php include('head.php'); ?>

<h1>User's Dashboard</h1>

<div class="cards-container">
  <!-- My Notes (Clickable) -->
  <a href="mynotesPage.php" class="card-link">
    <div class="card">
      <h3>My Notes</h3>
      <ul>
        <?php if ($myNotesResult->num_rows > 0): ?>
          <?php while ($note = $myNotesResult->fetch_assoc()): ?>
            <li><?= htmlspecialchars($note['note_Name']) ?> (<?= strtoupper($note['file_type']) ?>, <?= $note['download_count'] ?> downloads)</li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>You haven’t uploaded any notes yet.</li>
        <?php endif; ?>
      </ul>
    </div>
  </a>

  <!-- Recommendations (Not Clickable) -->
  <div class="card">
    <h3>Recommendations</h3>
    <ul>
      <?php if ($recommendations->num_rows > 0): ?>
        <?php while ($rec = $recommendations->fetch_assoc()): ?>
          <li><?= htmlspecialchars($rec['note_Name']) ?></li>
        <?php endwhile; ?>
      <?php else: ?>
        <li>No recommendations available.</li>
      <?php endif; ?>
    </ul>
  </div>

  <!-- Connections (Clickable) -->
  <a href="connectionPage.php" class="card-link">
    <div class="card">
      <h3>Connections</h3>
      <ul>
        <?php if ($friendsResult->num_rows > 0): ?>
          <?php while ($friend = $friendsResult->fetch_assoc()): ?>
            <li><?= htmlspecialchars($friend['user_Fname']) ?><br><small>@<?= htmlspecialchars($friend['user_Name']) ?></small></li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>No friends yet.</li>
        <?php endif; ?>
      </ul>
    </div>
  </a>

  <!-- Recently Viewed (Not Clickable) -->
  <div class="card recently-viewed">
    <h3>Recently Viewed</h3>
    <ul>
      <?php if ($viewedResult->num_rows > 0): ?>
        <?php while ($view = $viewedResult->fetch_assoc()): ?>
          <li><?= htmlspecialchars($view['note_Name']) ?></li>
        <?php endwhile; ?>
      <?php else: ?>
        <li>You haven't viewed any notes yet.</li>
      <?php endif; ?>
    </ul>
  </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
