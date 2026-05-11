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

/* Update quantity */
$stmt = $conn->prepare("UPDATE order_item SET quantity = ? WHERE orderItemId = ?");
$stmt->bind_param("ii", $quantity, $orderItemId);
$stmt->execute();
$stmt->close();

/* Delete old options */
$stmtDel = $conn->prepare("DELETE FROM order_item_option WHERE orderItemId = ?");
$stmtDel->bind_param("i", $orderItemId);
$stmtDel->execute();
$stmtDel->close();

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

        $price = $row['price'] ?? 0;

        $stmtIns = $conn->prepare("
            INSERT INTO order_item_option (orderItemId, optionItemId, purchasedPrice)
            VALUES (?, ?, ?)
        ");
        $stmtIns->bind_param("iid", $orderItemId, $optId, $price);
        $stmtIns->execute();
        $stmtIns->close();
    }
}

header("Location: /web/orders");
exit();
?>