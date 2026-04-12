<?php
$filter = "";
$params = [];
$types = "";
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $filter = " AND (seat_table.tableName LIKE ?)";
    $searchValue = "%" . $search . "%";
    $params = [$searchValue];
    $types = "s";
}
;
// filtering status
$selectedStatuses = $_GET['status'] ?? [];
if (!is_array($selectedStatuses)) {
    $selectedStatuses = [$selectedStatuses];
}
if (!empty($selectedStatuses)) {
    $escapedStatuses = array_map(function ($status) use ($conn) {
        return "'" . $conn->real_escape_string($status) . "'";
    }, $selectedStatuses);
    $filter .= " AND seat_table.status IN (" . implode(',', $escapedStatuses) . ")";
}

// final query with filter
/*$branchQuery = "SELECT * FROM branch WHERE 1" . $filter . " ORDER BY branchId DESC";
$branchResult = $conn->query($branchQuery);*/

$seatQuery = "SELECT * FROM seat_table WHERE 1" . $filter . " ORDER BY tableId DESC";
$stmt = $conn->prepare($seatQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$seatResult = $stmt->get_result();
?>

<div class="w-full px-4 sm:px-6 lg:px-10">
    <script>
        function toggleCollapse(event, id) {
            event.preventDefault();

            const container = document.getElementById('collapsibleContainer');
            const allContents = container.querySelectorAll('[id^="collapse"]');
            const allIcons = container.querySelectorAll('[id^="icon"]');

            const content = document.getElementById(id);
            const icon = document.getElementById(`icon${id.replace('collapse', '')}`);

            const isCurrentlyVisible = !content.classList.contains('hidden');

            allContents.forEach((c) => c.classList.add('hidden'));
            allIcons.forEach((i) => i.classList.remove('rotate-180'));

            if (!isCurrentlyVisible) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            }
        };
    </script>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4.5">
        <!-- Card 1 -->
        <div
            class="flex items-center p-2 border border-green-500 bg-green-500/10 hover:border-green/20 transition-colors rounded-xl ">
            <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
            <div class="ml-4">
                <div class="flex items-center gap-2.5">
                    <div class="relative flex size-4 items-center justify-center">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5 text-green-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-green-900">Available</h1>
                </div>
                <p class="text-3xl text-green-950 mt-3 font-extrabold">
                    <?php echo htmlspecialchars($totalStatusAvailable) ?>
                </p>
            </div>
        </div>
        <!-- Card 2 -->
        <div
            class="flex items-center p-2 border border-red-500 bg-red-500/10 hover:border-red/20 transition-colors rounded-xl ">
            <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
            <div class="ml-4">
                <div class="flex items-center gap-2.5">
                    <div class="relative flex size-4 items-center justify-center">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5 text-red-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-red-900">Occupied</h1>
                </div>
                <p class="text-3xl text-green-red mt-3 font-extrabold">
                    <?php echo htmlspecialchars($totalStatusOccupied) ?>
                </p>
            </div>
        </div>
        <!-- Card 3 -->
        <div
            class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-amber/20 transition-colors rounded-xl ">
            <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
            <div class="ml-4">
                <div class="flex items-center gap-2.5">
                    <div class="relative flex size-4 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M3 9h18M3 15h18M3 21h18" />
                            <circle cx="8" cy="7" r="1" fill="currentColor" />
                            <circle cx="12" cy="13" r="1" fill="currentColor" />
                            <circle cx="16" cy="7" r="1" fill="currentColor" />
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-amber-900">Dirty</h1>
                </div>
                <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                    <?php echo htmlspecialchars($totalStatusDirty) ?>
                </p>
            </div>
        </div>
        <!-- Card 4 -->
        <div
            class="flex items-center p-2 border border-orange-500 bg-orange-500/10 hover:border-orange/20 transition-colors rounded-xl ">
            <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
            <div class="ml-4">
                <div class="flex items-center gap-2.5">
                    <div class="relative flex size-4 items-center justify-center">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5 text-orange-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25ZM12.5675 17.5008C12.8446 17.1929 12.8196 16.7187 12.5117 16.4416C12.2038 16.1645 11.7296 16.1894 11.4525 16.4973L11.4425 16.5084C11.1654 16.8163 11.1904 17.2905 11.4983 17.5676C11.8062 17.8447 12.2804 17.8197 12.5575 17.5119L12.5675 17.5008Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-orange-900">Reserved</h1>
                </div>
                <p class="text-3xl text-orange-950 mt-3 font-extrabold">
                    <?php echo htmlspecialchars($totalStatusReserved) ?>
                </p>
            </div>
        </div>
        <!-- Card 5 -->
        <div
            class="flex items-center p-2 border border-slate-500 bg-slate-500/10 hover:border-green/20 transition-colors rounded-xl ">
            <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
            <div class="ml-4">
                <div class="flex items-center gap-2.5">
                    <div class="relative flex size-4 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                        </svg>
                    </div>
                    <h1 class="text-lg font-bold text-slate-900">Blocked</h1>
                </div>
                <p class="text-3xl text-slate-950 mt-3 font-extrabold">
                    <?php echo htmlspecialchars($totalStatusBlocked) ?>
                </p>
            </div>
        </div>
    </div>
    <?php
    // filtering status
    $selectedStatuses = $_GET['status'] ?? [];
    if (!is_array($selectedStatuses)) {
        $selectedStatuses = [$selectedStatuses];
    }
    ?>
    <div class="flex items-center justify-between flex-wrap w-full">
        <div class="flex gap-2 relative top-5">
            <!-- Trigger Button -->
            <button id="dropdownBtn" type="button" onclick="openDialog()"
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
                        <?php if ($status === 'Available'): ?>
                            <div
                                class="text-green-500  border border-green-500 bg-green-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php elseif ($status === 'Occupied'): ?>
                            <div
                                class="text-red-500 border border-red-500 bg-red-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php elseif ($status === 'Reserved'): ?>
                            <div
                                class="text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php elseif ($status === 'Dirty'): ?>
                            <div
                                class="text-orange-500 border border-orange-500 bg-amber-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php elseif ($status === 'Blocked'): ?>
                            <div
                                class="text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php endif ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="button" data-toggle="modal" onclick="openTableDialog()"
            class="inline-flex gap-2 items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary text-foreground hover:bg-amber-300 hover:text-secondaryForeground">
            <i class='bx bx-plus-circle text-xl'></i> <span class="lg:flex hidden font-bold">Create</span>
        </button>
        <?php include 'create-table-dialog.php'; ?>
    </div>
</div>
<?php include 'search-or-filter-dialog.php'; ?>
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
<span class="text-secondaryForeground relative left-5 lg:left-10">Showing
    <?php echo $seatResult->num_rows; ?> of
    <?php echo $totalSeatNumber; ?>
    tables
</span>