<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

if (!isset($_GET['note_ID'])) {
    die("Invalid request.");
}

$userID = $_SESSION['user_ID'];
$noteID = intval($_GET['note_ID']);

// Check if the note belongs to the user
$sql = "SELECT file_path FROM notes WHERE note_ID = ? AND user_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $noteID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Note not found or unauthorized.");
}

$note = $result->fetch_assoc();
$filePath = $note['file_path'];
$stmt->close();

// Delete note from database
$delete = $conn->prepare("DELETE FROM notes WHERE note_ID = ? AND user_ID = ?");
$delete->bind_param("ii", $noteID, $userID);
$delete->execute();
$delete->close();

// Delete file from server
if (file_exists($filePath)) {
    unlink($filePath);
}

header("Location: myNotesPage.php");
exit();
