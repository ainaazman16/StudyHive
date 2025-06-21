<?php
// adminDeleteNote.php
session_start();
include("connect.php");

// Only admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Validate note_ID parameter
if (!isset($_GET['note_ID']) || !is_numeric($_GET['note_ID'])) {
    header("Location: adminPanel.php?error=" . urlencode("Invalid note ID."));
    exit();
}

$note_ID = intval($_GET['note_ID']);

// Fetch file path to delete physical file
$stmt = $conn->prepare("SELECT file_path FROM notes WHERE note_ID = ?");
$stmt->bind_param("i", $note_ID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $note = $result->fetch_assoc();
    $filePath = $note['file_path'];
    // Delete file if exists
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
} else {
    // Note not found
    header("Location: adminPanel.php?error=" . urlencode("Note not found."));
    exit();
}
$stmt->close();

// Delete note record
$delStmt = $conn->prepare("DELETE FROM notes WHERE note_ID = ?");
$delStmt->bind_param("i", $note_ID);
if ($delStmt->execute()) {
    $delStmt->close();
    header("Location: adminPanel.php?success=" . urlencode("Note deleted successfully."));
    exit();
} else {
    header("Location: adminPanel.php?error=" . urlencode("Failed to delete note."));
    exit();
}
?>
