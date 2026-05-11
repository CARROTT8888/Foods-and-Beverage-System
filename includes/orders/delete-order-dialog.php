<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['removeItem'])) {
    $orderItemId = intval($_POST['orderItemId']);

    $stmtDelete = $conn->prepare("DELETE FROM order_item_option WHERE orderItemId = ?");
    $stmtDelete->bind_param("i", $orderItemId);
    $stmtDelete->execute();
    $stmtDelete->close();

    $stmtDelete2 = $conn->prepare("DELETE FROM order_item WHERE orderItemId = ? AND orderId = ?");
    $stmtDelete2->bind_param("ii", $orderItemId, $orderId);
    $stmtDelete2->execute();
    $stmtDelete2->close();

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

    echo "<script>window.location.href='/web/orders';</script>";
    exit();
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="deleteOrderDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
        <form method="POST">
            <div class="flex justify-between mb-4">
                <h1 class="text-lg text-slate-800 font-semibold">Removing the Order</h1>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none data-[shape=circular]:rounded-full text-sm min-w-[34px] min-h-[34px] rounded-md bg-transparent border-transparent text-red-500 hover:bg-red-200/10 hover:border-red-200/10 shadow-none hover:shadow-none outline-none">
                    <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5">
                        <path
                            d="M6.75827 17.2426L12.0009 12M17.2435 6.75736L12.0009 12M12.0009 12L6.75827 6.75736M12.0009 12L17.2435 17.2426"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="orderItemId" id="deleteOrderItemId">
            <input type="hidden" name="removeItem" value="1">
            <div class="text-slate-600 text-start">Are you sure you want to remove this order?</div>
            <div class="mt-6">
                <div class="flex justify-end gap-2">
                    <button onclick="closeDeleteOrderDialog()" type="button"
                        class="rounded-md border bg-secondary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-accent hover:text-accentForeground">Cancel</button>
                    <button type="submit" name="removeItem"
                        class="rounded-md border bg-destructive px-4 py-2 text-center text-sm font-medium border-red-500 text-red-50 hover:bg-red-400 hover:border-red-400">Yes</button>
                </div>
                <span class="text-sm mt-4 w-full flex justify-end text-secondaryForeground">Click 'X' or
                    tab 'ESC' key to close the dialog.</span>
            </div>
        </form>
    </div>
</div>