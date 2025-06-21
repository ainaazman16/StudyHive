<?php
include("connect.php");

$facultyID = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT course_ID AS id, course_name AS name FROM course WHERE faculty_ID = ?");
$stmt->bind_param("i", $facultyID);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

?>