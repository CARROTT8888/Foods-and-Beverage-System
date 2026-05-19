<?php
$filter = "";
$params = [];
$types = "";
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $filter = " AND (food_category.name LIKE ?)";
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
    $filter .= " AND food_category.status IN (" . implode(',', $escapedStatuses) . ")";
}

$selectedBranches = $_GET['branch'] ?? [];
if (!is_array($selectedBranches)) {
    $selectedBranches = [$selectedBranches];
}

if (!empty($selectedBranches)) {
    $escapedBranches = array_map(function ($id) use ($conn) {
        return (int) $id;
    }, $selectedBranches);
    $filter .= " AND food_category.branchId IN (" . implode(',', $escapedBranches) . ")";
}

// final query with filter
/*$branchQuery = "SELECT * FROM branch WHERE 1" . $filter . " ORDER BY branchId DESC";
$branchResult = $conn->query($branchQuery);*/

$categoryQuery = "SELECT * FROM food_category WHERE 1" . $filter . " ORDER BY categoryId DESC";
$stmt = $conn->prepare($categoryQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$categoryResult = $stmt->get_result();
?>

<div class="w-full px-4 sm:px-6 lg:px-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4.5">
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
                        <i class='bx bx-show text-lg text-green-600'></i>
                    </div>
                    <h1 class="text-lg font-bold text-green-900">Visible</h1>
                </div>
                <p class="text-3xl text-green-950 mt-3 font-extrabold">
                    -
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
                        <i class='bx bxs-low-vision text-lg text-red-600'></i>
                    </div>
                    <h1 class="text-lg font-bold text-red-900">Invisible</h1>
                </div>
                <p class="text-3xl text-green-red mt-3 font-extrabold">
                    -
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
                        <i class='bx bxs-x-circle text-lg text-slate-600'></i>
                    </div>
                    <h1 class="text-lg font-bold text-slate-900">Deprecated</h1>
                </div>
                <p class="text-3xl text-slate-950 mt-3 font-extrabold">
                    -
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
            <?php include 'search-or-filter-dialog.php'; ?>
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
                        <?php if ($status === 'Visible'): ?>
                            <div
                                class="text-green-500  border border-green-500 bg-green-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php elseif ($status === 'Invisible'): ?>
                            <div
                                class="text-red-500 border border-red-500 bg-red-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php elseif ($status === 'Deprecated'): ?>
                            <div
                                class="text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                                <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                            </div>
                        <?php endif ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <span class="text-secondaryForeground">Showing
        <?php echo $categoryResult->num_rows; ?> of
        -
        categories
    </span>
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