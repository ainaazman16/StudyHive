<?php
session_start();
include("connect.php");

$backQuery = http_build_query([
    'search' => $_GET['search'] ?? '',
    'uni_ID' => $_GET['uni_ID'] ?? '',
    'faculty_ID' => $_GET['faculty_ID'] ?? '',
    'course_ID' => $_GET['course_ID'] ?? '',
    'subject_ID' => $_GET['subject_ID'] ?? ''
]);

// Get note ID from query parameter
$noteID = $_GET['note_ID'] ?? null;
$userID = $_SESSION['user_ID'] ?? null;

// if (!$noteID || !$userID) {
//     echo "<p style='color:red;text-align:center;'>Invalid access. Please log in or select a note.</p>";
//     exit;
// }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $rating = $_POST['rating'];
    $reviewText = trim($_POST['review']);

    if ($rating && $reviewText) {
        // Optional: default is_helpful to 0
        $isHelpful = 0;

        // Prepare and insert into your review table
        $stmt = $conn->prepare("INSERT INTO review (rating, is_helpful, note_ID, review_text, user_ID, review_date)
                                VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiisi", $rating, $isHelpful, $noteID, $reviewText, $userID);

        if ($stmt->execute()) {
            echo "<script>alert('Review submitted successfully!'); window.location.href='viewNotes.php';</script>";
        } else {
            echo "<p style='color:red;text-align:center;'>Error submitting review: {$stmt->error}</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;text-align:center;'>Please fill out both fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Notes</title>
    <style>
        .back-btn { 
        margin: 20px; 
        display: inline-block; 
        font-size: 16px; 
        color: #660066; 
        text-decoration: none; 
        font-weight: bold; 
        }

        body { 
            font-family: Arial; 
            text-align: center;
        }

        .review { 
            border-bottom: 1px solid #ccc; 
            margin-bottom: 15px; 
            padding-bottom: 10px; 
        }

        h1 { 
            font-size: 60px; 
            text-align: center; 
            color: #4b004b; 
        }

        .submit-btn{
            text-align: center; 
            margin-top: 20px; 
            color:rgb(87, 11, 87);
        }

        .review{
            margin: 20px auto; 
            max-width: 600px; 
            text-align: center; 
            color: rgb(77, 19, 77);
        }
    </style>
</head>
<body>
    <?php
        include('head.php');
    ?>

    <a class="back-btn" href="viewNotes.php?<?= $backQuery ?>">&larr; Back</a>
    <h1>Review Notes</h1>
    <form method="POST" >
        <div class="review">
         <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="note-card">
                <h3><?= htmlspecialchars($row['note_Name']) ?></h3>
                <p><strong>Type:</strong> <?= strtoupper($row['file_type']) ?></p>
                <p><strong>Author:</strong> <?= htmlspecialchars($row['user_Fname']) ?></p>
                <p><strong>Uploaded:</strong> <?= $row['upload_date'] ?></p>
                </div>
            <?php endwhile; ?>
            <div>
                <h2><label for="rating">Rate this note:</label></h2>
                <select name="rating" id="rating" required>
                    <option value="">Select</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
        <div style="text-align:center; margin-bottom:20px;">
            <h2><label for="review">Your Review:</label><br></h2>
            <textarea name="review" id="review" rows="4" cols="50" required></textarea>
        </div>
        <div class="submit-btn">
            <button type="submit" name="submit">Submit Review</button>
        </div>
       </div>
    </form>


</body>
</html>