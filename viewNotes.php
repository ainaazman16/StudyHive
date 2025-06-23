<?php
// ⚠️ Debug mode on (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

$userID = $_SESSION['user_ID'];
$search = $_GET['search'] ?? '';
$uni_ID = $_GET['university'] ?? '';
$course_ID = $_GET['course'] ?? '';
$subject_ID = $_GET['subject'] ?? '';

if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = '';
}

$conditions = [];
$params = [];
$types = '';

$sql = "SELECT n.note_ID, n.note_Name, n.user_ID, u.user_Fname, uni.uni_Name
        FROM notes n
        LEFT JOIN user u ON n.user_ID = u.user_ID
        LEFT JOIN subject s ON n.subject_ID = s.subject_ID
        LEFT JOIN course c ON s.course_ID = c.course_ID
        LEFT JOIN faculty f ON c.faculty_ID = f.faculty_ID
        LEFT JOIN university uni ON f.uni_ID = uni.uni_ID";

$queryString = "search=$search&university=$uni_ID&course=$course_ID&subject=$subject_ID";

if (!empty($search)) {
    $conditions[] = "(n.note_Name LIKE ? OR u.user_Fname LIKE ?)";
    $types .= 'ss';
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if (!empty($uni_ID)) {
    $conditions[] = "uni.uni_ID = ?";
    $types .= 'i';
    $params[] = $uni_ID;
}
if (!empty($course_ID)) {
    $conditions[] = "c.course_ID = ?";
    $types .= 'i';
    $params[] = $course_ID;
}
if (!empty($subject_ID)) {
    $conditions[] = "s.subject_ID = ?";
    $types .= 'i';
    $params[] = $subject_ID;
}

if ($conditions) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
$sql .= " ORDER BY n.upload_date DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ SQL Prepare Failed: " . $conn->error);
}
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
if (!method_exists($stmt, 'get_result')) {
    die("❌ get_result() not supported. Make sure PHP has mysqlnd enabled.");
}
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Browse Notes - StudyHive</title>
  <link rel="stylesheet" href="style.css">
  <style>
    h1 { text-align: center; color: #4b004b; }
    .note-card {
      max-width: 700px;
      margin: 20px auto;
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .note-card h3 { margin: 0 0 10px; }
    .btn {
      display: inline-block;
      padding: 8px 15px;
      background-color: #660066;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      margin-top: 10px;
    }
    .btn:hover { background-color: #990099; }
    .btn-delete { background-color: #dc3545; }
  </style>
</head>
<body>
<?php include("head.php"); ?>

<?php if (isset($_SESSION['success_message'])): ?>
  <div style="background: #d4edda; color: #155724; padding: 12px; margin: 20px auto; max-width: 800px; text-align: center; border: 1px solid #c3e6cb; border-radius: 5px;">
    <?= $_SESSION['success_message'] ?>
  </div>
  <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<h1>Browse Notes</h1>

<?php if ($result && $result->num_rows > 0): ?>
  <?php while($row = $result->fetch_assoc()): ?>
    <?php
      $isOwner = $row['user_ID'] == $userID;

      if (!$isOwner) {
          $helpfulCheck = $conn->prepare("SELECT 1 FROM note_helpful WHERE user_ID = ? AND note_ID = ?");
          $helpfulCheck->bind_param("ii", $userID, $row['note_ID']);
          $helpfulCheck->execute();
          $helpfulCheck->store_result();
          $alreadyHelpful = $helpfulCheck->num_rows > 0;
          $helpfulCheck->close();

          $countResult = $conn->query("SELECT COUNT(*) FROM note_helpful WHERE note_ID = {$row['note_ID']}");
          $helpfulTotal = $countResult ? $countResult->fetch_row()[0] : 0;
      }
    ?>

    <div class="note-card" style="background-color:<?= $isOwner ? '#f7f7ff' : '#ffffff' ?>">
      <h3><?= htmlspecialchars($row['note_Name']) ?></h3>
      <p><strong>Author:</strong> <?= htmlspecialchars($row['user_Fname']) ?></p>
      <p><strong>University:</strong> <?= htmlspecialchars($row['uni_Name']) ?></p>

      <?php if (!$isOwner): ?>
        <p><strong>Helpful Count:</strong> <?= $helpfulTotal ?></p>
      <?php endif; ?>

      <a href="noteDetails.php?note_ID=<?= $row['note_ID'] ?>" class="btn">View Note</a>

      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a class="btn btn-delete" href="adminDeleteNote.php?note_ID=<?= $row['note_ID'] ?>" onclick="return confirm('Are you sure you want to delete this note?');">Admin Delete</a>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p style="text-align:center; color:#888;">🔍 No notes found for your search/filter.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
</body>
</html>
