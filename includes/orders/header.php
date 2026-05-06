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
        <div class="dropdown w-full flex justify-end">
            <div data-toggle="dropdown" aria-expanded="false"
                class="flex w-auto gap-2 justify-center items-center cursor-pointer border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full focus:shadow-none text-sm rounded-md py-2 px-4 bg-transparent border-transparent text-slate-800 hover:bg-accent hover:text-accentForeground shadow-none hover:shadow-none">
                <i class='bx bxs-edit text-4xl'></i>
            </div>
            <div data-role="menu"
                class="hidden mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-30 w-[180px] cursor-default">
                <div class="p-1 text-mutedForeground">
                    <p class="font-sans antialiased text-sm text-current font-semibold">Edit</p>
                </div>
                <button onclick="window.location.href='menu.php'"
                    class="block p-1 w-full text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                    <i class='bx bxs-food-menu mr-2 text-lg'></i>
                    Update Orders
                </button>
                <button onclick="window.location.href='order-method.php'"
                    class="block py-2 px-1 w-full text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                    <div class='mr-1.5'>
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.25 6C3.25 5.58579 3.58579 5.25 4 5.25H20C20.4142 5.25 20.75 5.58579 20.75 6C20.75 6.41421 20.4142 6.75 20 6.75H4C3.58579 6.75 3.25 6.41421 3.25 6Z"
                                fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.25 12C3.25 11.5858 3.58579 11.25 4 11.25H20C20.4142 11.25 20.75 11.5858 20.75 12C20.75 12.4142 20.4142 12.75 20 12.75H4C3.58579 12.75 3.25 12.4142 3.25 12Z"
                                fill="currentColor" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.25 18C3.25 17.5858 3.58579 17.25 4 17.25H20C20.4142 17.25 20.75 17.5858 20.75 18C20.75 18.4142 20.4142 18.75 20 18.75H4C3.58579 18.75 3.25 18.4142 3.25 18Z"
                                fill="currentColor" />

                            <!-- Drag handle dots on the right -->
                            <circle cx="18.5" cy="6" r="1" fill="currentColor" />
                            <circle cx="18.5" cy="12" r="1" fill="currentColor" />
                            <circle cx="18.5" cy="18" r="1" fill="currentColor" />
                        </svg>
                    </div>
                    Update Method
                </button>
                <button onclick="window.location.href='order-location.php?updateLocation=1'"
                    class="block w-full p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                    <i class='bx bxs-edit-location mr-2 text-lg'></i>
                    Update Location
                </button>
            </div>
        </div>
    </div>
</div>