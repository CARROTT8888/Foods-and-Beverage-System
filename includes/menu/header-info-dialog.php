<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="seatTableInfoDialog">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">

        <div class="flex justify-between mb-4">
            <span class="flex shrink-0 place-items-center p-1 gap-2">
                <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5">
                    <path d="M12 7L12 13" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12 17.01L12.01 16.9989" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path
                        d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <h1 class="text-lg text-slate-800 font-semibold">Seat Table Info</h1>
            </span>
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
        <div class="text-slate-600">
            <p class="font-sans text-base antialiased">There are 5 statuses of table seats:</p>
            <ul class="ml-2 mt-2 list-inside list-disc space-y-1">
                <li class="list-item font-sans text-md antialiased">
                    <span class="text-green-600 font-semibold">Available:</span>
                    <span>The table seat is clean and ready to use.</span>
                </li>
                <li class="list-item font-sans text-md antialiased">
                    <span class="text-red-600 font-semibold">Occupied:</span>
                    <span>The table seat is used by customers and it's full.</span>
                </li>
                <li class="list-item font-sans text-md antialiased">
                    <span class="text-orange-600 font-semibold">Reserved:</span>
                    <span>The table seat is booked by other customers, means that another customers cannot choose the table again once it's reserved.</span>
                </li>
                <li class="list-item font-sans text-md antialiased">
                    <span class="text-amber-600 font-semibold">Dirty:</span>
                    <span>The table seat is available to use but it's dirty, cleaners need to clean it.</span>
                </li>
                <li class="list-item font-sans text-md antialiased">
                    <span class="text-slate-600 font-semibold">Blocked:</span>
                    <span>The table seat cannot be used current time.</span>
                </li>
            </ul>
        </div>
        <div class="mt-6">
            <div class="flex justify-end gap-2" onclick="closeDialog()">
                <button type="button"
                    class="rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">Yes,
                    I understood!</button>
            </div>
        </div>
    </div>
</div>