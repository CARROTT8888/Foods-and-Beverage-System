<?php
$filter = "";
$params = [];
$types = "";
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $filter = " AND (branch.name LIKE ? OR branch.address LIKE ? OR branch.state LIKE ?)";
    $searchValue = "%" . $search . "%";
    $params = [$searchValue, $searchValue, $searchValue];
    $types = "sss";
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
    $filter .= " AND branch.status IN (" . implode(',', $escapedStatuses) . ")";
}
// filtering state
$selectedStates = $_GET['state'] ?? [];
if (!is_array($selectedStates)) {
    $selectedStates = [$selectedStates];
}
if (!empty($selectedStates)) {
    $escapedStates = array_map(function ($state) use ($conn) {
        return "'" . $conn->real_escape_string($state) . "'";
    }, $selectedStates);
    $filter .= " AND branch.state IN (" . implode(',', $escapedStates) . ")";
}
// final query with filter
/*$branchQuery = "SELECT * FROM branch WHERE 1" . $filter . " ORDER BY branchId DESC";
$branchResult = $conn->query($branchQuery);*/

$branchQuery = "SELECT * FROM branch WHERE 1" . $filter . " ORDER BY branchId DESC";
$stmt = $conn->prepare($branchQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$branchResult = $stmt->get_result();
?>

<div class="flex gap-2 ml-7">
    <?php
    // filtering status
    $selectedStatuses = $_GET['status'] ?? [];
    if (!is_array($selectedStatuses)) {
        $selectedStatuses = [$selectedStatuses];
    };
    // filtering state
    $selectedStates = $_GET['state'] ?? [];
    if (!is_array($selectedStates)) {
        $selectedStates = [$selectedStates];
    };
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
                            <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                        </div>
                    <?php elseif ($status === 'Closed'): ?>
                        <div
                            class="text-red-500 border border-red-500 bg-red-100 rounded-full text-sm w-auto p-1 px-2 items-center">
                            <?php echo htmlspecialchars($status); ?> <i class='bx bx-x-circle text-sm'></i>
                        </div>
                    <?php elseif ($status === 'Setup'): ?>
                        <div
                            class="text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-sm w-auto p-1 px-2 items-center">
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
<span class="text-secondaryForeground relative left-5 lg:left-10 bottom-2">Showing
    <?php echo $branchResult->num_rows; ?> of
    <?php echo $totalBranchNumber; ?>
    branches
</span>