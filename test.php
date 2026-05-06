<?php
session_start();
include_once './database/fnbdb.php';

if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}

$userId = $_SESSION['userId'];
$orderId = $_SESSION['orderId'] ?? null;

if (!$orderId) {
    header("Location: order-location.php");
    exit();
}

// 處理刪除 order item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeItem'])) {
    $orderItemId = intval($_POST['orderItemId']);

    $stmtDel = $conn->prepare("DELETE FROM order_item_option WHERE orderItemId = ?");
    $stmtDel->bind_param("i", $orderItemId);
    $stmtDel->execute();
    $stmtDel->close();

    $stmtDel2 = $conn->prepare("DELETE FROM order_item WHERE orderItemId = ? AND orderId = ?");
    $stmtDel2->bind_param("ii", $orderItemId, $orderId);
    $stmtDel2->execute();
    $stmtDel2->close();

    // 更新總價
    $stmtTotal = $conn->prepare("SELECT SUM(purchasedPrice) as total FROM order_item WHERE orderId = ?");
    $stmtTotal->bind_param("i", $orderId);
    $stmtTotal->execute();
    $totalRow = $stmtTotal->get_result()->fetch_assoc();
    $stmtTotal->close();

    $newTotal = $totalRow['total'] ?? 0;
    $stmtUpdateTotal = $conn->prepare("UPDATE `order` SET totalPrice = ? WHERE orderId = ?");
    $stmtUpdateTotal->bind_param("di", $newTotal, $orderId);
    $stmtUpdateTotal->execute();
    $stmtUpdateTotal->close();

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}

// 查詢 order items
$stmtItems = $conn->prepare("
    SELECT oi.orderItemId, oi.quantity, oi.purchasedPrice,
           f.name as foodName, f.image, f.basePrice,
           c.name as categoryName
    FROM order_item oi
    JOIN food f ON oi.foodId = f.foodId
    JOIN food_category c ON f.categoryId = c.categoryId
    WHERE oi.orderId = ?
    ORDER BY oi.orderItemId ASC
");
$stmtItems->bind_param("i", $orderId);
$stmtItems->execute();
$orderItems = $stmtItems->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtItems->close();

// 查詢每個 item 的 options
foreach ($orderItems as &$item) {
    $stmtOpts = $conn->prepare("
        SELECT foi.itemName, oio.purchasedPrice, fog.groupName
        FROM order_item_option oio
        JOIN food_option_item foi ON oio.optionItemId = foi.optionItemId
        JOIN food_option_group fog ON foi.optionGroupId = fog.optionGroupId
        WHERE oio.orderItemId = ?
    ");
    $stmtOpts->bind_param("i", $item['orderItemId']);
    $stmtOpts->execute();
    $item['options'] = $stmtOpts->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtOpts->close();
}

// 查詢總價
$stmtOrder = $conn->prepare("SELECT totalPrice, methodId FROM `order` WHERE orderId = ?");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$orderRow = $stmtOrder->get_result()->fetch_assoc();
$stmtOrder->close();
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

<body>

</body>

</html>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <button onclick="history.back()"
            class="flex items-center gap-1 text-slate-500 hover:text-slate-800 transition text-sm">
            <i class='bx bx-chevron-left text-xl'></i>
            Back to Menu
        </button>
        <h1 class="font-extrabold text-3xl">Your Cart</h1>
    </div>

    <?php if (empty($orderItems)): ?>
        <!-- Empty state -->
        <div class="flex flex-col items-center justify-center py-24 text-slate-400">
            <i class='bx bx-cart text-7xl mb-4'></i>
            <p class="text-lg font-medium">Your cart is empty</p>
            <a href="/web/menu"
                class="mt-4 px-6 py-2 bg-primary rounded-xl text-sm font-semibold hover:bg-amber-400 transition text-slate-800">
                Browse Menu
            </a>
        </div>
    <?php else: ?>

        <div class="flex flex-row justify-between">
            <!-- Order Items -->
            <div class="flex flex-col gap-4 mb-8 w-full">
                <?php foreach ($orderItems as &$item): ?>
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex gap-0"
                        id="cart-item-<?php echo $item['orderItemId']; ?>">

                        <!-- Image -->
                        <div class="w-28 shrink-0">
                            <?php if ($item['image']): ?>
                                <img src="/Foods-and-Beverage-System/uploads/menus/<?php echo htmlspecialchars($item['image']); ?>"
                                    class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-amber-50 flex items-center justify-center min-h-[100px]">
                                    <i class='bx bxs-food-menu text-3xl text-amber-300'></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Details -->
                        <div class="flex-1 p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span
                                        class="text-xs text-slate-400 font-medium"><?php echo htmlspecialchars($item['categoryName']); ?></span>
                                    <h3 class="font-bold text-slate-800 text-base leading-tight">
                                        <?php echo htmlspecialchars($item['foodName']); ?>
                                    </h3>
                                </div>
                                <!-- Remove button -->
                                <button onclick="removeItem(<?php echo $item['orderItemId']; ?>)"
                                    class="text-slate-300 hover:text-red-500 transition shrink-0">
                                    <i class='bx bx-trash text-lg'></i>
                                </button>
                            </div>

                            <!-- Options -->
                            <?php if (!empty($item['options'])): ?>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    <?php foreach ($item['options'] as $opt): ?>
                                        <span
                                            class="text-xs px-2 py-0.5 bg-amber-50 border border-amber-200 text-amber-700 rounded-full">
                                            <?php echo htmlspecialchars($opt['itemName']); ?>
                                            <?php if ($opt['purchasedPrice'] > 0): ?>
                                                +RM <?php echo number_format($opt['purchasedPrice'], 2); ?>
                                            <?php endif; ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Qty + Price -->
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-slate-400">Qty: <span
                                        class="font-semibold text-slate-600"><?php echo $item['quantity']; ?></span></span>
                                <span class="font-bold text-primary text-base">
                                    RM <?php echo number_format($item['purchasedPrice'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 mb-6">
                <h2 class="font-bold text-slate-700 mb-4">Order Summary</h2>
                <div class="flex justify-between text-sm text-slate-500 mb-2">
                    <span>Subtotal</span>
                    <span>RM <?php echo number_format($orderRow['totalPrice'], 2); ?></span>
                </div>
                <hr class="my-3 border-slate-100">
                <div class="flex justify-between font-bold text-slate-800 text-lg">
                    <span>Total</span>
                    <span class="text-primary">RM <?php echo number_format($orderRow['totalPrice'], 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Checkout Button -->
        <a href="/web/checkout"
            class="flex items-center justify-center gap-2 w-full bg-primary hover:bg-amber-400 transition font-bold py-4 rounded-2xl text-slate-800 text-base shadow-sm hover:shadow-md">
            <i class='bx bxs-lock-alt'></i>
            Proceed to Checkout
        </a>

        <!-- Continue Shopping -->
        <a href="/web/menu"
            class="flex items-center justify-center gap-2 w-full mt-3 py-3 text-sm text-slate-500 hover:text-slate-800 transition">
            <i class='bx bx-arrow-back'></i>
            Continue Shopping
        </a>

    <?php endif; ?>
</div>

<script>
    function removeItem(orderItemId) {
        if (!confirm('Remove this item from cart?')) return;

        fetch('/web/test', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `removeItem=1&orderItemId=${orderItemId}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-item-' + orderItemId).remove();
                    location.reload(); // 更新總價
                }
            });
    }
</script>