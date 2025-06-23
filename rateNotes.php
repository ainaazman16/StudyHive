<?php
session_start();
include("connect.php");

$noteID = $_GET['note_ID'] ?? null;
$userID = $_SESSION['user_ID'] ?? null;

$backQuery = http_build_query([
    'search' => $_GET['search'] ?? '',
    'uni_ID' => $_GET['uni_ID'] ?? '',
    'faculty_ID' => $_GET['faculty_ID'] ?? '',
    'course_ID' => $_GET['course_ID'] ?? '',
    'subject_ID' => $_GET['subject_ID'] ?? ''
]);


$note = null;
if ($noteID) {
    $stmt = $conn->prepare("SELECT n.note_Name, n.file_type, n.upload_date, u.user_Fname
                            FROM notes n
                            LEFT JOIN user u ON n.user_ID = u.user_ID
                            WHERE n.note_ID = ?");
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
        $stmt = $conn->prepare("INSERT INTO review (rating, is_helpful, note_ID, review_text, user_ID, review_date)
                                VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiisi", $rating, $isHelpful, $noteID, $reviewText, $userID);

        if ($stmt->execute()) {
            echo "<script>alert('Review submitted successfully!'); window.location.href='viewNotes.php?$backQuery';</script>";
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
    <style>
        .back-btn { 
            display: block;
            text-align: left;
            margin: 20px;
            font-size: 16px; 
            color: #660066; 
            text-decoration: none; 
            font-weight: bold; 
        }

        body { 
            font-family: Arial; 
            text-align: center;
        }

        .review-section { 
            margin: 20px auto; 
            max-width: 600px; 
            border: 1px solid #ccc; 
            border-radius: 8px; 
            padding: 20px; 
            background-color: #f7f0f7;
            color: rgb(77, 19, 77);
        }

        h1 { 
            font-size: 50px; 
            color: #660066; 
        }

        textarea, select {
            width: 80%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #aaa;
        }

        .submit-btn {
            margin-top: 20px;
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
    </style>
</head>
<body>

<?php include('head.php'); ?>

<a class="back-btn" href="viewNotes.php?<?= $backQuery ?>">&larr; Back</a>
<h1>Review Notes</h1>

<form method="POST">
    <div class="review-section">
        <?php if ($note): ?>
            <div class="note-card">
                <h3><?= htmlspecialchars($note['note_Name']) ?></h3>
                <p><strong>Type:</strong> <?= strtoupper($note['file_type']) ?></p>
                <p><strong>Author:</strong> <?= htmlspecialchars($note['user_Fname']) ?></p>
                <p><strong>Uploaded:</strong> <?= $note['upload_date'] ?></p>
            </div>
        <?php else: ?>
            <p style="color:red;">Note not found.</p>
        <?php endif; ?>

        <div style="margin-top:20px;">
            <h2><label for="rating">Rate this note:</label></h2>
            <select name="rating" id="rating" required>
                <option value="">Select</option>
                <option value="1">1 - Poor</option>
                <option value="2">2 - Fair</option>
                <option value="3">3 - Good</option>
                <option value="4">4 - Very Good</option>
                <option value="5">5 - Excellent</option>
            </select>
        </div>

        <div style="margin-top:20px;">
            <h2><label for="review">Your Review:</label></h2>
            <textarea name="review" id="review" rows="4" required></textarea>
        </div>

        <div class="submit-btn">
            <button type="submit" name="submit">Submit Review</button>
        </div>
    </div>
</form>
</body>
</html>
