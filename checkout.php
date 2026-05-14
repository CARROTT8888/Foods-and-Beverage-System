<?php
session_start();
include './database/fnbdb.php';

// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}

$userId = $_SESSION['userId'];
$branchId = $_SESSION['branchId'] ?? null;
$orderId = $_SESSION['orderId'] ?? null;

if (!$orderId) {
    header("Location: order-location.php");
    exit();
}

$stmtBranch = $conn->prepare("SELECT * FROM branch WHERE branchId = ?");
$stmtBranch->bind_param("i", $branchId);
$stmtBranch->execute();
$branchResult = $stmtBranch->get_result()->fetch_assoc();
$stmtBranch->close();

$stmtCategory = $conn->prepare("SELECT * FROM food_category WHERE branchId = ? AND status = 'Visible' ORDER BY categoryId ASC");
$stmtCategory->bind_param("i", $branchId);
$stmtCategory->execute();
$categoryResult = $stmtCategory->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtCategory->close();

$stmtCart = $conn->prepare("SELECT SUM(quantity) as totalItems FROM order_item WHERE orderId = ?");
$stmtCart->bind_param("i", $orderId);
$stmtCart->execute();
$cartRow = $stmtCart->get_result()->fetch_assoc();
$cartCount = $cartRow['totalItems'] ?? 0;
$stmtCart->close();

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['updateOrderItem'])) {
    $orderItemId = intval($_POST['orderItemId']);
    $foodId = intval($_POST['foodId']);
    $quantity = intval($_POST['quantity']) ?: 1;
    $selectedOptions = $_POST['options'] ?? [];

    $stmtFood = $conn->prepare("SELECT basePrice FROM food WHERE foodId = ?");
    $stmtFood->bind_param("i", $foodId);
    $stmtFood->execute();
    $food = $stmtFood->get_result()->fetch_assoc();
    $stmtFood->close();

    $extraPrice = 0;
    if (!empty($selectedOptions)) {
        $placeholders = implode(',', array_fill(0, count($selectedOptions), '?'));
        $stmtOpt = $conn->prepare("SELECT SUM(extraPrice) as total FROM food_option_item WHERE optionItemId IN ($placeholders)");
        $stmtOpt->bind_param(str_repeat('i', count($selectedOptions)), ...$selectedOptions);
        $stmtOpt->execute();
        $optResult = $stmtOpt->get_result()->fetch_assoc();
        $extraPrice = $optResult['total'] ?? 0;
        $stmtOpt->close();
    }

    $purchasedPrice = ($food['basePrice'] + $extraPrice) * $quantity;

    $stmtUpdate = $conn->prepare("UPDATE order_item SET quantity = ?, purchasedPrice = ? WHERE orderItemId = ? AND orderId = ?");
    $stmtUpdate->bind_param("idii", $quantity, $purchasedPrice, $orderItemId, $orderId);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    $stmtDelOpt = $conn->prepare("DELETE FROM order_item_option WHERE orderItemId = ?");
    $stmtDelOpt->bind_param("i", $orderItemId);
    $stmtDelOpt->execute();
    $stmtDelOpt->close();

    foreach ($selectedOptions as $optionItemId) {
        $stmtOptPrice = $conn->prepare("SELECT extraPrice FROM food_option_item WHERE optionItemId = ?");
        $stmtOptPrice->bind_param("i", $optionItemId);
        $stmtOptPrice->execute();
        $optPrice = $stmtOptPrice->get_result()->fetch_assoc();
        $stmtOptPrice->close();

        $stmtInsertOpt = $conn->prepare("INSERT INTO order_item_option (orderItemId, optionItemId, purchasedPrice) VALUES (?, ?, ?)");
        $stmtInsertOpt->bind_param("iid", $orderItemId, $optionItemId, $optPrice['extraPrice']);
        $stmtInsertOpt->execute();
        $stmtInsertOpt->close();
    }

    $stmtTotal = $conn->prepare("SELECT SUM(purchasedPrice) as total FROM order_item WHERE orderId = ?");
    $stmtTotal->bind_param("i", $orderId);
    $stmtTotal->execute();
    $totalRow = $stmtTotal->get_result()->fetch_assoc();
    $stmtTotal->close();

    $stmtUpdateTotal = $conn->prepare("UPDATE `order` SET totalPrice = ? WHERE orderId = ?");
    $stmtUpdateTotal->bind_param("di", $totalRow['total'], $orderId);
    $stmtUpdateTotal->execute();
    $stmtUpdateTotal->close();

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="app.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Orders - Floudemo</title>
    <link rel="Icon" href="./assets/logo.png" sizes="64x64">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        /*mint: {
                            500: 'oklch(0.72 0.11 178)'
                        },*/
                        foreground: 'oklch(0.2686 0 0)',
                        primary: 'oklch(0.7686 0.1647 70.0804)',
                        primaryForeground: 'oklch(0 0 0)',
                        secondary: 'oklch(0.9670 0.0029 264.5419)',
                        secondaryForeground: 'oklch(0.4461 0.0263 256.8018)',
                        muted: 'oklch(0.9846 0.0017 247.8389)',
                        mutedForeground: 'oklch(0.5510 0.0234 264.3637)',
                        accent: 'oklch(0.9869 0.0214 95.2774)',
                        accentForeground: 'oklch(0.4732 0.1247 46.2007)',
                        destructive: 'oklch(0.6368 0.2078 25.3313)',
                    },
                    borderRadius: {
                        'custom': '0.375rem'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    },
                }
            }
        };
        document.addEventListener("DOMContentLoaded", function () {
            const currentPath = window.location.pathname;
            document.querySelectorAll(".nav-link").forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath === linkPath) {
                    link.classList.add("text-primary", "font-semibold");
                    link.setAttribute("aria-current", "page");
                }
            });
            const dialog4 = document.getElementById("deleteOrderDialog");
            window.openDeleteOrderDialog = function (orderItemId) {
                document.getElementById('deleteOrderItemId').value = orderItemId;
                document.getElementById('deleteOrderDialog').classList.remove('opacity-0', 'pointer-events-none');
            }
            window.closeDeleteOrderDialog = function () {
                dialog4.classList.remove("opacity-100");
                dialog4.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeDeleteOrderDialog();
                }
            });
            const dialog4a = document.getElementById("cashSubmissionDialog");
            window.openCashSubmissionDialog = function () {
                dialog4a.classList.remove("opacity-0", "pointer-events-none");
                dialog4a.classList.add("opacity-100");
            }
            window.closeCashSubmissionDialog = function () {
                dialog4a.classList.remove("opacity-100");
                dialog4a.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeCashSubmissionDialog();
                }
            });
        });
    </script>
</head>

<body class="flex flex-col justify-center h-screen">
    <div class="h-screen w-full overflow-y-scroll">
        <?php include './includes/navbar.php'; ?>
        <?php include_once './includes/checkout/body.php'; ?>
        <?php include_once './includes/footer.php'; ?>
    </div>
</body>

</html>

<script>
    function removeItem(orderItemId) {
        if (!confirm('Remove this item from cart?')) return;

        fetch('/web/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `removeItem=1&orderItemId=${orderItemId}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-item-' + orderItemId).remove();
                    location.reload();
                }
            });
    }
</script>