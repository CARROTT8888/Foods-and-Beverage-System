<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateOrderDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-200 w-full max-w-xl max-h-[90vh] overflow-hidden">
        <form method="POST" action="/web/includes/orders/update-order-item.php" class="">
            <input type="hidden" name="orderItemId" id="updateOrderItemId">
            <input type="hidden" name="quantity" id="updateQuantity">
            <div class="p-4 pb-2 flex justify-between items-center">
                <h1 class="text-lg text-slate-800 font-semibold">Update Order</h1>
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

            <div class="overflow-y-auto max-h-[80vh]">
                <div class="p-4">
                    <div id="updateFoodImage" class="w-full h-full object-cover hiddena "></div>
                    <div class="flex items-center justify-between mt-2">
                        <h6 class="text-slate-800 text-2xl font-bold" id="updateFoodName"></h6>
                        <span class="pr-1 font-medium text-green-600" id="updateBasePrice"></span>
                    </div>

                    <p class="text-slate-500 text-lg mb-1" id="updateFoodDescription"></p>
                </div>

                <!-- Options -->
                <div id="updateOrderOptions" class="mt-2 p-4"></div>

                <!-- Quantity -->
                <div class="flex items-center gap-4 mt-2 p-4">
                    <span class="text-sm font-medium text-slate-600">Quantity</span>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="changeQuantity(-1)"
                            class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center hover:bg-slate-100">
                            <i class='bx bx-minus text-sm'></i>
                        </button>
                        <span id="updateQuantityDisplay" class="w-6 text-center font-semibold">1</span>
                        <button type="button" onclick="changeQuantity(1)"
                            class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center hover:bg-slate-100">
                            <i class='bx bx-plus text-sm'></i>
                        </button>
                    </div>
                </div>

                <!-- Purchased Price -->
                <div class="flex items-center justify-between px-4 py-2 border-t border-slate-100">
                    <span class="text-sm font-medium text-slate-600">Total</span>
                    <span class="font-bold text-green-600">RM <span id="updateTotalPriceDisplay">0.00</span></span>
                </div>

                <div class="flex flex-col justify-end gap-2 mt-6 p-4">
                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="closeUpdateOrderDialog()"
                            class="rounded-md border bg-secondary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-accent hover:text-accentForeground">
                            Cancel
                        </button>

                        <button type="submit"
                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground">
                            Save Changes
                        </button>
                    </div>
                    <span class="text-sm mt-4 w-full flex justify-end text-secondaryForeground">Click 'X' or
                        tab 'ESC' key to close the dialog.</span>
                </div>
            </div>
        </form>
    </div>
</div>