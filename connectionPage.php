<?php
// connectionPage.php
session_start();
include('connect.php');

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}


$userID = $_SESSION['user_ID'];

// Handle actions: send request, accept, decline, remove, cancel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['target_ID'])) {
        $targetID = intval($_POST['target_ID']);
        if ($_POST['action'] === 'send') {
            $stmt = $conn->prepare("INSERT INTO user_friends (user_ID, friend_ID, status, friend_date)
                                    VALUES (?, ?, 'pending', NOW())");
            $stmt->bind_param('ii', $userID, $targetID);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'accept') {
            $stmt = $conn->prepare("UPDATE user_friends SET status='accepted', friend_date=NOW() WHERE user_ID=? AND friend_ID=?");
            $stmt->bind_param('ii', $targetID, $userID);
            $stmt->execute();
            $stmt->close();
            $stmt2 = $conn->prepare("INSERT INTO user_friends (user_ID, friend_ID, status, friend_date) VALUES (?, ?, 'accepted', NOW())");
            $stmt2->bind_param('ii', $userID, $targetID);
            $stmt2->execute();
            $stmt2->close();
        } elseif ($_POST['action'] === 'decline') {
            $stmt = $conn->prepare("DELETE FROM user_friends WHERE user_ID=? AND friend_ID=?");
            $stmt->bind_param('ii', $targetID, $userID);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'remove') {
            $stmt = $conn->prepare("DELETE FROM user_friends WHERE (user_ID=? AND friend_ID=?) OR (user_ID=? AND friend_ID=?)");
            $stmt->bind_param('iiii', $userID, $targetID, $targetID, $userID);
            $stmt->execute();
            $stmt->close();
        } elseif ($_POST['action'] === 'cancel') {
            $stmt = $conn->prepare("DELETE FROM user_friends WHERE user_ID=? AND friend_ID=? AND status='pending'");
            $stmt->bind_param('ii', $userID, $targetID);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Fetch existing friends
$friends = $conn->prepare("SELECT u.user_ID, u.user_Fname, u.user_Name FROM user u
    JOIN user_friends uf ON u.user_ID = uf.friend_ID
    WHERE uf.user_ID = ? AND uf.status = 'accepted'");
$friends->bind_param('i', $userID);
$friends->execute(); $friendsResult = $friends->get_result(); $friends->close();

// Fetch incoming requests
$incoming = $conn->prepare("
  SELECT u.user_ID, u.user_Fname, u.user_Name 
  FROM user u
  JOIN user_friends uf ON u.user_ID = uf.user_ID
  WHERE uf.friend_ID = ? AND uf.status = 'pending'
");
$incoming->bind_param('i', $userID);
$incoming->execute();
$incomingResult = $incoming->get_result();
$incoming->close();


// Search filter
$search = $_GET['search'] ?? '';

// Fetch potential users to add (excluding self and existing/pending relations)
$potentialQuery = "
  SELECT user_ID, user_Fname, user_Name FROM user
  WHERE user_ID != ?
  AND user_ID NOT IN (
    SELECT friend_ID FROM user_friends WHERE user_ID = ?
    UNION
    SELECT user_ID FROM user_friends WHERE friend_ID = ?
  )
";

if (!empty($search)) {
    $potentialQuery .= " AND (user_Fname LIKE ? OR user_Name LIKE ?)";
}
$potential = $conn->prepare($potentialQuery);
if (!empty($search)) {
    $like = "%$search%";
    $potential->bind_param('iiiss', $userID, $userID, $userID, $like, $like);
} else {
    $potential->bind_param('iii', $userID, $userID, $userID);
}
$potential->execute(); $potentialResult = $potential->get_result(); $potential->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Connections - StudyHive</title>
  <link rel="stylesheet" href="style.css">
      <link rel="icon" type="image/png" href="images/logo.png">
  <style>
    .section { max-width:600px; margin:20px auto; }
    h2 { color:#660066; }
    ul { list-style:none; padding:0; }
    li { padding:8px; background:#f9f9f9; margin-bottom:6px; border-radius:4px; display:flex; justify-content:space-between; }
    button { padding:5px 10px; border:none; background:#660066; color:#fff; border-radius:4px; cursor:pointer; }
    button.decline { background:#aa0033; }
    button.remove { background:#999; } 
   
 

.search-bar form {
  display: flex;
  gap: 200px; /* space between input and button */
  align-items: center;
}

.search-bar input[type="text"] {
  padding: 5px;
  border-radius: 4px;
  border: 1px solid #ccc;
  width: 250px;
}



    
    
  </style>
</head>
<body>
<?php include('head.php'); ?>
<div class="section">
  <h1>Connections</h1>
  <div class="section">
  <h2>Incoming Friend Requests</h2>
  <ul>
    <?php while($r = $incomingResult->fetch_assoc()): ?>
      <li>     
       <a href="friendProfile.php?friend_ID=<?= $r['user_ID'] ?>">
        <?= htmlspecialchars($r['user_Fname']) ?> (<?= htmlspecialchars($r['user_Name']) ?>)
      </a>

        <div>
          <form method="post" style="display:inline;">
            <input type="hidden" name="action" value="accept">
            <input type="hidden" name="target_ID" value="<?= $r['user_ID'] ?>">
            <button type="submit">Accept</button>
          </form>
          <form method="post" style="display:inline;">
            <input type="hidden" name="action" value="decline">
            <input type="hidden" name="target_ID" value="<?= $r['user_ID'] ?>">
            <button type="submit" class="decline">Decline</button>
          </form>
        </div>
      </li>
    <?php endwhile; ?>
    <?php if ($incomingResult->num_rows === 0): ?>
      <li>No incoming requests.</li>
    <?php endif; ?>
  </ul>
</div>

</div>

<div class="section">
  <h2>Your Friends</h2>
  <ul>
    <?php while($f = $friendsResult->fetch_assoc()): ?>
      <li>
        <a href="friendProfile.php?friend_ID=<?= $f['user_ID'] ?>">
        <?= htmlspecialchars($f['user_Fname']) ?> (<?= htmlspecialchars($f['user_Name']) ?>)
      </a>
        <form method="post">
          <input type="hidden" name="action" value="remove">
          <input type="hidden" name="target_ID" value="<?= $f['user_ID'] ?>">
          <button type="submit" style="background:#dc3545; "class="remove">Remove</button>
        </form>
      </li>
    <?php endwhile; ?>
    <?php if ($friendsResult->num_rows === 0) echo '<li>You have no friends yet.</li>'; ?>
  </ul>
</div>

<div class="section">
  <h2>Find People</h2>
  <div class="search-bar">
    <form method="get">
      <input type="text" name="search" placeholder="Search by name or username..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>
  </div>
  <ul>
    <?php while($p = $potentialResult->fetch_assoc()): ?>
      <li>
        <?= htmlspecialchars($p['user_Fname']) ?> (<?= htmlspecialchars($p['user_Name']) ?>)
        <form method="post">
          <input type="hidden" name="action" value="send">
          <input type="hidden" name="target_ID" value="<?= $p['user_ID'] ?>">
          <button type="submit">Send Request</button>
        </form>
      </li>
    <?php endwhile; ?>
    <?php if ($potentialResult->num_rows === 0) echo '<li>No users found.</li>'; ?>
  </ul>
</div>

<div class="section">
  <h2>Pending Requests You've Sent</h2>
  <ul>
    <?php
    $pending = $conn->prepare("SELECT u.user_ID, u.user_Fname, u.user_Name FROM user u
        JOIN user_friends uf ON u.user_ID = uf.friend_ID
        WHERE uf.user_ID = ? AND uf.status = 'pending'");
    $pending->bind_param('i', $userID);
    $pending->execute();
    $pendingResult = $pending->get_result();
    $pending->close();
    ?>
    <?php while($p = $pendingResult->fetch_assoc()): ?>
      <li>
        <?= htmlspecialchars($p['user_Fname']) ?> (<?= htmlspecialchars($p['user_Name']) ?>)
        <form method="post">
          <input type="hidden" name="action" value="cancel">
          <input type="hidden" name="target_ID" value="<?= $p['user_ID'] ?>">
          <button type="submit"style="background:#dc3545;" class="remove">Cancel Request</button>
        </form>
      </li>
    <?php endwhile; ?>
    <?php if ($pendingResult->num_rows === 0) echo '<li>No pending requests.</li>'; ?>
  </ul>
</div>
<?php include('footer.php'); ?>
</body>
</html>
