<?php
session_start();
include("connect.php");

$userID = $_SESSION['user_ID'] ?? null;
$noteID = $_POST['note_ID'] ?? null;

if (!$userID || !$noteID) {
    die("Missing user or note ID");
}

// Prevent duplicate entry
$stmt = $conn->prepare("SELECT 1 FROM note_helpful WHERE user_ID = ? AND note_ID = ?");
$stmt->bind_param("ii", $userID, $noteID);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    $insert = $conn->prepare("INSERT INTO note_helpful (user_ID, note_ID) VALUES (?, ?)");
    $insert->bind_param("ii", $userID, $noteID);
    $insert->execute();
    $insert->close();
}

header("Location: viewNotes.php");
exit;
?>
