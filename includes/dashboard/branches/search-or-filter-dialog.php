<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="searchOrFilterDialog" onclick="event.target === this && null">
    <form action="" method="GET">
        <!-- Dropdown Menu -->
        <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
            <div class="flex items-center justify-between mb-2">
                <small class="font-sans antialiased text-sm mx-2 font-semibold text-slate-600">Search or
                    Filter</small>
                <div class="flex items-center">
                    <a href="order-location.php">
                        <button
                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-1 px-2 bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none"
                            data-shape="default" data-width="default">Clear All</button>
                    </a>
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
            </div>
            <div class="relative w-full">

                <input placeholder="Search a name, state, or address" name="search" value=""
                    class="w-full aria-disabled:cursor-not-allowed outline-none focus:outline-none placeholder:text-slate-black bg-transparent ring-transparent border border-slate-200 transition-all duration-300 ease-in disabled:opacity-50 disabled:pointer-events-none data-[error=true]:border-error data-[success=true]:border-success select-none data-[shape=pill]:rounded-full text-sm rounded-md py-2 px-2.5 ring shadow-sm data-[icon-placement=start]:ps-9 data-[icon-placement=end]:pe-9 hover:border-primary-800 hover:ring-primary-800/10 focus:border-primary peer"
                    data-error="false" data-success="false" data-shape="default" data-icon-placement="end" type="text"
                    data-tabindex="" />
                <span
                    class="pointer-events-none absolute top-7 -translate-y-1/2 text-slate-600/70 peer-hover:text-black peer-focus:text-black dark:peer-hover:text-white dark:peer-focus:text-white transition-all duration-300 ease-in overflow-hidden w-5 h-5 data-[placement=start]:left-2.5 data-[placement=end]:right-2.5"
                    data-error="false" data-success="false" data-placement="end">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" stroke-width="1.5" fill="none"
                        xmlns="http://www.w3.org/2000/svg" color="currentColor" class="w-full h-full">
                        <path d="M17 17L21 21" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path
                            d="M3 11C3 15.4183 6.58172 19 11 19C13.213 19 15.2161 18.1015 16.6644 16.6493C18.1077 15.2022 19 13.2053 19 11C19 6.58172 15.4183 3 11 3C6.58172 3 3 6.58172 3 11Z"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
            <div class="p-3" id="collapsibleContainer">
                <!-- Collapse Item -->
                <div>
                    <button class="w-full flex justify-between items-center py-2 text-slate-600 text-sm font-sans"
                        onclick="toggleCollapse('collapseMarketing')"> Status <span
                            class="transform transition-transform duration-300" id="iconMarketing">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </button>
                    <div id="collapseMarketing"
                        class="hidden transition-all duration-300 ease-in-out text-slate-500 text-sm">
                        <!-- Checkbox List -->
                        <div class="flex flex-col gap-3 my-2">

                            <!-- Checkbox Item -->
                            <div class="inline-flex items-center justify-betweena">
                                <label class="flex items-center cursor-pointer relative" for="Opening">
                                    <input type="checkbox" id="Opening" name="status[]" value="Opening"
                                        <?= in_array('Opening', $selectedStatuses) ? 'checked' : '' ?>
                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-secondary" />
                                    <span
                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-4.5 left-4.5 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </label>
                                <label class="cursor-pointer ml-2 font-sans antialiased text-sm text-green-600 flex-1"
                                    for="Opening"> Opening </label>
                                <span
                                    class="font-sans antialiased text-sm text-green-600 ml-6"><?php echo $totalStatusOpening; ?></span>
                            </div>
                            <div class="inline-flex items-center justify-between">
                                <label class="flex items-center cursor-pointer relative" for="Closed">
                                    <input type="checkbox" id="Closed" name="status[]" value="Closed"
                                        <?= in_array('Closed', $selectedStatuses) ? 'checked' : '' ?>
                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-secondary" />
                                    <span
                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-4.5 left-4.5 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </label>
                                <label class="cursor-pointer ml-2 font-sans antialiased text-sm text-red-600 flex-1"
                                    for="Closed"> Closed </label>
                                <span
                                    class="font-sans antialiased text-sm text-red-600 ml-6"><?php echo $totalStatusClosed; ?></span>
                            </div>

                            <div class="inline-flex items-center justify-between">
                                <label class="flex items-center cursor-pointer relative" for="Setup">
                                    <input type="checkbox" id="Setup" name="status[]" value="Setup" <?= in_array('Setup', $selectedStatuses) ? 'checked' : '' ?>
                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-secondary" />
                                    <span
                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-4.5 left-4.5 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </label>
                                <label class="cursor-pointer ml-2 font-sans antialiased text-sm text-amber-600 flex-1"
                                    for="Setup"> Setup </label>
                                <span
                                    class="font-sans antialiased text-sm text-amber-600 ml-6"><?php echo $totalStatusSetup; ?></span>
                            </div>

                            <div class="inline-flex items-center justify-between">
                                <label class="flex items-center cursor-pointer relative" for="Deprecated">
                                    <input type="checkbox" id="Deprecated" name="status[]" value="Deprecated"
                                        <?= in_array('Deprecated', $selectedStatuses) ? 'checked' : '' ?>
                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-secondary" />
                                    <span
                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-4.5 left-4.5 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </label>
                                <label class="cursor-pointer ml-2 font-sans antialiased text-sm text-slate-600 flex-1"
                                    for="Deprecated"> Deprecated </label>
                                <span
                                    class="font-sans antialiased text-sm text-slate-600 ml-6"><?php echo $totalStatusDeprecated; ?></span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Collapse Item -->
                <div>
                    <button class="w-full flex justify-between items-center py-2 text-slate-600 text-sm font-sans"
                        onclick="toggleCollapse('collapseMarketing2')"> State <span
                            class="transform transition-transform duration-300" id="iconMarketing2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </button>
                    <div id="collapseMarketing2"
                        class="hidden transition-all duration-300 ease-in-out text-slate-500 text-sm">
                        <!-- Checkbox List -->
                        <div class="flex flex-col gap-3 my-2">

                            <!-- Checkbox Item -->
                            <div class="inline-flex items-center justify-between">
                                <label class="flex items-center cursor-pointer relative" for="Melaka">
                                    <input type="checkbox" id="Melaka" name="state[]" value="Melaka"
                                        <?= in_array('Melaka', $selectedStates) ? 'checked' : '' ?>
                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-secondary" />
                                    <span
                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-4.5 left-4.5 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </label>
                                <label class="cursor-pointer ml-2 font-sans antialiased text-sm text-green-600 flex-1"
                                    for="Melaka"> Melaka</label>
                                <span class="font-sans antialiased text-sm text-green-600 ml-6">-</span>
                            </div>
                            <div class="inline-flex items-center justify-between">
                                <label class="flex items-center cursor-pointer relative" for="Melaka">
                                    <input type="checkbox" id="Melaka" name="state[]" value="Melaka"
                                        <?= in_array('Melaka', $selectedStates) ? 'checked' : '' ?>
                                        class="peer h-5 w-5 cursor-pointer transition-all appearance-none rounded shadow hover:shadow-md border border-slate-300 checked:bg-primary checked:border-secondary" />
                                    <span
                                        class="absolute text-white opacity-0 peer-checked:opacity-100 top-4.5 left-4.5 transform -translate-x-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                            fill="currentColor" stroke="currentColor" stroke-width="1">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </label>
                                <label class="cursor-pointer ml-2 font-sans antialiased text-sm text-green-600 flex-1"
                                    for="Melaka"> Melaka</label>
                                <span class="font-sans antialiased text-sm text-green-600 ml-6">-</span>
                            </div>
                        </div>
                    </div>

                </div>

                <button
                    class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground w-full"
                    type="submit">Apply</button>
                <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or
                    tab 'ESC' key to close the dialog.</span>
            </div>
        </div>
    </form>
</div>