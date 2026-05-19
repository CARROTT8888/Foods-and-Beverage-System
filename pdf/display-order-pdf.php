<?php
require_once("../fpdf.php");
include "../database/fnbdb.php";
session_start();

if (!isset($_GET['orderId'])) {
    die("Missing orderId");
}

$orderId = intval($_GET['orderId']);

// ================================
// 1. GET ORDER DETAILS
// ================================
$stmtOrder = $conn->prepare("
    SELECT 
        o.orderId, o.totalPrice, o.createdAt, o.orderStatus,
        o.deliveryAddress, o.deliveryState, o.deliveryDistrict, o.deliveryPostalCode, o.extraNote,
        u.name AS customerName,
        b.name AS branchName, b.address AS branchAddress,
        om.methodName
    FROM `order` o
    JOIN user u ON o.userId = u.userId
    JOIN branch b ON o.branchId = b.branchId
    JOIN order_method om ON o.methodId = om.methodId
    WHERE o.orderId = ?
");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$order = $stmtOrder->get_result()->fetch_assoc();
$stmtOrder->close();

if (!$order) {
    die("Order not found.");
}

// ================================
// 2. GET ORDER ITEMS
// ================================
$stmtItems = $conn->prepare("
    SELECT 
        oi.orderItemId, oi.quantity, oi.purchasedPrice,
        f.name AS foodName, f.basePrice
    FROM order_item oi
    JOIN food f ON oi.foodId = f.foodId
    WHERE oi.orderId = ?
    ORDER BY oi.orderItemId ASC
");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$orderItems = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtItems->close();

// ================================
// 3. GET OPTIONS FOR EACH ITEM
// ================================
foreach ($orderItems as &$item) {
    $stmtOpt = $conn->prepare("
        SELECT 
            fog.groupName,
            foi.itemName,
            oio.purchasedPrice
        FROM order_item_option oio
        JOIN food_option_item foi ON oio.optionItemId = foi.optionItemId
        JOIN food_option_group fog ON foi.optionGroupId = fog.optionGroupId
        WHERE oio.orderItemId = ?
        ORDER BY fog.groupName ASC
    ");
    $stmtOpt->bind_param("i", $item['orderItemId']);
    $stmtOpt->execute();
    $item['options'] = $stmtOpt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtOpt->close();
}

// ================================
// PDF DESIGN
// ================================
$pdf = new FPDF("P", "mm", "A4");
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Header (Branch Info)
$pdf->SetFont("Arial", "B", 16);
$pdf->Cell(190, 8, $order['branchName'], 0, 1, "C");

$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell(190, 5, $order['branchAddress'], 0, "C");
$pdf->Ln(2);

$pdf->SetFont("Arial", "B", 12);
$pdf->Cell(190, 8, "E-INVOICE / RECEIPT", 0, 1, "C");

$pdf->Ln(2);
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(5);

// Order Details
$pdf->SetFont("Times", "", 11);

$pdf->Cell(95, 7, "Invoice No: INV-" . str_pad($order['orderId'], 6, "0", STR_PAD_LEFT), 0, 0);
$pdf->Cell(95, 7, "Date: " . date("Y-m-d H:i", strtotime($order['createdAt'])), 0, 1);

$pdf->Cell(95, 7, "Order ID: #" . $order['orderId'], 0, 0);
$pdf->Cell(95, 7, "Method: " . $order['methodName'], 0, 1);

$pdf->Cell(95, 7, "Customer: " . $order['customerName'], 0, 0);
$pdf->Cell(95, 7, "Payment: " . "Payment Status hasnt setted yet", 0, 1);

$pdf->Cell(95, 7, "Order Status: " . $order['orderStatus'], 0, 1);

$pdf->Ln(3);

// Delivery Info (Only if Delivery)
if ($order['methodName'] === "Delivery") {
    $pdf->SetFont("Arial", "B", 11);
    $pdf->Cell(190, 7, "Delivery Details", 0, 1);

    $pdf->SetFont("Arial", "", 10);
    $deliveryText = $order['deliveryAddress'] . "\n"
        . $order['deliveryDistrict'] . ", " . $order['deliveryState'] . "\n"
        . "Postcode: " . $order['deliveryPostalCode'];

    $pdf->MultiCell(190, 5, $deliveryText, 0);
    $pdf->Ln(2);
}

// Extra Note
if (!empty($order['extraNote'])) {
    $pdf->SetFont("Arial", "B", 11);
    $pdf->Cell(190, 7, "Extra Note", 0, 1);

    $pdf->SetFont("Arial", "", 10);
    $pdf->MultiCell(190, 5, $order['extraNote'], 0);
    $pdf->Ln(2);
}

// ================================
// TABLE HEADER
// ================================
$pdf->SetFont("Arial", "B", 11);
$pdf->SetFillColor(240, 240, 240);

$pdf->Cell(90, 8, "Item", 1, 0, "L", true);
$pdf->Cell(20, 8, "Qty", 1, 0, "C", true);
$pdf->Cell(40, 8, "Unit (RM)", 1, 0, "R", true);
$pdf->Cell(40, 8, "Total (RM)", 1, 1, "R", true);

// ================================
// TABLE BODY
// ================================
$pdf->SetFont("Arial", "", 10);

$grandTotal = 0;

foreach ($orderItems as $item) {

    $unitPrice = $item['purchasedPrice'] / max(1, $item['quantity']);
    $lineTotal = $item['purchasedPrice'];
    $grandTotal += $lineTotal;

    $pdf->Cell(90, 8, $item['foodName'], 1, 0);
    $pdf->Cell(20, 8, $item['quantity'], 1, 0, "C");
    $pdf->Cell(40, 8, number_format($unitPrice, 2), 1, 0, "R");
    $pdf->Cell(40, 8, number_format($lineTotal, 2), 1, 1, "R");

    // Options under item
    if (!empty($item['options'])) {
        foreach ($item['options'] as $opt) {
            $optText = "  - " . $opt['groupName'] . ": " . $opt['itemName'];

            if ($opt['purchasedPrice'] > 0) {
                $optText .= " (+RM" . number_format($opt['purchasedPrice'], 2) . ")";
            }

            $pdf->Cell(190, 6, $optText, 1, 1);
        }
    }
}

// ================================
// TOTAL SECTION
// ================================
$pdf->SetFont("Arial", "B", 11);
$pdf->Cell(150, 10, "Grand Total", 1, 0, "R");
$pdf->Cell(40, 10, "RM " . number_format($grandTotal, 2), 1, 1, "R");

$pdf->Ln(8);

// Footer
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(190, 6, "Thank you for your purchase!", 0, 1, "C");

$pdf->SetFont("Arial", "I", 9);
$pdf->Cell(190, 5, "This is a system generated receipt.", 0, 1, "C");

// OUTPUT
$pdf->Output("I", "invoice_order_" . $orderId . ".pdf");
exit;
?>