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
$currentStmt = $conn->prepare("SELECT s.subject_ID, s.course_ID, c.faculty_ID, f.uni_ID FROM subject s JOIN course c ON s.course_ID = c.course_ID JOIN faculty f ON c.faculty_ID = f.faculty_ID WHERE s.subject_ID = ?");
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
  <meta charset="UTF-8">
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

    <label>University</label>
      <select name="uni_ID" id="university" required>
        <option value="">Select University</option>
        <?php
        $unis = $conn->query("SELECT * FROM university");
        while ($u = $unis->fetch_assoc()) {
          echo "<option value='{$u['uni_ID']}'>{$u['uni_Name']}</option>";
        }
        ?>
        <option value="other">Other...</option>
      </select>
      <input type="text" name="new_uni" id="new_uni" placeholder="Enter new university" style="display:none;">


     <label>Faculty</label>
      <select name="faculty_ID" id="faculty" required>
        <option value="">Select Faculty</option>
        <?php
        $faculties = $conn->query("SELECT * FROM faculty");
        while ($f = $faculties->fetch_assoc()) {
          echo "<option value='{$f['faculty_ID']}'>{$f['faculty_Name']}</option>";
        }
        ?>
        <option value="other">Other...</option>
      </select>
      <input type="text" name="new_faculty" id="new_faculty" placeholder="Enter new faculty" style="display:none;">

    <label>Course</label>
      <select name="course_ID" id="course" required>
        <option value="">Select Course</option>
        <?php
        $courses = $conn->query("SELECT * FROM course");
        while ($c = $courses->fetch_assoc()) {
          echo "<option value='{$c['course_ID']}'>{$c['course_Name']}</option>";
        }
        ?>
        <option value="other">Other...</option>
      </select>
      <input type="text" name="new_course" id="new_course" placeholder="Enter new course" style="display:none;">


    <label>Subject</label>
      <select name="subject_ID" id="subject" required>
        <option value="">Select Subject</option>
        <?php
        $subjects = $conn->query("SELECT * FROM subject");
        while ($s = $subjects->fetch_assoc()) {
          echo "<option value='{$s['subject_ID']}'>{$s['subject_Name']}</option>";
        }
        ?>
        <option value="other">Other...</option>
      </select>
      <input type="text" name="new_subject" id="new_subject" placeholder="Enter new subject" style="display:none;">


    <button type="submit">Save Changes</button>
  </form>
</div>

<script>
  // Toggle "Other" input fields
  function toggleInput(selectId, inputId) {
    const select = document.getElementById(selectId);
    const input = document.getElementById(inputId);
    input.style.display = (select.value === 'other') ? 'block' : 'none';
  }

  ['university', 'faculty', 'course', 'subject'].forEach(type => {
    document.getElementById(type).addEventListener('change', () => {
      toggleInput(type, 'new_' + type);
    });
  });

  // Drag-and-Drop File Upload
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');

  dropZone.addEventListener('dragover', function (e) {
    e.preventDefault();
    dropZone.classList.add('dragover');
  });

  dropZone.addEventListener('dragleave', function () {
    dropZone.classList.remove('dragover');
  });

  dropZone.addEventListener('drop', function (e) {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    if (e.dataTransfer.files.length > 0) {
      fileInput.files = e.dataTransfer.files;
    }
  });
</script>

<?php include("footer.php"); ?>
</body>
</html>