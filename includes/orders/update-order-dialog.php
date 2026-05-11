<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateOrderDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
        <form method="POST" action="/web/includes/orders/update-order-item.php" class="p-6">

            <input type="hidden" name="orderItemId" id="updateOrderItemId">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-lg text-slate-800 font-semibold">Update Order</h1>

                <button type="button" onclick="closeUpdateOrderDialog()" class="text-red-500 text-2xl font-bold">
                    &times;
                </button>
            </div>

            <!-- Options -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Options</label>
                <div id="updateOrderOptions" class="space-y-2"></div>
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Quantity</label>
                <input type="number" name="quantity" id="updateQuantity"
                    class="w-full border rounded-lg px-3 py-2 outline-none focus:ring focus:ring-amber-200" min="1"
                    required>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeUpdateOrderDialog()"
                    class="px-4 py-2 rounded-lg border text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-amber-400">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>