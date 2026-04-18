<?php
include './../../../database/fnbdb.php'; // adjust path if needed

header('Content-Type: application/json');

if (!isset($_GET['branchId'])) {
    echo json_encode([]);
    exit();
}

$branchId = (int) $_GET['branchId'];

$query = "SELECT categoryId, name FROM food_category WHERE branchId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $branchId);
$stmt->execute();

$result = $stmt->get_result();

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);

$stmt->close();
$conn->close();
?>