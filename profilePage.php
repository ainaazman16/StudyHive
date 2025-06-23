<?php
session_start();
include('connect.php');

// Check if user is logged in
if (!isset($_SESSION['user_ID'])) {
    die("Please log in first.");
}

$userID = $_SESSION['user_ID'];

// Get user details from 'user' table
$sql = "SELECT * FROM user WHERE user_ID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css">
      <link rel="icon" type="image/png" href="images/logo.png">
  <style>
    body {
      background-color: #ffffff;
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
    }

    table {
        border-collapse: collapse;
        width: 60%;
        margin: 20px auto;
        background-color: #f9f9f9;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px 15px;
        text-align: left;
    }

    th {
        background-color: #f0c3f0;
        color: #333;
        width: 30%;
    }

    td {
        background-color: #fff;
    }

    .imgcenter {
        display: block;
        margin: 15px auto 10px auto;
        max-width: 200px;
        height: auto;
        border-radius: 10px;
        border: 2px solid #ccc;
    }

    .no-picture {
        text-align: center;
        color: #777;
        font-style: italic;
        margin-bottom: 10px;
    }
  </style>
</head>
<body>

<?php include("head.php"); ?>
<h1>Your Profile</h1>

<section>
<?php
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $imageName = $row["profile_picture"];
    $imagePath = "uploads/" . $imageName;

    if (!empty($imageName) && file_exists($imagePath)) {
        echo "<img class='imgcenter' src='$imagePath' alt='User Picture'>";
    } else {
        echo "<div class='no-picture'>No file chosen</div>";
    }

    echo "<table>";
    echo "<tr><th>Full Name:</th><td>" . htmlspecialchars($row["user_Fname"]) . "</td></tr>";
    echo "<tr><th>Email:</th><td>" . htmlspecialchars($row["email"]) . "</td></tr>";
    echo "<tr><th>Phone Number:</th><td>" . htmlspecialchars($row["phone"]) . "</td></tr>";
    echo "<tr><th>Gender:</th><td>" . htmlspecialchars($row["gender"]) . "</td></tr>";
    echo "<tr><th>Username:</th><td>" . htmlspecialchars($row["user_Name"]) . "</td></tr>";
    echo "<tr><th>Password:</th><td>******</td></tr>";
    echo "</table>";
} else {
    echo "<p style='text-align:center; color:red;'>User not found.</p>";
}
?>
</section>

<?php include("footer.php"); ?>

</body>
</html>
