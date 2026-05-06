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

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['addToCart'])) {
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
        $stmtOption = $conn->prepare("SELECT SUM(extraPrice) as total FROM food_option_item WHERE optionItemId IN ($placeholders)");
        $stmtOption->bind_param(str_repeat('i', count($selectedOptions)), ...$selectedOptions);
        $stmtOption->execute();
        $optionResult = $stmtOption->get_result()->fetch_assoc();
        $extraPrice = $optionResult['total'] ?? 0;
        $stmtOption->close();
    }

    $purchasedPrice = ($food['basePrice'] + $extraPrice) * $quantity;

    $stmtInsert = $conn->prepare("
    INSERT INTO order_item (orderId, foodId, quantity, purchasedPrice) 
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        quantity = quantity + VALUES(quantity),
        purchasedPrice = purchasedPrice + VALUES(purchasedPrice)
");
    $stmtInsert->bind_param("iiid", $orderId, $foodId, $quantity, $purchasedPrice);
    $stmtInsert->execute();

    if ($stmtInsert->insert_id > 0) {
        $orderItemId = $stmtInsert->insert_id;
    } else {
        $stmtGetId = $conn->prepare("SELECT orderItemId FROM order_item WHERE orderId = ? AND foodId = ?");
        $stmtGetId->bind_param("ii", $orderId, $foodId);
        $stmtGetId->execute();
        $orderItemId = $stmtGetId->get_result()->fetch_assoc()['orderItemId'];
        $stmtGetId->close();
    }
    $stmtInsert->close();

    if (!empty($selectedOptions)) {
        $stmtDeleteOption = $conn->prepare("DELETE FROM order_item_option WHERE orderItemId = ?");
        $stmtDeleteOption->bind_param("i", $orderItemId);
        $stmtDeleteOption->execute();
        $stmtDeleteOption->close();

        foreach ($selectedOptions as $optionItemId) {
            $stmtOptionPrice = $conn->prepare("SELECT extraPrice FROM food_option_item WHERE optionItemId = ?");
            $stmtOptionPrice->bind_param("i", $optionItemId);
            $stmtOptionPrice->execute();
            $optionPrice = $stmtOptionPrice->get_result()->fetch_assoc();
            $stmtOptionPrice->close();

            $stmtInsertOption = $conn->prepare("INSERT INTO order_item_option (orderItemId, optionItemId, purchasedPrice) VALUES (?, ?, ?)");
            $stmtInsertOption->bind_param("iid", $orderItemId, $optionItemId, $optionPrice['extraPrice']);
            $stmtInsertOption->execute();
            $stmtInsertOption->close();
        }
    }

    $stmtTotal = $conn->prepare("SELECT SUM(purchasedPrice) as total FROM order_item WHERE orderId = ?");
    $stmtTotal->bind_param("i", $orderId);
    $stmtTotal->execute();
    $stmtRow = $stmtTotal->get_result()->fetch_assoc();
    $stmtTotal->close();

    $stmtUpdateTotal = $conn->prepare("UPDATE `order` SET totalPrice = ? WHERE orderId = ?");
    $stmtUpdateTotal->bind_param("di", $stmtRow['total'], $orderId);
    $stmtUpdateTotal->execute();
    $stmtUpdateTotal->close();

    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'redirect' => '/web/orders'
    ]);
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="app.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Floudemo</title>
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
        });
    </script>
</head>

<body class="flex flex-col justify-center h-screen">
    <div class="h-screen w-full overflow-y-scroll">
        <?php include_once './includes/navbar.php' ?>
        <?php include './includes/menu/body.php'; ?>
        <?php include "./includes/footer.php"; ?>
    </div>
</body>

</html>