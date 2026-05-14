<?php
include "../../database/fnbdb.php";
session_start();

if (!isset($_POST['orderItemId'], $_POST['quantity'])) {
    header("Location: /web/orders");
    exit();
}

$orderItemId = intval($_POST['orderItemId']);
$quantity = intval($_POST['quantity']);
$options = $_POST['options'] ?? [];

if ($quantity < 1) $quantity = 1;

$stmtGet = $conn->prepare("SELECT orderId, foodId FROM order_item WHERE orderItemId = ?");
$stmtGet->bind_param("i", $orderItemId);
$stmtGet->execute();
$itemRow = $stmtGet->get_result()->fetch_assoc();
$stmtGet->close();

if (!$itemRow) {
    header("Location: /web/orders");
    exit();
}

$orderId = $itemRow['orderId'];
$foodId = $itemRow['foodId'];

$stmtFood = $conn->prepare("SELECT basePrice FROM food WHERE foodId = ?");
$stmtFood->bind_param("i", $foodId);
$stmtFood->execute();
$foodRow = $stmtFood->get_result()->fetch_assoc();
$stmtFood->close();

$basePrice = $foodRow['basePrice'] ?? 0;

/* Update quantity */
$stmt = $conn->prepare("UPDATE order_item SET quantity = ? WHERE orderItemId = ?");
$stmt->bind_param("ii", $quantity, $orderItemId);
$stmt->execute();
$stmt->close();

/* Delete old options */
$stmtDelete = $conn->prepare("DELETE FROM order_item_option WHERE orderItemId = ?");
$stmtDelete->bind_param("i", $orderItemId);
$stmtDelete->execute();
$stmtDelete->close();

$extraTotal = 0;

/* Insert new options */
if (!empty($options)) {
    foreach ($options as $optId) {
        $optId = intval($optId);

        // get price of option item
        $stmtPrice = $conn->prepare("SELECT extraPrice FROM food_option_item WHERE optionItemId = ?");
        $stmtPrice->bind_param("i", $optId);
        $stmtPrice->execute();
        $row = $stmtPrice->get_result()->fetch_assoc();
        $stmtPrice->close();

        $price = $row['extraPrice'] ?? 0;

        $stmtIns = $conn->prepare("
            INSERT INTO order_item_option (orderItemId, optionItemId, purchasedPrice)
            VALUES (?, ?, ?)
        ");
        $stmtIns->bind_param("iid", $orderItemId, $optId, $price);
        $stmtIns->execute();
        $stmtIns->close();
    }
}

$itemTotal = ($basePrice + $extraTotal) * $quantity;
$stmtUpdateItem = $conn->prepare("UPDATE order_item SET purchasedPrice = ? WHERE orderItemId = ?");
$stmtUpdateItem->bind_param("di", $itemTotal, $orderItemId);
$stmtUpdateItem->execute();
$stmtUpdateItem->close();

$stmtTotal = $conn->prepare("
    SELECT SUM(purchasedPrice) as newTotal
    FROM order_item
    WHERE orderId = ?
");
$stmtTotal->bind_param("i", $orderId);
$stmtTotal->execute();
$totalRow = $stmtTotal->get_result()->fetch_assoc();
$stmtTotal->close();

$newTotal = $totalRow['newTotal'] ?? 0;

$stmtUpdateOrder = $conn->prepare("UPDATE `order` SET totalPrice = ? WHERE orderId = ?");
$stmtUpdateOrder->bind_param("di", $newTotal, $orderId);
$stmtUpdateOrder->execute();
$stmtUpdateOrder->close();

header("Location: /web/orders");
exit();
?>