<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="cashSubmissionDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
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
        <div class="text-slate-600 text-start">Are you sure you want to select the payment method? You cannot change another method once you selected.</div>
        <div class="mt-6">
            <div class="flex justify-end gap-2">
                <button onclick="closeCashSubmissionDialog()" type="button"
                    class="rounded-md border bg-secondary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-accent hover:text-accentForeground">Cancel</button>
                <button type="submit"
                    class="inline-flex gap-2 items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground">Yes</button>
            </div>
            <span class="text-sm mt-4 w-full flex justify-end text-secondaryForeground">Click 'X' or
                tab 'ESC' key to close the dialog.</span>
        </div>
    </div>
</div>