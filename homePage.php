<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

$userID = $_SESSION['user_ID'];

// Fetch last 3 uploaded notes by this user
$myNotes = $conn->prepare("SELECT note_ID, note_Name, upload_date, download_count, file_type 
                           FROM notes 
                           WHERE user_ID = ? 
                           ORDER BY upload_date DESC 
                           LIMIT 3");
$myNotes->bind_param("i", $userID);
$myNotes->execute();
$myNotesResult = $myNotes->get_result();

// Get user's recent subject interests (from uploads and downloads)
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
    // Recommend based on subjects
    $inClause = implode(',', array_fill(0, count($subjectIDs), '?'));
    $types = str_repeat('i', count($subjectIDs));
    $sql = "SELECT note_Name, note_ID FROM notes WHERE subject_ID IN ($inClause) AND user_ID != ? ORDER BY upload_date DESC LIMIT 3";
    $recStmt = $conn->prepare($sql);
    $params = array_merge($subjectIDs, [$userID]);
    $recStmt->bind_param($types . "i", ...$params);
} else {
    // Recommend top downloads as fallback
    $sql = "SELECT note_Name, note_ID FROM notes ORDER BY download_count DESC LIMIT 3";
    $recStmt = $conn->prepare($sql);
}
$recStmt->execute();
$recommendations = $recStmt->get_result();

// Fetch recent friends (accepted connections)
$friendQuery = $conn->prepare("
  SELECT u.user_Fname, u.user_Name 
  FROM user_friends f
  JOIN user u ON u.user_ID = f.friend_ID
  WHERE f.user_ID = ? AND f.status = 'accepted'
  ORDER BY f.friend_date DESC LIMIT 3
");
$friendQuery->bind_param("i", $userID);
$friendQuery->execute();
$friendsResult = $friendQuery->get_result();

// Fetch recent uploads
$userID = $_SESSION['user_ID'];
$recentUploads = $conn->prepare("SELECT note_Name, upload_date FROM notes WHERE user_ID = ? ORDER BY upload_date DESC LIMIT 3");
$recentUploads->bind_param("i", $userID);
$recentUploads->execute();
$uploadResult = $recentUploads->get_result();
// Get user's recent subject interests (from uploads and downloads)
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
    // Recommend based on subjects
    $inClause = implode(',', array_fill(0, count($subjectIDs), '?'));
    $types = str_repeat('i', count($subjectIDs));
    $sql = "SELECT note_Name, note_ID FROM notes WHERE subject_ID IN ($inClause) AND user_ID != ? ORDER BY upload_date DESC LIMIT 3";
    $recStmt = $conn->prepare($sql);
    $params = array_merge($subjectIDs, [$userID]);
    $recStmt->bind_param($types . "i", ...$params);
} else {
    // Recommend top downloads as fallback
    $sql = "SELECT note_Name, note_ID FROM notes ORDER BY download_count DESC LIMIT 3";
    $recStmt = $conn->prepare($sql);
}
$recStmt->execute();
$recommendations = $recStmt->get_result();

// Get user's recently viewed notes
$viewQuery = $conn->prepare("
  SELECT n.note_Name, n.note_ID, MAX(v.view_date) as last_view
  FROM note_views v
  JOIN notes n ON v.note_ID = n.note_ID
  WHERE v.user_ID = ?
  GROUP BY n.note_ID
  ORDER BY last_view DESC
  LIMIT 3
");
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
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    h1 {
      font-size: 60px;
      text-align: center;
      color: #4b004b;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      margin-top: 30px;
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
      justify-content: flex-start;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: 0.3s;
      border-radius: 5px;
      background-color: #fff;
      height: 250px;
      width: 100%;
      padding: 15px;
    }

    .card:hover {
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
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

    .card a {
      color: #660066;
      text-decoration: underline;
      font-weight: bold;
      font-size: 13px;
    }

    /* Recently Viewed Card Styling */
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
        width: 100%;
        grid-column: 1 / -1;
      }
    }

    @media screen and (max-width: 600px) {
      .cards-container {
        grid-template-columns: 1fr;
      }

      .card.recently-viewed {
        width: 100%;
        grid-column: auto;
      }
    }

    .footer {
      color: white;
      text-align: center;
      padding: 20px 0;
      font-family: Arial, sans-serif;
      font-size: 14px;
      box-sizing: border-box;
    }

    .footer h4 {
      margin: 0;
      font-weight: normal;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body>
<?php include('head.php'); ?>

<h1>User's Dashboard</h1>

<div class="cards-container">
  <!-- My Notes -->
  <div class="card">
    <h3>My Notes</h3>
    <?php if ($myNotesResult->num_rows > 0): ?>
      <ul>
        <?php while ($note = $myNotesResult->fetch_assoc()): ?>
          <li>
            <?= htmlspecialchars($note['note_Name']) ?> 
            (<?= strtoupper($note['file_type']) ?>, <?= $note['download_count'] ?> downloads)<br>
            <small style="color:#555;">Uploaded: <?= $note['upload_date'] ?></small>
          </li>
        <?php endwhile; ?>
      </ul>
      <a href="mynotesPage.php">View All →</a>
    <?php else: ?>
      <p>You haven’t uploaded any notes yet.</p>
      <a href="uploadPage.php">Upload Now →</a>
    <?php endif; ?>
  </div>

    <!-- Recommendations Card -->
    <div class="card">
      <div class="container">
        <div>
          <h3><b>Recommendations</b></h3>
          <ul style="margin-top: 10px; padding-left: 15px;">
            <?php if ($recommendations->num_rows > 0): ?>
              <?php while ($rec = $recommendations->fetch_assoc()): ?>
                <li style="font-size: 13px;">
                  <?= htmlspecialchars($rec['note_Name']) ?>
                  <a href="download.php?note_ID=<?= $rec['note_ID'] ?>" style="font-size:12px; color:#660066;">[Download]</a>
                </li>
              <?php endwhile; ?>
            <?php else: ?>
              <li>No recommendations available.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>


 <!-- Connection Card -->
<div class="card">
  <div class="container">
    <div>
      <h3><b>Connection</b></h3>
      <ul style="margin-top: 10px; padding-left: 15px;">
        <?php if ($friendsResult->num_rows > 0): ?>
          <?php while ($friend = $friendsResult->fetch_assoc()): ?>
            <li style="font-size: 13px;">
              <?= htmlspecialchars($friend['user_Fname']) ?>
              <br><small>@<?= htmlspecialchars($friend['user_Name']) ?></small>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>No friends yet.</li>
        <?php endif; ?>
      </ul>
      <a href="connectionPage.php" class="btn" style="margin-top: 10px;">Manage</a>
    </div>
  </div>
</div>


 <!-- Recently Viewed Card -->
<div class="card recently-viewed">
  <div class="container">
    <div>
      <h3><b>Recently Viewed</b></h3>
      <ul style="margin-top: 10px; padding-left: 15px;">
        <?php if ($viewedResult->num_rows > 0): ?>
          <?php while ($view = $viewedResult->fetch_assoc()): ?>
            <li style="font-size: 13px;">
              <?= htmlspecialchars($view['note_Name']) ?>
              <a href="download.php?note_ID=<?= $view['note_ID'] ?>" style="font-size:12px; color:#660066;">[Open]</a>
            </li>
          <?php endwhile; ?>
        <?php else: ?>
          <li>You haven't viewed any notes yet.</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

 <?php include('footer.php'); ?>
</body>
</html>
