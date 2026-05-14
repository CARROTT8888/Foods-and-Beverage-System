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
    <input type="hidden" name="paymentMethod" value="Online Payment">
    <input type="hidden" name="savePayment" value="1">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mb-6 md:col-span-2 col-span-2">
        <h2 class="font-bold text-slate-700 mb-4">Online Payment</h2>
        <div class="mt-6">
            <label for="deliveryAddress" class="block text-sm font-medium text-foreground mb-1 text-start">
                Credit Card Number <span class="text-destructive">*</span></label>
            <?php if ($paymentStatus === 'Success'): ?>
                <input disabled type="text" required placeholder="0000 0000 0000 0000"
                    class="w-full cursor-not-allowed bg-secondary border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
            <?php else: ?>
                <input type="text" required placeholder="0000 0000 0000 0000"
                    class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
            <?php endif; ?>
        </div>

        <div class="mt-6 flex justify-between gap-2">
            <div class="w-full">
                <label for="deliveryPostalCode" class="block text-sm font-medium text-foreground mb-1 text-start">
                    3 Digits <span class="text-destructive">*</span>
                </label>
                <?php if ($paymentStatus === 'Success'): ?>
                    <input disabled type="text" required placeholder="000"
                        class="w-full cursor-not-allowed bg-secondary border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                <?php else: ?>
                    <input type="text" required placeholder="000"
                        class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                <?php endif; ?>
            </div>
            <div class="w-full">
                <label for="deliveryDistrict" class="block text-sm font-medium text-foreground mb-1 text-start">
                    Validation Date <span class="text-destructive">*</span>
                </label>
                <?php if ($paymentStatus === 'Success'): ?>
                    <input disabled type="date" required placeholder="mm/dd/yyyy"
                        class="w-full cursor-not-allowed bg-secondary border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                <?php else: ?>
                    <input type="date" required
                        class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                <?php endif; ?>
            </div>
        </div>
        <?php if ($paymentStatus === 'Success'): ?>
            <button type="button"
                class="inline-flex gap-2 mt-4 mb-2 items-center cursor-not-allowed justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-amber-600 text-foreground border-secondary w-full"
                data-shape="default" data-width="full">
                <i class='bx bxs-lock-alt'></i>
                You have Paid Successfully
            </button>
        <?php else: ?>
            <button type="submit"
                class="inline-flex gap-2 mt-4 mb-2 items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                data-shape="default" data-width="full">
                <i class='bx bxs-lock-alt'></i>
                Save as The Payment Method
            </button>
        <?php endif; ?>
    </div>
</form>