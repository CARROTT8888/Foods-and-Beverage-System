<div class="items-center max-w-7xl w-auto my-5">
    <div role="alert"
        class="relative flex w-full justify-between items-start rounded-md border border-transparent bg-slate-800/10 p-2 text-slate-800">
        <span class="grid shrink-0 place-items-center p-1">
            <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5">
                <path d="M12 7L12 13" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M12 17.01L12.01 16.9989" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                </path>
                <path
                    d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </span>
        <div class="m-1.5 mt-0.5 w-full font-sans text-base leading-none">
            <p class="font-sans text-base antialiased">Current Branch Selected: <span
                    class="font-bold"><?php echo htmlspecialchars($branch['name']); ?></span></p>
            <span class=mt-2 list-inside list-disc space-y-1">
                <div class="font-sans text-sm antialiased">Address:
                    <?php echo htmlspecialchars($branch['address']); ?>
                </div>
            </span>
        </div>
    </div>
</div>