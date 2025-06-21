<?php
session_start();
include("connect.php");

// Capture filter and search parameters
$search = $_GET['search'] ?? '';
$uni_ID = $_GET['uni_ID'] ?? '';
$faculty_ID = $_GET['faculty_ID'] ?? '';
$course_ID = $_GET['course_ID'] ?? '';
$subject_ID = $_GET['subject_ID'] ?? '';

// Capture role from session after login
if (!isset($_SESSION['role'])) {
  $_SESSION['role'] = '';
}

// Build dynamic SQL with filters
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

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Filtered Notes - StudyHive</title>
  <link rel="stylesheet" href="style.css">
  <style>
    h1 {
      text-align: center;
      color: #660066;
    }
    .filter-bar, .note-card {
      max-width: 700px;
      margin: 20px auto;
    }
    .filter-bar form {
      background-color: #fff0ff;
      padding: 20px;
      border-radius: 8px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .filter-bar input, .filter-bar select {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 100%;
    }
    .filter-bar button {
      background-color: #660066;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .note-card {
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .note-card h3 {
      margin: 0 0 10px;
    }
    .btn {
      display: inline-block;
      padding: 8px 15px;
      background-color: #660066;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      margin-top: 10px;
    }
    .btn:hover {
      background-color: #990099;
    }
  </style>
</head>
<body>

<?php include('head.php'); ?>

<h1>Find Notes</h1>
<div class="filter-bar">
  <form method="get">
    <input type="text" name="search" placeholder="Search notes..." value="<?= htmlspecialchars($search) ?>">

    <select name="uni_ID">
      <option value="">All Universities</option>
      <?php
      $q = $conn->query("SELECT * FROM university");
      while ($row = $q->fetch_assoc()) {
        $selected = ($row['uni_ID'] == $uni_ID) ? 'selected' : '';
        echo "<option value='{$row['uni_ID']}' $selected>{$row['uni_Name']}</option>";
      }
      ?>
    </select>

    <select name="faculty_ID">
      <option value="">All Faculties</option>
      <?php
      $q = $conn->query("SELECT * FROM faculty");
      while ($row = $q->fetch_assoc()) {
        $selected = ($row['faculty_ID'] == $faculty_ID) ? 'selected' : '';
        echo "<option value='{$row['faculty_ID']}' $selected>{$row['faculty_Name']}</option>";
      }
      ?>
    </select>

    <select name="course_ID">
      <option value="">All Courses</option>
      <?php
      $q = $conn->query("SELECT * FROM course");
      while ($row = $q->fetch_assoc()) {
        $selected = ($row['course_ID'] == $course_ID) ? 'selected' : '';
        echo "<option value='{$row['course_ID']}' $selected>{$row['course_Name']}</option>";
      }
      ?>
    </select>

    <select name="subject_ID">
      <option value="">All Subjects</option>
      <?php
      $q = $conn->query("SELECT * FROM subject");
      while ($row = $q->fetch_assoc()) {
        $selected = ($row['subject_ID'] == $subject_ID) ? 'selected' : '';
        echo "<option value='{$row['subject_ID']}' $selected>{$row['subject_Name']}</option>";
      }
      ?>
    </select>

    <button type="submit">Apply Filter</button>
  </form>
</div>

<?php if ($result->num_rows > 0): ?>
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="note-card">
      <h3><?= htmlspecialchars($row['note_Name']) ?></h3>
      <p><strong>Type:</strong> <?= strtoupper($row['file_type']) ?></p>
      <p><strong>Author:</strong> <?= htmlspecialchars($row['user_Fname']) ?></p>
      <p><strong>Uploaded:</strong> <?= $row['upload_date'] ?></p>
      <a class="btn" href="download.php?note_ID=<?= $row['note_ID'] ?>">Download</a>
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a class="btn" href="adminDeleteNote.php?note_ID=<?= $row['note_ID'] ?>" style="background-color:#aa0033">Delete</a>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p style="text-align:center; color:#888;">No notes found.</p>
<?php endif; ?>

</body>
</html>
