<?php
session_start();
include("connect.php");

// Get values from GET or POST
$noteID = $_GET['note_ID'] ?? $_POST['note_ID'] ?? null;
if (!$noteID){
    die("Note ID missing.");
} 

$userID = $_SESSION['user_ID'] ?? null;

// Get filters (manual method)
$search = $_GET['search'] ?? $_POST['search'] ?? '';
$uni_ID = $_GET['uni_ID'] ?? $_POST['uni_ID'] ?? '';
$faculty_ID = $_GET['faculty_ID'] ?? $_POST['faculty_ID'] ?? '';
$course_ID = $_GET['course_ID'] ?? $_POST['course_ID'] ?? '';
$subject_ID = $_GET['subject_ID'] ?? $_POST['subject_ID'] ?? '';

// Fetch note details
$note = null;
if ($noteID) {
    $stmt = $conn->prepare("
        SELECT n.note_Name, n.file_type, n.upload_date, u.user_Fname
        FROM notes n
        LEFT JOIN user u ON n.user_ID = u.user_ID
        WHERE n.note_ID = ?
    ");
    $stmt->bind_param("i", $noteID);
    $stmt->execute();
    $result = $stmt->get_result();
    $note = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $rating = $_POST['rating'];
    $reviewText = trim($_POST['review']);

    if ($rating && $reviewText && $noteID && $userID) {
        $isHelpful = 0;
        $stmt = $conn->prepare("
            INSERT INTO review (rating, is_helpful, note_ID, review_text, user_ID, review_date)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiisi", $rating, $isHelpful, $noteID, $reviewText, $userID);

        if ($stmt->execute()) {
            echo "<script>
                alert('Review submitted successfully!');
                window.location.href = 'viewNotes.php?note_ID=$noteID&search=$search&uni_ID=$uni_ID&faculty_ID=$faculty_ID&course_ID=$course_ID&subject_ID=$subject_ID';
            </script>";
        } else {
            echo "<p style='color:red;text-align:center;'>Error submitting review: {$stmt->error}</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;text-align:center;'>Please fill out all fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Notes</title>
    <link rel="stylesheet" href="style.css">
  <style>
    .note-card, .rate-card {
        max-width: 600px;
        margin: 20px auto;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
        background-color: #f9f6fb;
        color: #4a004a;
        text-align: left;
    }

    .note-card h3 {
        margin-top: 0;
        color: #4a004a;
    }

    .rate-card h2 {
        margin-bottom: 10px;
        color: #4a004a;
    }

    .rate-card select, .rate-card textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 5px;
        border: 1px solid #aaa;
        font-family: Arial;
        font-size: 14px;
    }

    .submit-btn {
        text-align: center;
    }

    .submit-btn button {
        padding: 10px 20px;
        font-weight: bold;
        color: white;
        background-color: #660066;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .submit-btn button:hover {
        background-color: #800080;
    }

    .back-btn {
      display: block;
      margin: 20px;
      font-size: 16px;
      color: #660066;
      text-decoration: none;
      font-weight: bold;
      text-align: left;
    }
</style>
</head>
<body>

<?php include('head.php'); ?>

<a class="back-btn" href="viewNotes.php?note_ID=<?= $noteID ?>&search=<?= $search ?>&uni_ID=<?= $uni_ID ?>&faculty_ID=<?= $faculty_ID ?>&course_ID=<?= $course_ID ?>&subject_ID=<?= $subject_ID ?>">&larr; Back</a>

<h1>Review Notes</h1>

<form method="POST">
    <input type="hidden" name="note_ID" value="<?= $noteID ?>">
    <input type="hidden" name="search" value="<?= $search ?>">
    <input type="hidden" name="uni_ID" value="<?= $uni_ID ?>">
    <input type="hidden" name="faculty_ID" value="<?= $faculty_ID ?>">
    <input type="hidden" name="course_ID" value="<?= $course_ID ?>">
    <input type="hidden" name="subject_ID" value="<?= $subject_ID ?>">

    <?php if ($note): ?>
        <div class="note-card">
            <h3><?= htmlspecialchars($note['note_Name']) ?></h3>
            <p><strong>Type:</strong> <?= strtoupper($note['file_type']) ?></p>
            <p><strong>Author:</strong> <?= htmlspecialchars($note['user_Fname']) ?></p>
            <p><strong>Uploaded:</strong> <?= $note['upload_date'] ?></p>
        </div>

        <div class="rate-card">
            <h2>Rate this note:</h2>
            <select name="rating" id="rating" required>
                <option value="">Select</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>

            <h2>Your Review:</h2>
            <textarea name="review" id="review" rows="4" required></textarea>

            <div class="submit-btn">
                <button type="submit" name="submit">Submit Review</button>
            </div>
        </div>
    <?php else: ?>
        <p style="color:red; text-align:center;">Note not found.</p>
    <?php endif; ?>
</form>

</body>
</html>
