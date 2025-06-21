<?php
include("connect.php");

$courseID = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT subject_ID AS id, subject_name AS name FROM subject WHERE course_ID = ?");
$stmt->bind_param("i", $courseID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

?>