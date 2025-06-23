<?php
session_start();
include("connect.php");
include("head.php"); 

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

$userID = $_SESSION['user_ID'];
$noteID = $_GET['note_ID'] ?? null;

if (!$noteID) {
    die("Note ID missing.");
}

$stmt = $conn->prepare("SELECT * FROM notes WHERE note_ID = ? AND user_ID = ?");
$stmt->bind_param("ii", $noteID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Note not found or unauthorized.");
}

$note = $result->fetch_assoc();
$stmt->close();

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

$universities = $conn->query("SELECT * FROM university ORDER BY uni_name");

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
  <link rel="stylesheet" href="style.css">
<head>
  <meta charset="UTF-8">
  <title>Edit Note</title>
  <link rel="icon" type="image/png" href="images/logo.png">
  <style>

    /* Copy all search bar styles from head.php */
.search-wrapper {
      display: flex;
      justify-content: center;
      width: 100%;
    }

    .search-bar {
      display: flex;
      align-items: center;
      background-color: #fff;
      border-radius: 999px;
      padding: 10px 20px;
      width: 90%;
      max-width: 800px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      position: relative;
    }

/* Include all other search-related styles from head.php */
    body { font-family: Arial; background: #fdfdfd; }
    .container {
      max-width: 700px;
      margin: 40px auto;
      padding: 30px;
      background: #f4caff;
      border-radius: 10px;
    }

    

    label { 
      display: block; margin-top: 10px; font-weight: bold; 
    }
    
    /* Uniform sizing for inputs and selects */
    input[type="text"],
    select {
      box-sizing: border-box;
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    
    button {
      display: block;
      margin: 20px auto 0;
      padding: 10px 20px;
      background: #660066;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
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
            const otherOpt = document.createElement('option');
            otherOpt.value = 'other';
            otherOpt.textContent = 'Other...';
            target.appendChild(otherOpt);
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

<h1>Edit Note</h1>
<div class="container">
  
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
        $sel = ($u['uni_ID']==$note['uni_ID']) ? ' selected' : '';
        echo "<option value='{$u['uni_ID']}'$sel>" . htmlspecialchars($u['uni_Name']) . "</option>";
      }
      ?>
      <option value="other">Other...</option>
    </select>

    <label>Faculty</label>
    <select name="faculty_ID" id="faculty" required>
      <option value="">Select Faculty</option>
      <?php
      $faculties = $conn->query("SELECT * FROM faculty");
      while ($f = $faculties->fetch_assoc()) {
        $sel = ($f['faculty_ID']==$note['faculty_ID']) ? ' selected' : '';
        echo "<option value='{$f['faculty_ID']}'$sel>" . htmlspecialchars($f['faculty_Name']) . "</option>";
      }
      ?>
      <option value="other">Other...</option>
    </select>

    <label>Course</label>
    <select name="course_ID" id="course" required>
      <option value="">Select Course</option>
      <?php
      $courses = $conn->query("SELECT * FROM course");
      while ($c = $courses->fetch_assoc()) {
        $sel = ($c['course_ID']==$note['course_ID']) ? ' selected' : '';
        echo "<option value='{$c['course_ID']}'$sel>" . htmlspecialchars($c['course_Name']) . "</option>";
      }
      ?>
      <option value="other">Other...</option>
    </select>

    <label>Subject</label>
    <select name="subject_ID" id="subject" required>
      <option value="">Select Subject</option>
      <?php
      $subjects = $conn->query("SELECT * FROM subject");
      while ($s = $subjects->fetch_assoc()) {
        $sel = ($s['subject_ID']==$note['subject_ID']) ? ' selected' : '';
        echo "<option value='{$s['subject_ID']}'$sel>" . htmlspecialchars($s['subject_Name']) . "</option>";
      }
      ?>
      <option value="other">Other...</option>
    </select>

    <button type="submit">Save Changes</button>
  </form>
</div>

<?php include("footer.php"); ?>
</body>
</html>
