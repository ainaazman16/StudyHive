<?php
session_start();
include("connect.php");

if (!isset($_SESSION['user_ID'])) {
    header("Location: loginPage.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    $allowedTypes = ["pdf", "jpg", "jpeg", "png"];
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $originalName = basename($_FILES["file"]["name"]);
    $fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($fileType, $allowedTypes)) {
        header("Location: uploadPage.php?error=" . urlencode("Invalid file type. Only PDF, JPG, JPEG, PNG allowed."));
        exit();
    }

    $storedFileName = time() . "_" . $originalName;
    $targetFile = $targetDir . $storedFileName;

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        header("Location: uploadPage.php?error=" . urlencode("Failed to move uploaded file."));
        exit();
    }

    $noteName = trim($_POST['note_name']);
    $userID = $_SESSION['user_ID'];
    $uploadDate = date("Y-m-d H:i:s");

    // Step 1: University
    if ($_POST['uni_ID'] === 'other') {
        $new_uni = trim($_POST['new_uni']);
        if (empty($new_uni)) {
            header("Location: uploadPage.php?error=" . urlencode("New university name is empty."));
            exit();
        }
        $stmt = $conn->prepare("INSERT INTO university (uni_Name) VALUES (?)");
        $stmt->bind_param("s", $new_uni);
        $stmt->execute();
        $uni_ID = $conn->insert_id;
    } else {
        $uni_ID = intval($_POST['uni_ID']);
    }

    // Step 2: Faculty
    if ($_POST['faculty_ID'] === 'other') {
        $new_faculty = trim($_POST['new_faculty']);
        if (empty($new_faculty)) {
            header("Location: uploadPage.php?error=" . urlencode("New faculty name is empty."));
            exit();
        }
        $stmt = $conn->prepare("INSERT INTO faculty (faculty_Name, uni_ID) VALUES (?, ?)");
        $stmt->bind_param("si", $new_faculty, $uni_ID);
        $stmt->execute();
        $faculty_ID = $conn->insert_id;
    } else {
        $faculty_ID = intval($_POST['faculty_ID']);
    }

    // Step 3: Course
    if ($_POST['course_ID'] === 'other') {
        $new_course = trim($_POST['new_course']);
        if (empty($new_course)) {
            header("Location: uploadPage.php?error=" . urlencode("New course name is empty."));
            exit();
        }
        $stmt = $conn->prepare("INSERT INTO course (course_Name, faculty_ID) VALUES (?, ?)");
        $stmt->bind_param("si", $new_course, $faculty_ID);
        $stmt->execute();
        $course_ID = $conn->insert_id;
    } else {
        $course_ID = intval($_POST['course_ID']);
    }

    // Step 4: Subject
    if ($_POST['subject_ID'] === 'other') {
        $new_subject = trim($_POST['new_subject']);
        if (empty($new_subject)) {
            header("Location: uploadPage.php?error=" . urlencode("New subject name is empty."));
            exit();
        }
        $stmt = $conn->prepare("INSERT INTO subject (subject_Name, course_ID) VALUES (?, ?)");
        $stmt->bind_param("si", $new_subject, $course_ID);
        $stmt->execute();
        $subject_ID = $conn->insert_id;
    } else {
        $subject_ID = intval($_POST['subject_ID']);
    }

    // Final: Save note with subject and uni reference
    $sql = "INSERT INTO notes (
    note_Name, file_path, file_type, user_ID, upload_date,
    download_count, subject_ID, course_ID, faculty_ID, uni_ID
) VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssissiii", $noteName, $storedFileName, $fileType, $userID, $uploadDate, $subject_ID, $course_ID, $faculty_ID, $uni_ID);

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
