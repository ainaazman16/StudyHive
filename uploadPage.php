
<?php
session_start();
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css">
      <link rel="icon" type="image/png" href="images/logo.png">
  <title>UPLOAD NOTES</title>
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
    }

    .upload-container {
      background-color: #f4caff;
      max-width: 500px;
      margin: 30px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      color: #4b004b;
      margin-bottom: 5px;
    }

    .form-group select,
    .form-group input[type="text"],
    .form-group input[type="file"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .btn-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }

    button[type="submit"],
    button[type="reset"] {
      padding: 10px 25px;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    button[type="reset"] {
      background-color: #dc3545;
    }

    button[type="submit"] {
      background-color: #4b004b;
    }

    /* HOVER STYLES */
    button[type="reset"]:hover {
      background-color: #b02a37;
    }

    button[type="submit"]:hover {
      background-color: #330033;
    }

    .success-message {
      text-align: center;
      color: green;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .error-message {
      text-align: center;
      color: red;
      font-weight: bold;
      margin-bottom: 20px;
    }

    input[id^="new_"] {
      margin-top: 5px;
      display: none;
    }

    #other-details-group {
      margin-top: 20px;
      padding: 15px;
      background-color: #ffeaff;
      border-radius: 10px;
    }
  </style>
</head>
<body>
<?php include('head.php'); ?>

<h1>Upload Your Notes</h1>

<div class="upload-container">
  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="success-message">✅ Your note was uploaded successfully!</div>
  <?php elseif (isset($_GET['error'])): ?>
    <div class="error-message">❌ <?= htmlspecialchars($_GET['error']) ?></div>
  <?php endif; ?>

  <form action="uploadSave.php" method="POST" enctype="multipart/form-data">
    <!-- Note Title -->
    <div class="form-group">
      <label>Note Title</label>
      <input type="text" placeholder="Enter notes title here..." name="note_name" required>
    </div>

    <!-- File Upload -->
    <div class="form-group">
      <label>Input File</label>
      <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
      <small>Accepted: PDF, PNG, JPG</small>
    </div>

    <!-- University -->
    <div class="form-group">
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
      <input type="text" name="new_uni" id="new_university" placeholder="Enter new university">
    </div>

    <!-- Faculty -->
    <div class="form-group">
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
      <input type="text" name="new_faculty" id="new_faculty" placeholder="Enter new faculty">
    </div>

    <!-- Course -->
    <div class="form-group">
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
      <input type="text" name="new_course" id="new_course" placeholder="Enter new course">
    </div>

    <!-- Subject -->
    <div class="form-group">
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
      <input type="text" name="new_subject" id="new_subject" placeholder="Enter new subject">
    </div>

    <div class="btn-group">
      <button type="reset">Reset</button>
      <button type="submit">Upload</button>
    </div>
  </form>
</div>

<script>
  function toggleOther(selectId, inputId) {
    const select = document.getElementById(selectId);
    const input = document.getElementById(inputId);
    if (select.value === 'other') {
      input.style.display = 'block';
      input.required = true;
    } else {
      input.style.display = 'none';
      input.required = false;
    }
  }

  document.getElementById('university').addEventListener('change', function () {
    toggleOther('university', 'new_university');
    document.getElementById('other-details-group').style.display = (this.value === 'other') ? 'block' : 'none';
  });

  document.getElementById('faculty').addEventListener('change', () => toggleOther('faculty', 'new_faculty'));
  document.getElementById('course').addEventListener('change', () => toggleOther('course', 'new_course'));
  document.getElementById('subject').addEventListener('change', () => toggleOther('subject', 'new_subject'));
</script>

<?php include('footer.php'); ?>
</body>
</html>
