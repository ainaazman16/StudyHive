<?php
session_start();
include("connect.php");

// Only admins allowed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: homePage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_ID']) && is_numeric($_POST['user_ID'])) {
    $user_ID = intval($_POST['user_ID']);

    // Prevent admin from deleting their own account
    if ($_SESSION['user_ID'] == $user_ID) {
        header("Location: adminUsers.php?error=" . urlencode("You cannot delete your own account."));
        exit();
    }

    // Check if user exists
    $check = $conn->prepare("SELECT * FROM user WHERE user_ID = ?");
    $check->bind_param("i", $user_ID);
    $check->execute();
    $result = $check->get_result();

    if ($result && $result->num_rows === 1) {
        // Delete the user
        $delete = $conn->prepare("DELETE FROM user WHERE user_ID = ?");
        $delete->bind_param("i", $user_ID);
        if ($delete->execute()) {
            header("Location: adminUsers.php?success=" . urlencode("User permanently deleted."));
        } else {
            header("Location: adminUsers.php?error=" . urlencode("Failed to delete user."));
        }
        $delete->close();
    } else {
        header("Location: adminUsers.php?error=" . urlencode("User not found."));
    }

    $check->close();
} else {
    header("Location: adminUsers.php?error=" . urlencode("Invalid request."));
}
exit();
?>