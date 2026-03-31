<div class="flex gap-2 ml-7">
    <?php
    // filtering status
    $selectedStatuses = $_GET['status'] ?? [];
    if (!is_array($selectedStatuses)) {
        $selectedStatuses = [$selectedStatuses];
    }
    ?>
    <div class="flex items-center flex-wrap gap-4 ">
        <!-- Trigger Button -->
        <button id="dropdownBtn" type="button" data-toggle="modal" data-target="#searchORFilterDialog"
            class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
            <i class="bx bx-search text-lg"></i><span class="lg:flex hidden">Search or Filter</span>
        </button>
        <div class="flex items-center mb-10 gap-2 justify-self-start">
            <?php foreach ($selectedStatuses as $status):
                $newStatuses = array_filter($selectedStatuses, fn($s) => $s !== $status);
                $query = $_GET;
                $query['status'] = $newStatuses;
                if (empty($newStatuses)) {
                    unset($query['status']);
                }
                $url = '?' . http_build_query($query);
                ?>
                <a href="<?= $url ?>" class="">
                    <?php if ($status === 'Opening'): ?>
                        <div
                            class="text-green-500  border border-green-500 bg-green-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                            <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i></div>
                    <?php elseif ($status === 'Closed'): ?>
                        <div
                            class="text-red-500 border border-red-500 bg-red-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                            <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i></div>
                    <?php elseif ($status === 'Setup'): ?>
                        <div
                            class="text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                            <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i></div>
                    <?php elseif ($status === 'Deprecated'): ?>
                        <div
                            class="text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                            <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i></div>
                    <?php endif ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="searchORFilterDialog" aria-hidden="true">
    <form action="" method="GET">
        <!-- Dropdown Menu -->
        <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
            <div class="flex items-center justify-between mb-2">
                <small class="font-sans antialiased text-sm mx-2 font-semibold text-slate-600">Search or
                    Filter</small>
                <a href="order-location.php">
                    <button
                        class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-1 px-2 bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none"
                        data-shape="default" data-width="default">Clear All</button>
                </a>
            </div>
            <div class="relative w-full">
                <input placeholder="Search a name, state, or address" name="name"
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
                            <?php
                            $filter = "";
                            // filtering name
                            if (!empty($_GET['name'])) {
                                $name = $conn->real_escape_string($_GET['name']);
                                $filter .= " AND branch.name LIKE '%$name%'";
                            }
                            // filtering status
                            $selectedStatuses = $_GET['status'] ?? [];
                            if (!is_array($selectedStatuses)) {
                                $selectedStatuses = [$selectedStatuses];
                            }
                            if (!empty($selectedStatuses)) {
                                $escapedStatuses = array_map(function ($status) use ($conn) {
                                    return "'" . $conn->real_escape_string($status) . "'";
                                }, $selectedStatuses);
                                $filter .= " AND branch.status IN (" . implode(',', $escapedStatuses) . ")";
                            }
                            // final query with filter
                            $branchQuery = "SELECT * FROM branch WHERE 1" . $filter . " ORDER BY branchId DESC";
                            $branchResult = $conn->query($branchQuery);
                            ?>
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

                <button
                    class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground w-full"
                    type="submit">Apply</button>
            </div>
        </div>
    </form>
</div>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script>
    // Dropdown logic
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    let popperInstance = null;

    function createPopper() {
        popperInstance = Popper.createPopper(dropdownBtn, dropdownMenu, {
            placement: 'bottom-start',
            modifiers: [{
                name: 'offset',
                options: {
                    offset: [0, 8]
                }
            },],
        });
    }
    dropdownBtn.addEventListener('click', () => {
        const isHidden = dropdownMenu.classList.contains('hidden');
        if (isHidden) {
            dropdownMenu.classList.remove('hidden');
            dropdownMenu.classList.add('block');
            createPopper();
        } else {
            dropdownMenu.classList.remove('block');
            dropdownMenu.classList.add('hidden');
            if (popperInstance) {
                popperInstance.destroy();
                popperInstance = null;
            }
        }
    });
    window.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
            dropdownMenu.classList.remove('block');
            if (popperInstance) {
                popperInstance.destroy();
                popperInstance = null;
            }
        }
    });
</script>
<script>
    function toggleCollapse(id) {
        const container = document.getElementById('collapsibleContainer');
        const allContents = container.querySelectorAll('[id^="collapse"]');
        const allIcons = container.querySelectorAll('[id^="icon"]');
        const content = document.getElementById(id);
        const icon = document.getElementById(`icon${id.replace('collapse', '')}`);
        // Check if the clicked section is already expanded
        const isCurrentlyVisible = !content.classList.contains('hidden');
        // Collapse all sections first
        allContents.forEach((c) => c.classList.add('hidden'));
        allIcons.forEach((i) => i.classList.remove('rotate-180'));
        // If the clicked section was not already expanded, expand it
        if (!isCurrentlyVisible) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        }
        event.preventDefault();
    }
</script>
<span class="absolute mt-2 top-12 ml-10 text-secondaryForeground">Showing
    <?php echo $branchResult->num_rows; ?> of
    <?php echo $totalBranchNumber; ?>
    branches
</span>