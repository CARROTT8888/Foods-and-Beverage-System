<?php
include '../../database/fnbdb.php';
session_start();

header('Content-Type: application/json');

if (!isset($_GET['orderItemId'])) {
    echo json_encode(["error" => "Missing id"]);
    exit();
}

$orderItemId = intval($_GET['orderItemId']);

$stmtOrderItem = $conn->prepare("SELECT quantity, foodId FROM order_item WHERE orderItemId = ?");
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
    SELECT foi.optionItemId, foi.itemName, foi.extraPrice,
        CASE WHEN oio.optionItemId IS NOT NULL THEN 1 ELSE 0 END AS selected
    FROM food_option_item foi
    JOIN food_option_group fog ON foi.optionGroupId = fog.optionGroupId
    LEFT JOIN order_item_option oio 
        ON foi.optionItemId = oio.optionItemId AND oio.orderItemId = ?
    WHERE fog.foodId = ?
");
$stmtOption->bind_param("ii", $orderItemId, $foodId);
$stmtOption->execute();
$orderOptions = $stmtOption->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtOption->close();

echo json_encode([
    "quantity" => $orderItem['quantity'],
    "foodId" => $orderItem['foodId'],
    "options" => $orderOptions
]);
?>