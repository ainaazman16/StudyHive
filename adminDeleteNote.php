<?php
session_start();
include("connect.php");

// Only admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Validate POST and note_ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_ID']) && is_numeric($_POST['note_ID'])) {
    $note_ID = intval($_POST['note_ID']);

    // Fetch file path
    $stmt = $conn->prepare("SELECT file_path FROM notes WHERE note_ID = ?");
    $stmt->bind_param("i", $note_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $note = $result->fetch_assoc();
        $filePath = $note['file_path'];

        // Delete physical file if exists
        if (!empty($filePath) && file_exists($filePath)) {
            @unlink($filePath);
        }

        $stmt->close();

        // Delete the note from database
        $deleteStmt = $conn->prepare("DELETE FROM notes WHERE note_ID = ?");
        $deleteStmt->bind_param("i", $note_ID);

        if ($deleteStmt->execute()) {
            $deleteStmt->close();
            header("Location: adminNotes.php?success=" . urlencode("Note deleted successfully."));
            exit();
        } else {
            header("Location: adminNotes.php?error=" . urlencode("Failed to delete note."));
            exit();
        }
    } else {
        header("Location: adminNotes.php?error=" . urlencode("Note not found."));
        exit();
    }
} else {
    header("Location: adminNotes.php?error=" . urlencode("Invalid request."));
    exit();
}
?>
