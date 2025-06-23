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

$search = $_GET['search'] ?? '';
$uni_ID = $_GET['uni_ID'] ?? '';
$faculty_ID = $_GET['faculty_ID'] ?? '';
$course_ID = $_GET['course_ID'] ?? '';
$subject_ID = $_GET['subject_ID'] ?? '';

if (!isset($_SESSION['role'])) {
  $_SESSION['role'] = '';
}

$conditions = [];
$params = [];
$types = '';

$sql = "SELECT n.note_ID, n.note_Name, n.file_type, n.upload_date, u.user_Fname
        FROM notes n
        LEFT JOIN user u ON n.user_ID = u.user_ID
        LEFT JOIN subject s ON n.subject_ID = s.subject_ID
        LEFT JOIN course c ON s.course_ID = c.course_ID
        LEFT JOIN faculty f ON c.faculty_ID = f.faculty_ID
        LEFT JOIN university uni ON f.uni_ID = uni.uni_ID";

$queryString = http_build_query([
    'search' => $search,
    'uni_ID' => $uni_ID,
    'faculty_ID' => $faculty_ID,
    'course_ID' => $course_ID,
    'subject_ID' => $subject_ID
]);

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
if (!empty($faculty_ID)) {
    $conditions[] = "f.faculty_ID = ?";
    $types .= 'i';
    $params[] = $faculty_ID;
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

// 🔍 Prepare and check query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ SQL Prepare Failed: " . $conn->error);
}

// 🔗 Bind if necessary
if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

// 🔎 Check if get_result is available
if (!method_exists($stmt, 'get_result')) {
    die("❌ get_result() not supported. Make sure PHP has mysqlnd enabled.");
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Filtered Notes - StudyHive</title>
  <link rel="stylesheet" href="style.css">
  <style>
    h1 { text-align: center; color: #660066; }
    .note-card {
      max-width: 700px; margin: 20px auto;
      background-color: #fff; border: 1px solid #ccc;
      padding: 20px; border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .note-card h3 { margin: 0 0 10px; }
    .btn {
      display: inline-block;
      padding: 8px 15px; background-color: #660066;
      color: white; text-decoration: none;
      border-radius: 6px; margin-top: 10px;
    }
    .btn-report{
      display: inline-block;
      padding: 8px 15px; background-color:rgb(237, 50, 50);
      color: white; text-decoration: none;
      border-radius: 6px; margin-top: 10px;
    }
    .btn-rate{
      display: inline-block;
      padding: 8px 15px; background-color:rgb(227, 139, 206);
      color: white; text-decoration: none;
      border-radius: 6px; margin-top: 10px;
    }
    .btn:hover { background-color: #990099; }
  </style>
</head>
<body>
<?php include("head.php"); ?>

<h1>Browse Notes</h1>

<?php if ($result && $result->num_rows > 0): ?>
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="note-card">
      <h3><?= htmlspecialchars($row['note_Name']) ?></h3>
      <p><strong>Type:</strong> <?= strtoupper($row['file_type']) ?></p>
      <p><strong>Author:</strong> <?= htmlspecialchars($row['user_Fname']) ?></p>
      <p><strong>Uploaded:</strong> <?= $row['upload_date'] ?></p>
      <a class="btn" href="download.php?note_ID=<?= $row['note_ID'] ?>">Download</a>
      <a class="btn" href="rateNotes.php?note_ID=<?= $row['note_ID'] ?>">Rate Notes</a>
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a class="btn" style="background-color: #aa0033;" href="adminDeleteNote.php?note_ID=<?= $row['note_ID'] ?>">Delete</a>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p style="text-align:center; color:#888;">🔍 No notes found for your search/filter.</p>
<?php endif; ?>

</body>
</html>
