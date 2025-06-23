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

    // Prevent admin from deleting themselves
    if ($_SESSION['user_ID'] == $user_ID) {
        header("Location: adminUsers.php?error=" . urlencode("You cannot delete your own account."));
        exit();
    }

    // Check if user exists and not already deactivated
    $check = $conn->prepare("SELECT role FROM user WHERE user_ID = ?");
    $check->bind_param("i", $user_ID);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($row['role'] === 'deactivated') {
            header("Location: adminUsers.php?error=" . urlencode("User is already deactivated."));
            exit();
        }

        // Soft delete user
        $deactivate = $conn->prepare("UPDATE user SET role = 'deactivated' WHERE user_ID = ?");
        $deactivate->bind_param("i", $user_ID);

        if ($deactivate->execute()) {
            header("Location: adminUsers.php?success=" . urlencode("User account deactivated successfully."));
            exit();
        } else {
            header("Location: adminUsers.php?error=" . urlencode("Failed to deactivate user."));
            exit();
        }
    } else {
        header("Location: adminUsers.php?error=" . urlencode("User not found."));
        exit();
    }
} else {
    header("Location: adminUsers.php?error=" . urlencode("Invalid request."));
    exit();
}
?>