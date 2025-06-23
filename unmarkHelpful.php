<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID']) || !isset($_POST['note_ID'])) {
    die("Unauthorized access.");
}

$userID = $_SESSION['user_ID'];
$noteID = intval($_POST['note_ID']);

$stmt = $conn->prepare("DELETE FROM note_helpful WHERE user_ID = ? AND note_ID = ?");
$stmt->bind_param("ii", $userID, $noteID);
if ($stmt->execute()) {
    header("Location: viewNotes.php");
    exit;
} else {
    echo "Failed to unmark helpful.";
}
?>
