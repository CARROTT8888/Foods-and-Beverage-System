<?php
$branchId = $branch['branchId'];
$counttablesql = "SELECT COUNT(*) FROM seat_table WHERE branchId = ?";
$stmtcount = $conn->prepare($counttablesql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalTableNumber);
$stmtcount->fetch();
$stmtcount->close();
$countmenusql = "SELECT COUNT(*) FROM food WHERE branchId = ?";
$stmtcount = $conn->prepare($countmenusql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalMenuNumber);
$stmtcount->fetch();
$stmtcount->close();
$countordersql = "SELECT COUNT(*) FROM `order` WHERE branchId = ?";
$stmtcount = $conn->prepare($countordersql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalOrderNumber);
$stmtcount->fetch();
$stmtcount->close();
?>

<div class="max-w-7xl mx-auto mt-10a px-6 pb-20 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    <div class="md:sticky flex flex-col gap-4">
        <!-- Sidebar -->
        <button type="button" data-toggle="modal" data-target="#sidebarDrawerBranch"
            class="text-gray-500 hover:text-gray-600">
            <span class="lg:hidden flex font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </span>
        </button>
        <?php include 'drawer.php'; ?>
        <div class="image-hover rounded-2xl overflow-hidden aspect-[4/3] bg-stone-200 shadow-lg relative">
            <?php if ($branch['image']): ?>
                <img class="w-full h-full object-cover block hover:scale-[1.03] transition ease-in"
                    src="/Foods-and-Beverage-System/uploads/branches/<?php echo htmlspecialchars($branch['image']); ?>"
                    alt="<?php echo htmlspecialchars($branch['name']); ?>">
            <?php else: ?>
                <img class="w-full h-full object-cover block hover:scale-[1.03]"
                    src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.sopandai.com%2Fwp-content%2Fuploads%2F2023%2F01%2FMMU.png.webp&f=1&nofb=1&ipt=ed618de2de637fb9769308656bab69713f756476ef4ce6bd2ae83063b07b3f18"
                    alt="No image">
            <?php endif; ?>

            <?php if ($branch['visibleStatus'] === 'Invisible'): ?>
                <div
                    class="absolute inset-0 bg-stone-900/50 flex items-center justify-center gap-2 text-white text-sm font-medium tracking-wide">
                    <i class='bx bxs-low-vision'></i> Hidden from customers
                </div>
            <?php endif; ?>
        </div>

        <div class="flex justify-between">
            <div class="flex gap-2 flex-wrap">
                <button
                    class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                    <i class='bx bxs-edit text-lg'></i> <span class="lg:flex hidden">Edit Branch</span>
                </button>
                <?php if ($branch['visibleStatus'] === 'Visible'): ?>
                    <button id="dropdownBtn" type="button"
                        onclick="fillUpdateForm2(<?= $branch['branchId'] ?>, 'Invisible')"
                        class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                        <i class='bx bxs-show text-lg'></i>Set Invisible</span>
                    </button>
                <?php elseif ($branch['visibleStatus'] === 'Invisible'): ?>
                    <button id="dropdownBtn" type="button" onclick="fillUpdateForm2(<?= $branch['branchId'] ?>,  'Visible')"
                        class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                        <i class='bx bxs-show text-lg'></i>Set Visible</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-7">
        <div class="flex flex-col gap-4">
            <div class="flex gap-2 absolute">
                <div
                    class="flex items-center gap-2 text-secondaryForeground border border-secondaryForeground bg-secondary rounded-lg text-xs w-auto p-1 px-2 ">
                    <span>
                        <?php echo htmlspecialchars($branch['state']); ?>
                    </span>
                </div>
                <?php if ($branch['status'] === 'Opening'): ?>
                    <div
                        class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                        <div class="relative flex size-3.5 items-center justify-center">
                            <span
                                class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping duration-300"></span>
                            <span class="relative inline-flex size-2 rounded-full bg-green-600"></span>
                        </div>
                        <span>Opening</span>
                    </div>
                <?php elseif ($branch['status'] === 'Closed'): ?>
                    <div
                        class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                        <i class='bx bxs-no-entry'></i>
                        <span>Closed</span>
                    </div>
                <?php elseif ($branch['status'] === 'Setup'): ?>
                    <div
                        class="flex items-center gap-2 text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                        <i class='bx bxs-time'></i>
                        <span>Setup</span>
                    </div>
                <?php elseif ($branch['status'] === 'Deprecated'): ?>
                    <div
                        class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                        <i class='bx bxs-x-circle'></i>
                        <span>Deprecated</span>
                    </div>
                <?php endif; ?>
            </div>
            <h1 class="font-sans antialiased font-extrabold text-4xl text-current mt-7 items-center ">
                <?php echo htmlspecialchars($branch['name']); ?>
            </h1>
            <div class="mb-5 items-center space-y-1.5">
                <p class="font-sans antialiased text-base flex items-center gap-2 ">
                    <?php if (!empty($branch['address'])): ?>
                        <i class='bx bxs-map text-xl text-primary'></i> <span class="font-medium">
                            <?php echo htmlspecialchars($branch['address']); ?>
                        </span>
                    <?php else: ?>
                        <i class='bx bxs-map text-xl text-primary'></i>
                        <?php echo "<span class='italic text-secondaryForeground'>The address is not released.</span>" ?>
                    <?php endif ?>
                </p>
                <div class="flex justify-between">
                    <p class="font-sans antialiased text-base flex items-center gap-2 ">
                        <?php if (!empty($branch['contactNumber'])): ?>
                            <i class='bx bxs-phone text-xl text-primary'></i> <span class="font-medium">
                                <?php echo htmlspecialchars($branch['contactNumber']); ?>
                            </span>
                        <?php else: ?>
                            <i class='bx bxs-phone text-xl text-primary'></i>
                            <?php echo "<span class='italic text-secondaryForeground'>The contact number is not released.</span>" ?>
                        <?php endif ?>
                    </p>
                    <p class="font-sans antialiased text-base flex items-center gap-2 ">
                        <?php if (!empty($branch['endTime'])): ?>
                            <i class='bx bxs-hourglass text-xl text-primary'></i> <span class="text-green-500 font-medium">
                                <?php echo htmlspecialchars($branch['startTime']); ?>
                            </span> - <span class="text-red-500 font-medium">
                                <?php echo htmlspecialchars($branch['endTime']); ?>
                            </span>
                        <?php else: ?>
                            <i class='bx bxs-hourglass text-xl text-primary'></i>
                            <?php echo "<span class='italic text-secondaryForeground'>The opening hour is not scheduled.</span>" ?>
                        <?php endif ?>
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4.5">
                <div
                    class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-amber/20 transition-colors rounded-xl ">
                    <div class="ml-1">
                        <div class="flex items-center gap-2.5">
                            <div class="relative flex size-4 items-center justify-center">
                                <i class='bx bxs-food-menu mr-2 text-xl text-amber-600'></i>
                            </div>
                            <h1 class="text-lg font-bold text-amber-900">Menu(s)</h1>
                        </div>
                        <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                            <?php echo $totalMenuNumber; ?>
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-green/20 transition-colors rounded-xl ">
                    <div class="ml-1">
                        <div class="flex items-center gap-2.5">
                            <div class="relative flex size-4 items-center justify-center">
                                <i class='bx bx-table mr-2 text-xl text-amber-600'></i>
                            </div>
                            <h1 class="text-lg font-bold text-amber-900">Seat(s)</h1>
                        </div>
                        <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                            <?php echo $totalTableNumber; ?>
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-green/20 transition-colors rounded-xl ">
                    <div class="ml-1">
                        <div class="flex items-center gap-2.5">
                            <div class="relative flex size-4 items-center justify-center">
                                <i class='bx bxs-bowl-hot mr-2 text-xl text-amber-600'></i>
                            </div>
                            <h1 class="text-lg font-bold text-amber-900">Order(s)</h1>
                        </div>
                        <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                            <?php echo $totalOrderNumber; ?>
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-green/20 transition-colors rounded-xl ">
                    <div class="ml-1">
                        <div class="flex items-center gap-2.5">
                            <div class="relative flex size-4 items-center justify-center">
                                <i class='bx bx-dollar mr-2 text-xl text-amber-600'></i>
                            </div>
                            <h1 class="text-lg font-bold text-amber-900">Revenue(s)</h1>
                        </div>
                        <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                            0
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-green/20 transition-colors rounded-xl ">
                    <div class="ml-1">
                        <div class="flex items-center gap-2.5">
                            <div class="relative flex size-4 items-center justify-center">
                                <i class='bx bxs-user mr-2 text-xl text-amber-600'></i>
                            </div>
                            <h1 class="text-lg font-bold text-amber-900">Employee(s)</h1>
                        </div>
                        <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                            0
                        </p>
                    </div>
                </div>
                <div
                    class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-green/20 transition-colors rounded-xl ">
                    <div class="ml-1">
                        <div class="flex items-center gap-2.5">
                            <div class="relative flex size-4 items-center justify-center">
                                <i class='bx bxs-star mr-2 text-xl text-amber-600'></i>
                            </div>
                            <h1 class="text-lg font-bold text-amber-900">Review(s)</h1>
                        </div>
                        <p class="text-3xl text-amber-950 mt-3 font-extrabold">
                            0
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'update-visible-status-dialog.php'; ?>

<script>
    function fillUpdateForm2(branchId, visibleStatus) {
        //console.log(branchId, name);

        document.getElementById("branchIdVisible").value = branchId;
        document.getElementById("visibleStatus").value = visibleStatus;

        document.getElementById("confirmText").innerText =
            "Are you sure you want to change visible status to " + visibleStatus + "?";

        document.getElementById("updateBranchVisibleStatusDialog").classList.remove("opacity-0", "pointer-events-none");
    }
</script>