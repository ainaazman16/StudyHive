<?php
session_start();
include("connect.php");

if (!isset($_GET['note_ID'])) {
    die("Invalid request.");
}

$noteID = intval($_GET['note_ID']);

// Get file path from DB
$sql = "SELECT file_path FROM notes WHERE note_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $noteID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $note = $result->fetch_assoc();
    $fileName = $note['file_path'];
    $filePath = $fileName; // file path already includes 'uploads/'

    if (file_exists($filePath)) {
        // Update download count
        $updateSql = "UPDATE notes SET download_count = download_count + 1 WHERE note_ID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $noteID);
        $updateStmt->execute();
        $updateStmt->close();

        // Log this download for user only if not already logged
        if (isset($_SESSION['user_ID'])) {
            $userID = $_SESSION['user_ID'];

            $checkSql = "SELECT 1 FROM note_downloads WHERE user_ID = ? AND note_ID = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ii", $userID, $noteID);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows == 0) {
                $logSql = "INSERT INTO note_downloads (user_ID, note_ID, download_date) VALUES (?, ?, NOW())";
                $logStmt = $conn->prepare($logSql);
                $logStmt->bind_param("ii", $userID, $noteID);
                $logStmt->execute();
                $logStmt->close();
            }
            $checkStmt->close();
        }

        // Force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        die("File not found.");
    }
} else {
    die("Note not found.");
}
?>
