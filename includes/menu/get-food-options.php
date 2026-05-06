<?php
include './../../database/fnbdb.php';

header('Content-Type: application/json');

$foodId = intval($_GET['foodId'] ?? 0);
if (!$foodId) {
    echo json_encode([]);
    exit();
}

$query = "SELECT * FROM food_option_group WHERE foodId = ? ORDER BY optionGroupId ASC";
$stmtGroups = $conn->prepare($query);
$stmtGroups->bind_param("i", $foodId);
$stmtGroups->execute();
$groups = $stmtGroups->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtGroups->close();

foreach ($groups as &$group) {
    $stmtItem = $conn->prepare("SELECT * FROM food_option_item WHERE optionGroupId = ? ORDER BY optionItemId ASC");
    $stmtItem->bind_param("i", $group['optionGroupId']);
    $stmtItem->execute();
    $group['items'] = $stmtItem->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtItem->close();
}

echo json_encode($groups);