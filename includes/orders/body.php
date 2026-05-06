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

foreach ($orderItems as &$item) {
    $stmtOptions = $conn->prepare("
        SELECT foi.itemName, oio.purchasedPrice, fog.groupName
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

$stmtOrder = $conn->prepare("SELECT totalPrice, methodId FROM `order` WHERE orderId = ?");
$stmtOrder->bind_param("i", $orderId);
$stmtOrder->execute();
$orderRow = $stmtOrder->get_result()->fetch_assoc();
$stmtOrder->close();
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
    <!-- Header -->
    <?php include 'header.php'; ?>
    <?php if (empty($orderItems)): ?>
        <div class="container flex items-center px-6 py-12 mx-auto">
            <div class="flex flex-col justify-center items-center max-w-7xl mx-auto text-center">
                <i class='bx bxs-plus-square text-9xl text-primary'></i>
                <h1 class="mt-3 text-2xl font-extrabold text-gray-800 md:text-3xl">Where is Order?</h1>
                <p class="mt-4 text-secondaryForeground">Your Order is Empty. Let's Started to Order Now! (´▽`ʃ♡ƪ)</p>

                <div class="flex items-center w-full mt-6 gap-x-3 shrink-0 sm:w-auto">
                    <button onclick="window.location.href='/web/home'"
                        class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 transition-colors duration-200 bg-white border rounded-md gap-x-2 sm:w-auto hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 rtl:rotate-180">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                        </svg>


                        <span>Back to Home</span>
                    </button>

                    <button onclick="window.location.href='/web/menu'"
                        class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground">
                        Let's Order!
                    </button>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
            <div class="grid grid-cols-1 space-y-2 col-span-2">
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
                                <div class="flex -mr-2">
                                    <button onclick="openDeleteOrderDialog(<?php echo $item['orderItemId']; ?>)"
                                        class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none data-[shape=circular]:rounded-full text-sm min-w-[34px] min-h-[34px] rounded-md bg-transparent border-transparent text-primary hover:bg-amber-200/50 hover:border-amber-200/10 shadow-none hover:shadow-none outline-none">
                                        <i class='bx bxs-edit text-lg'></i>
                                    </button>
                                    <button onclick="openDeleteOrderDialog(<?php echo $item['orderItemId']; ?>)"
                                        class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none data-[shape=circular]:rounded-full text-sm min-w-[34px] min-h-[34px] rounded-md bg-transparent border-transparent text-primary hover:bg-amber-200/50 hover:border-amber-200/10 shadow-none hover:shadow-none outline-none">
                                        <i class='bx bx-trash text-lg text-red-500'></i>
                                    </button>
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
            </div>
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mb-6 md:col-span-1 col-span-2">
                <h2 class="font-bold text-slate-700 mb-4">Order Summary</h2>
                <div class="flex justify-between text-sm text-slate-500 mb-2">
                    <span>Subtotal</span>
                    <span>RM <?php echo number_format($orderRow['totalPrice'], 2); ?></span>
                </div>
                <hr class="my-3 border-slate-100">
                <div class="flex justify-between font-bold text-slate-800 text-lg">
                    <span>Total</span>
                    <span class="text-green-600">RM <?php echo number_format($orderRow['totalPrice'], 2); ?></span>
                </div>
                <div class="mt-10">
                    <button onclick="openFoodDialog(<?php echo htmlspecialchars(json_encode($food)); ?>)"
                        class="inline-flex gap-2 items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                        data-shape="default" data-width="full">
                        <i class='bx bxs-lock-alt'></i>
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php include 'delete-order-dialog.php'; ?>
</div>