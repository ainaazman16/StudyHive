<?php
session_start();
include("connect.php");

function getOrCreate($conn, $table, $column, $value, $parentCol = null, $parentVal = null) {
    $idCol = $table . "_ID";
    $value = trim($value);

    if ($parentCol && $parentVal !== null) {
        $check = $conn->prepare("SELECT $idCol FROM $table WHERE $column = ? AND $parentCol = ?");
        $check->bind_param("si", $value, $parentVal);
    } else {
        $check = $conn->prepare("SELECT $idCol FROM $table WHERE $column = ?");
        $check->bind_param("s", $value);
    }

    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()[$idCol];
    } else {
        if ($parentCol && $parentVal !== null) {
            $insert = $conn->prepare("INSERT INTO $table ($column, $parentCol) VALUES (?, ?)");
            $insert->bind_param("si", $value, $parentVal);
        } else {
            $insert = $conn->prepare("INSERT INTO $table ($column) VALUES (?)");
            $insert->bind_param("s", $value);
        }

        if ($insert->execute()) {
            return $insert->insert_id;
        } else {
            header("Location: uploadPage.php?error=" . urlencode("Failed to insert into $table."));
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $userID = $_SESSION['user_ID'] ?? null;
    if (!$userID) {
        header("Location: uploadPage.php?error=" . urlencode("You must be logged in to upload notes."));
        exit();
    }

    $noteName = $_POST['note_name'];

    // === University ===
    $uni_ID = ($_POST['uni_ID'] === 'other')
        ? getOrCreate($conn, "university", "uni_Name", $_POST['new_uni'])
        : intval($_POST['uni_ID']);

    // === Faculty ===
    $faculty_ID = ($_POST['faculty_ID'] === 'other')
        ? getOrCreate($conn, "faculty", "faculty_Name", $_POST['new_faculty'], "uni_ID", $uni_ID)
        : intval($_POST['faculty_ID']);

    // === Course ===
    $course_ID = ($_POST['course_ID'] === 'other')
        ? getOrCreate($conn, "course", "course_Name", $_POST['new_course'], "faculty_ID", $faculty_ID)
        : intval($_POST['course_ID']);

    // === Subject ===
    $subject_ID = ($_POST['subject_ID'] === 'other')
        ? getOrCreate($conn, "subject", "subject_Name", $_POST['new_subject'], "course_ID", $course_ID)
        : intval($_POST['subject_ID']);

    // === File Upload ===
    $allowedTypes = ["pdf", "jpg", "jpeg", "png"];
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        header("Location: uploadPage.php?error=" . urlencode("Invalid file type. Only PDF, JPG, JPEG, PNG allowed."));
        exit();
    }

    $targetFile = $targetDir . time() . "_" . $fileName;

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        header("Location: uploadPage.php?error=" . urlencode("Failed to move uploaded file."));
        exit();
    }

    $fileSize = $_FILES["file"]["size"];
    $uploadDate = date("Y-m-d H:i:s");

    $sql = "INSERT INTO notes (note_Name, file_type, file_size, upload_date, download_count, user_ID, file_path, uni_ID, faculty_ID, course_ID, subject_ID)
            VALUES (?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisisiiii", $noteName, $fileType, $fileSize, $uploadDate, $userID, $targetFile, $uni_ID, $faculty_ID, $course_ID, $subject_ID);

    if ($stmt->execute()) {
        header("Location: uploadPage.php?success=1");
        exit();
    } else {
        header("Location: uploadPage.php?error=" . urlencode("Database error: " . $stmt->error));
        exit();
    }
} else {
    header("Location: uploadPage.php?error=" . urlencode("No file uploaded or invalid request."));
    exit();
}
?>
