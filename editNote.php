<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

$userID = $_SESSION['user_ID'];
$noteID = $_GET['note_ID'] ?? null;

if (!$noteID) {
    die("Note ID missing.");
}

// Fetch note info
$stmt = $conn->prepare("SELECT * FROM notes WHERE note_ID = ? AND user_ID = ?");
$stmt->bind_param("ii", $noteID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Note not found or unauthorized.");
}

$note = $result->fetch_assoc();
$stmt->close();

// Fetch current subject hierarchy securely
$subjectID = $note['subject_ID'];
$currentStmt = $conn->prepare("
    SELECT s.subject_ID, s.course_ID, c.faculty_ID, f.uni_ID
    FROM subject s
    JOIN course c ON s.course_ID = c.course_ID
    JOIN faculty f ON c.faculty_ID = f.faculty_ID
    WHERE s.subject_ID = ?
");
$currentStmt->bind_param("i", $subjectID);
$currentStmt->execute();
$currentResult = $currentStmt->get_result();
$current = $currentResult->fetch_assoc();
$currentStmt->close();

// Fetch all universities
$universities = $conn->query("SELECT * FROM university ORDER BY uni_name");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $noteName = $_POST['note_Name'];
    $subjectID = $_POST['subject_ID'];

    $update = $conn->prepare("UPDATE notes SET note_Name = ?, subject_ID = ? WHERE note_ID = ? AND user_ID = ?");
    if (!$update) {
        die("SQL prepare failed: " . $conn->error);
    }

    $update->bind_param("siii", $noteName, $subjectID, $noteID, $userID);
    if ($update->execute()) {
        $_SESSION['success_message'] = "Note updated successfully!";
        header("Location: myNotesPage.php");
        exit();
    } else {
        $error = "Update failed: " . $conn->error;
    }
    $update->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Note</title>
  <style>
    body { font-family: Arial; background: #fdfdfd; }
    .container { max-width: 700px; margin: 40px auto; padding: 30px; background: #f4f4f4; border-radius: 10px; }
    label { display: block; margin-top: 10px; font-weight: bold; }
    input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
    button { margin-top: 20px; padding: 10px 20px; background: #660066; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .error { color: red; font-weight: bold; }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const uni = document.getElementById('university');
      const faculty = document.getElementById('faculty');
      const course = document.getElementById('course');
      const subject = document.getElementById('subject_ID');

      function loadOptions(endpoint, id, target, selected) {
        fetch(`${endpoint}.php?id=${id}`)
          .then(r => r.json())
          .then(data => {
            target.innerHTML = '<option value="">-- Select --</option>';
            data.forEach(row => {
              const opt = document.createElement('option');
              opt.value = row.id;
              opt.textContent = row.name;
              if (selected && selected == row.id) opt.selected = true;
              target.appendChild(opt);
            });
          });
      }

      uni.addEventListener('change', () => {
        loadOptions('getFaculties', uni.value, faculty);
        course.innerHTML = subject.innerHTML = '<option value="">-- Select --</option>';
      });

      faculty.addEventListener('change', () => {
        loadOptions('getCourses', faculty.value, course);
        subject.innerHTML = '<option value="">-- Select --</option>';
      });

      course.addEventListener('change', () => {
        loadOptions('getSubjects', course.value, subject);
      });

      // Preload selection
      <?php if ($current): ?>
      loadOptions('getFaculties', <?= $current['uni_ID'] ?>, faculty, <?= $current['faculty_ID'] ?>);
      loadOptions('getCourses', <?= $current['faculty_ID'] ?>, course, <?= $current['course_ID'] ?>);
      loadOptions('getSubjects', <?= $current['course_ID'] ?>, subject, <?= $current['subject_ID'] ?>);
      <?php endif; ?>
    });
  </script>
</head>
<body>
<?php include("head.php"); ?>

<div class="container">
  <h2>Edit Note</h2>
  <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

  <form method="post">
    <label for="note_Name">File Name</label>
    <input type="text" name="note_Name" id="note_Name" value="<?= htmlspecialchars($note['note_Name']) ?>" required>

    <label for="university">University</label>
    <select name="university" id="university" required>
      <option value="">-- Select --</option>
      <?php while($u = $universities->fetch_assoc()): ?>
        <option value="<?= $u['uni_ID'] ?>" <?= ($current && $current['uni_ID'] == $u['uni_ID']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($u['uni_Name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label for="faculty">Faculty</label>
    <select name="faculty" id="faculty" required><option>-- Select --</option></select>

    <label for="course">Course</label>
    <select name="course" id="course" required><option>-- Select --</option></select>

    <label for="subject_ID">Subject</label>
    <select name="subject_ID" id="subject_ID" required><option>-- Select --</option></select>

    <button type="submit">Save Changes</button>
  </form>
</div>
<?php include('footer.php'); ?>
</body>
</html>
