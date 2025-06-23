<?php
session_start();
include("connect.php");

// Only admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

// Handle deletion of notes from reports page
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['report_ID']) && is_numeric($_POST['report_ID'])
) {
    // Get the note_ID using the report_ID
    $report_ID = intval($_POST['report_ID']);

    $fetchNoteID = $conn->prepare("SELECT note_ID FROM report_note WHERE report_ID = ?");
    $fetchNoteID->bind_param("i", $report_ID);
    $fetchNoteID->execute();
    $res = $fetchNoteID->get_result();

    if ($res->num_rows === 1) {
        $noteRow = $res->fetch_assoc();
        $note_ID = $noteRow['note_ID'];

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
                // Optionally delete the report too
                $deleteReport = $conn->prepare("DELETE FROM report_note WHERE report_ID = ?");
                $deleteReport->bind_param("i", $report_ID);
                $deleteReport->execute();
                $deleteReport->close();

                header("Location: adminReports.php?success=" . urlencode("Note deleted successfully."));
                exit();
            } else {
                header("Location: adminReports.php?error=" . urlencode("Failed to delete note."));
                exit();
            }
        } else {
            header("Location: adminReports.php?error=" . urlencode("Note not found."));
            exit();
        }
    } else {
        header("Location: adminReports.php?error=" . urlencode("Invalid report."));
        exit();
    }
}

// Fetch report details
$query = "
    SELECT r.report_ID, n.note_Name, u.user_Name, r.report_type, r.report_detail, r.reported_at
    FROM report_note r
    JOIN notes n ON r.note_ID = n.note_ID
    JOIN user u ON r.user_ID = u.user_ID
    ORDER BY r.reported_at DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Reported Notes</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f3f4f6; margin: 0; }
        .navbar {
            background-color: #660066;
            position: sticky;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 10px;
            height: 60px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }
        .navbar a {
            text-decoration: none;
            color: white;
            padding: 14px 16px;
            display: block;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }
        .navbar a:hover { background-color: #990099; }
        .header {
            background-color: #ec97ec;
            color: #5e1b5e;
            text-align: center;
            padding: 30px 20px;
            font-family: Cambria, serif;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .header h1 { margin: 0; font-size: 2.8em; font-weight: bold; }
        .header p { font-size: 1.2em; margin-top: 10px; color: #3d0d3d; }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        th { background-color: #ec97ec; color: #5e1b5e; }
        .btn-delete {
            background-color: #e11d48;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header class="header">
    <img src="images/whiteLogo.png" alt="Logo" style="width: 160px;">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['user_Name']) ?>!</p>
</header>

<div class="navbar">
    <a href="adminDashboard.php">Dashboard</a>
    <a href="adminUsers.php">Manage Users</a>
    <a href="adminNotes.php">Manage Notes</a>
    <a href="adminReports.php">Reported Content</a>
    <a href="adminFeedback.php">Feedback</a>
    <a href="logout.php">Log out</a>
</div>

<div class="container">
    <h2>Reported Notes</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_GET['success']) ?>
        </p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red; font-weight: bold;">
            <?= htmlspecialchars($_GET['error']) ?>
        </p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Report ID</th>
            <th>Note Title</th>
            <th>Uploader</th>
            <th>Report Type</th>
            <th>Report Details</th>
            <th>Reported At</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['report_ID'] ?></td>
                <td><?= htmlspecialchars($row['note_Name']) ?></td>
                <td><?= htmlspecialchars($row['user_Name']) ?></td>
                <td><?= htmlspecialchars($row['report_type']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['report_detail'])) ?></td>
                <td><?= $row['reported_at'] ?></td>
                <td>
                    <form method="POST" action="adminReports.php" onsubmit="return confirm('Are you sure you want to delete this note?');">
                        <input type="hidden" name="report_ID" value="<?= $row['report_ID'] ?>">
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>