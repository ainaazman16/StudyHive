<?php
session_start();
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user_Name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "Please provide both username and password.";
        exit();
    }

    // Fetch user record
    $stmt = $conn->prepare("SELECT user_ID, user_Fname, email, user_Name, password, role FROM `user` WHERE user_Name = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_ID']    = $user['user_ID'];
            $_SESSION['user_Fname'] = $user['user_Fname'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['user_Name']  = $user['user_Name'];
            $_SESSION['role']       = $user['role'];

            // Redirect to home or admin panel
            if ($user['role'] === 'admin') {
                header('Location: adminPanel.php');
            } else {
                header('Location: homePage.php');
            }
            exit();
        } else {
            // Invalid password
            $error = "Login failed: incorrect password.";
        }
    } else {
        // No such user
        $error = "Login failed: username does not exist.";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - StudyHive</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
      <label for="user_Name">Username</label>
      <input type="text" id="user_Name" name="user_Name" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Sign In</button>
    </form>
  </div>
</body>
</html>
