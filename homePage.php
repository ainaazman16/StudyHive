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

  <!-- Recommendations -->
  <div class="card">
    <h3>Recommendations</h3>
    <p>Coming soon: Smart recommendations based on your downloads and friends.</p>
  </div>

  <!-- Connection -->
  <div class="card">
    <h3>Connections</h3>
    <p>Check your friend list, pending requests, or find new users.</p>
    <a href="connectionPage.php">Manage Connections →</a>
  </div>

  <!-- Recently Viewed -->
  <div class="card recently-viewed">
    <h3>Recently Viewed</h3>
    <p>Coming soon: View your most recent note visits with quick access links.</p>
  </div>
</div>

<?php include('footer.php'); ?>
</body>
</html>
