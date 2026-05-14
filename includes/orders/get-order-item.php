<?php
include '../../database/fnbdb.php';
session_start();

header('Content-Type: application/json');

if (!isset($_GET['orderItemId'])) {
    echo json_encode(["error" => "Missing id"]);
    exit();
}

$orderItemId = intval($_GET['orderItemId']);

$stmtOrderItem = $conn->prepare("
    SELECT oi.quantity, oi.foodId,
        f.name, f.description, f.image, f.basePrice
    FROM order_item oi
    LEFT JOIN food f ON oi.foodId = f.foodId
    WHERE oi.orderItemId = ?
");
$stmtOrderItem->bind_param("i", $orderItemId);
$stmtOrderItem->execute();
$orderItem = $stmtOrderItem->get_result()->fetch_assoc();
$stmtOrderItem->close();

if (!$orderItem) {
    echo json_encode(["error" => "Order item not found"]);
    exit();
}

$foodId = $orderItem['foodId'];

$stmtOption = $conn->prepare("
    SELECT foi.optionItemId, foi.itemName, foi.extraPrice, fog.groupName,
        CASE WHEN oio.optionItemId IS NOT NULL THEN 1 ELSE 0 END AS selected
    FROM food_option_item foi
    JOIN food_option_group fog ON foi.optionGroupId = fog.optionGroupId
    LEFT JOIN order_item_option oio
        ON foi.optionItemId = oio.optionItemId AND oio.orderItemId = ?
    WHERE fog.foodId = ?
    ORDER BY fog.groupName ASC, foi.optionItemId ASC
");
$stmtOption->bind_param("ii", $orderItemId, $foodId);
$stmtOption->execute();
$orderOptions = $stmtOption->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtOption->close();

echo json_encode([
    //"orderItemId" => $orderItem['orderItemId'],
    "quantity" => $orderItem['quantity'],
    "foodId" => $orderItem['foodId'],
    "foodName" => $orderItem['name'],
    "foodDescription" => $orderItem['description'],
    "foodImage" => $orderItem['image'],
    "basePrice" => $orderItem['basePrice'],
    "options" => $orderOptions
]);
?>