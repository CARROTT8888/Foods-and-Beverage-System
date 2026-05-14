<?php
if (isset($_SESSION['branchId'])) {
    $branchId = $_SESSION['branchId'];

    $stmtBranch = $conn->prepare("SELECT * FROM branch WHERE branchId=?");
    $stmtBranch->bind_param("i", $branchId);
    $stmtBranch->execute();
    $branchResult = $stmtBranch->get_result();
    $branch = $branchResult->fetch_assoc();
    $stmtBranch->close();
}

$stmtCategory = $conn->prepare("SELECT * FROM food_category WHERE branchId = ? AND status = 'Visible' ORDER BY categoryId ASC");
$stmtCategory->bind_param("i", $branchId);
$stmtCategory->execute();
$categories = $stmtCategory->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtCategory->close();

$stmtCart = $conn->prepare("SELECT SUM(quantity) as totalItems FROM order_item WHERE orderId = ?");
$stmtCart->bind_param("i", $orderId);
$stmtCart->execute();
$cartRow = $stmtCart->get_result()->fetch_assoc();
$cartCount = $cartRow['totalItems'] ?? 0;
$stmtCart->close();

// checks order items
$stmtItems = $conn->prepare("
    SELECT oi.orderItemId, oi.quantity, oi.purchasedPrice,
        f.name as foodName, f.image, f.basePrice, f.foodId,
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

foreach ($orderItems as &$item) {
    $stmtOptions = $conn->prepare("
        SELECT foi.optionItemId, foi.itemName, oio.purchasedPrice, fog.groupName
        FROM order_item_option oio
        JOIN food_option_item foi ON oio.optionItemId = foi.optionItemId
        JOIN food_option_group fog ON foi.optionGroupId = fog.optionGroupId
        WHERE oio.orderItemId = ?
    ");
    $stmtOptions->bind_param("i", $item['orderItemId']);
    $stmtOptions->execute();
    $item['options'] = $stmtOptions->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmtOptions->close();

    /*echo "<pre>";
    print_r($orderItems);
    echo "</pre>";
    exit();*/
}

if (empty($orderItems)) {
    echo "<script>window.location.href='/web/orders'</script>";
}

$stmtOrder = $conn->prepare("SELECT totalPrice, methodId FROM `order` WHERE orderId = ?");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$orderRow = $stmtOrder->get_result()->fetch_assoc();
$stmtOrder->close();

$stmtPay = $conn->prepare("SELECT paymentMethod, paymentStatus FROM payment WHERE orderId = ?");
$stmtPay->bind_param("i", $orderId);
$stmtPay->execute();
$paymentRow = $stmtPay->get_result()->fetch_assoc();
$stmtPay->close();

$paymentStatus = $paymentRow['paymentStatus'] ?? null;
$paymentMethod = $paymentRow['paymentMethod'] ?? null;

$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['savePayment'])) {
    $orderId = intval($_POST['orderId']);
    $paymentMethod = $_POST['paymentMethod'] ?? '';

    if (empty($orderId) || empty($paymentMethod)) {
        echo "<script>alert('Oppps... Invalid payment request.');</script>";
    } else {
        $paymentStatus = "Pending";

        if ($paymentMethod === 'Online Payment') {
            $paymentStatus = "Success";
        }

        $checkQuery = "SELECT paymentId FROM payment WHERE orderId=?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $orderId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $existingPayment = $checkResult->fetch_assoc();
        $checkStmt->close();

        if ($existingPayment) {
            $updateQuery = "UPDATE payment SET paymentMethod = ?, paymentStatus = ? WHERE orderId = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ssi", $paymentMethod, $paymentStatus, $orderId);

            if ($updateStmt->execute()) {
                $updateStmt->close();
                echo "<script>window.location.href='/web/checkout'</script>";
                $successMessage = "You have paid successfully.";
                exit();
            } else {
                echo "Update failed.";
            }
            $updateStmt->close();
        } else {
            $insertQuery = "INSERT INTO payment (paymentMethod, paymentStatus, orderId) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ssi", $paymentMethod, $paymentStatus, $orderId);

            if ($insertStmt->execute()) {
                $insertStmt->close();
                echo "<script>window.location.href='/web/checkout'</script>";
                exit();
            } else {
                echo "Update failed.";
            }
            $insertStmt->close();
        }
    }
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
    <!-- Header -->
    <?php include 'header.php'; ?>
    <?php if (!empty($orderItems)): ?>

        <div class="grid md:grid-cols-4 grid-cols-1 gap-4">
            <div class="relative tab-group md:col-span-2 col-span-2">
                <div class="flex bg-slate-100 p-0.5 relative rounded-lg" role="tablist">
                    <div
                        class="absolute top-1 left-0.5 h-8 bg-primary rounded-md shadow-sm transition-all duration-300 transform scale-x-0 translate-x-0 tab-indicator z-0">
                    </div>
                    <?php if ($paymentStatus === 'Success' && $paymentMethod === 'Online Payment'): ?>
                        <a href="#"
                            class="cursor-not-allowed text-secondaryForeground line-through text-sm font-medium inline-block py-2 px-4 transition-all duration-300 relative z-1 mr-1"
                            data-tab-target="tab1-group">
                            Cash
                        </a>
                    <?php else: ?>
                        <a href="#"
                            class="tab-link text-sm font-medium active inline-block py-2 px-4 text-slate-800 transition-all duration-300 relative z-1 mr-1"
                            data-tab-target="tab1-group">
                            Cash
                        </a>
                    <?php endif; ?>
                    <?php if ($paymentStatus === 'Pending' && $paymentMethod === 'Cash'): ?>
                        <a href="#"
                            class="cursor-not-allowed text-secondaryForeground line-through text-sm font-medium inline-block py-2 px-4 text-slate-800 transition-all duration-300 relative z-1 mr-1"
                            data-tab-target="tab2-group">
                            Online Payment
                        </a>
                    <?php else: ?>
                        <a href="#"
                            class="tab-link text-sm font-medium inline-block py-2 px-4 text-slate-800 transition-all duration-300 relative z-1 mr-1"
                            data-tab-target="tab2-group">
                            Online Payment
                        </a>
                    <?php endif; ?>
                </div>
                <div class="mt-4 tab-content-container">
                    <div id="tab1-group" class="tab-content text-slate-800 block">
                        <?php include 'cash-form.php'; ?>
                    </div>
                    <div id="tab2-group" class="tab-content text-slate-800 hidden">
                        <?php include 'online-payment-form.php'; ?>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 col-span-2">
                <h1 class="text-3xl font-extrabold mb-2">Orders</h1>
                <div class="space-y-4">
                    <?php foreach ($orderItems as &$item): ?>
                        <div class="bg-white rounded-lg border h-48 border-slate-200 hover:bg-accent hover:border-accentForeground transition shadow-sm overflow-hidden flex gap-0"
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
                                    <div class="">
                                        <span
                                            class="flexa items-center gap-2 text-secondaryForeground border border-secondaryForeground bg-secondary rounded-lg text-xs w-auto mx-autoa absolutea p-1 px-2"><?php echo htmlspecialchars($item['categoryName']); ?></span>
                                        <h3 class="font-extrabold text-slate-800 leading-tight text-xl">
                                            <?php echo htmlspecialchars($item['foodName']); ?>
                                        </h3>
                                    </div>
                                </div>

                                <!-- Options -->
                                <?php if (!empty($item['options'])): ?>
                                    <div class="mt-2 flex flex-wrap gap-1">
                                        <?php foreach ($item['options'] as $opt): ?>
                                            <span
                                                class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-full text-xs p-0.5 px-2 shadow-sm bg-accent border-primary text-primary">
                                                <?php echo htmlspecialchars($opt['itemName']); ?>
                                                <?php if ($opt['purchasedPrice'] > 0): ?>
                                                    <span
                                                        class="text-green-600">+RM<?php echo number_format($opt['purchasedPrice'], 2); ?></span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-sm text-slate-400">Quantity: <span
                                            class="font-semibold text-slate-600"><?php echo $item['quantity']; ?></span></span>
                                    <span class="font-bold text-green-600 text-base">
                                        RM <?php echo number_format($item['purchasedPrice'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div
                        class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mb-6 md:col-span-2 col-span-2 h-[280px]">
                        <h2 class="font-bold text-slate-700 mb-4">Order Summary</h2>
                        <div class="flex justify-between text-sm text-slate-500 mb-2">
                            <span>Subtotal</span>
                            <span>RM <?php echo number_format($orderRow['totalPrice'], 2); ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-500 mb-2">
                            <span>Delivery Charge</span>
                            <span>-</span>
                        </div>
                        <hr class="my-3 border-slate-100">
                        <div class="flex justify-between font-bold text-slate-800 text-lg">
                            <span>Total</span>
                            <span class="text-green-600">RM <?php echo number_format($orderRow['totalPrice'], 2); ?></span>
                        </div>
                        <div class="mt-6">
                            <?php if ($paymentStatus === 'Pending' || $paymentStatus === 'Success'): ?>
                                <button onclick="window.location.href='/web/confirmation'"
                                    class="inline-flex gap-2 items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                    data-shape="default" data-width="full">
                                    <i class='bx bxs-check-circle'></i>
                                    Confirmation
                                </button>
                            <?php else: ?>
                                <button type="button"
                                    class="inline-flex gap-2 items-center cursor-not-allowed justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-amber-600 text-foreground border-secondary w-full"
                                    data-shape="default" data-width="full">
                                    <i class='bx bxs-check-circle'></i>
                                    Confirmation
                                </button>
                            <?php endif; ?>
                            <button onclick="window.location.href='/web/menu'"
                                class="text-center w-full mt-2 p-2 gap-2 text-md text-primary hover:underline items-center">
                                <i class='bx bxs-left-arrow-circle'></i> Continue Ordering
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>