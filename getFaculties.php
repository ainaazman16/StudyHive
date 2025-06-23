<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("connect.php");

$uniID = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT faculty_ID AS id, faculty_Name AS name FROM faculty WHERE uni_ID = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $uniID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

?>
