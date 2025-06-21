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
  <title>UPLOAD NOTES</title>
  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }
    .topic {
      background-color: #ec97ec;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      font-size: 230%;
      text-decoration: none;
      color: #5e1b5e;
      text-align: center;
      height: 500px;
      padding-top: 10px;
      padding-bottom: 10px;
      position: relative;
    }
    .topic img {
      width: 350px;
      height: auto;
      margin-bottom: 10px;
      margin-top: 5px;
    }
    .bottom-nav {
      display: flex;
      align-items: center;
      background-color: #4b004b;
      padding: 20 20px;
      height: 60px;
    }
    .bottom-nav img.logo {
      height: 40px;
    }
    .nav-links {
      display: flex;
      margin-left: auto;
    }
    .nav-btn {
      background-color: #4b004b;
      color: white;
      padding: 23px 30px;
      text-align: center;
      text-decoration: none;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 12px;
      border-right: 2px solid #ffffff;
      transition: background-color 0.3s;
    }
    .nav-btn:last-child {
      border-right: none;
    }
    .nav-btn:hover {
      background-color: #e696ec;
    }
    .nav-btn.active {
      background-color: #e696ec;
      color: #ffffff;
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
    .form-group input[type="text"] {
      width: 100%;
      padding: 8px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    input[type="file"] {
      padding: 10px;
    }
    .btn-group {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }
    button[type="submit"],
    button[type="reset"] {
      padding: 10px 25px;
      background-color: #a94eb5;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    button[type="reset"] {
      background-color: rgb(153, 137, 153);
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
      <div class="form-group">
        <label>Note Title</label>
        <input type="text" name="note_name" required>
      </div>

      <div class="form-group">
        <label>Drop Files Here</label>
        <div class="upload-box" style="border: 2px dashed #aaa; background-color: #fff; padding: 20px; margin-bottom: 20px;">
          <p>Drop files here</p>
          <p><small>Supported: PNG, JPG, PDF</small></p>
          <input type="file" name="file" required />
        </div>
      </div>

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
        <input type="text" name="new_uni" id="new_uni" placeholder="Enter new university" style="display:none;">
      </div>

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
        <input type="text" name="new_faculty" id="new_faculty" placeholder="Enter new faculty" style="display:none;">
      </div>

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
        <input type="text" name="new_course" id="new_course" placeholder="Enter new course" style="display:none;">
      </div>

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
        <input type="text" name="new_subject" id="new_subject" placeholder="Enter new subject" style="display:none;">
      </div>

      <div class="btn-group">
        <button type="reset">Cancel</button>
        <button type="submit">Upload</button>
      </div>
    </form>
  </div>

<script>
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
</script>
<?php include('footer.php'); ?>
</body>
</html>
