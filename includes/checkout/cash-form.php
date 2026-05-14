<?php
$order = null;

// 1. Load existing data for the form
if (!empty($_GET['orderId'])) {
    $orderId = intval($_GET['orderId']);
    $stmtOrder = $conn->prepare("SELECT * FROM `order` WHERE orderId = ?");
    $stmtOrder->bind_param("i", $orderId);
    $stmtOrder->execute();
    $orderResult = $stmtOrder->get_result();
    $order = $orderResult->fetch_assoc();
    $stmtOrder->close();
}
?>

<form method="POST">
    <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
    <input type="hidden" name="paymentMethod" value="Cash">
    <input type="hidden" name="savePayment" value="1">
    <div
        class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mb-6 md:col-span-2 col-span-2 w-full text-center">
        <h2 class="font-bold text-slate-700 mb-4">Your Order Number:</h2>
        <h1 class="text-7xl font-extrabold">
            <?php echo htmlspecialchars($orderId); ?>
        </h1>
        <hr class="my-3 border-secondaryForeground">
        <div class="flex justify-between font-bold text-slate-800 text-lg">
            <span>Total</span>
            <span class="text-green-600">RM
                <?php echo number_format($orderRow['totalPrice'], 2); ?>
            </span>
        </div>
        <div class="flex justify-between font-bold text-slate-800 text-lg">
            <span>Status</span>
            <span class="font-normal">Pending</span>
        </div>
        <?php if ($paymentMethod === 'Cash' && $paymentStatus === 'Pending'): ?>
            <button type="button"
                class="inline-flex gap-2 mt-4 mb-2 items-center cursor-not-allowed justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-amber-600 text-foreground border-secondary w-full"
                data-shape="default" data-width="full">
                <i class='bx bxs-lock-alt'></i>
                Selected the Payment Method Successfully
            </button>
        <?php else: ?>
            <button type="button" onclick="openCashSubmissionDialog()"
                class="inline-flex gap-2 mt-4 mb-2 items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                data-shape="default" data-width="full">
                <i class='bx bxs-lock-alt'></i>
                Save as The Payment Method
            </button>
        <?php endif; ?>
        <span class="text-secondaryForeground italic ">Please pay at counter and show your order number.</span>
    </div>
    <?php include 'cash-submission-dialog.php'; ?>
</form>