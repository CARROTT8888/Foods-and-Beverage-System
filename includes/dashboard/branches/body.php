<section
    class="relative overflow-y-scroll h-screen bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8 max-w-7xl w-full">
    <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8 w-full">
        <!-- Sidebar -->
        <!---<button class="text-gray-500 hover:text-gray-600" id="open-sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>--->
        <button type="button" data-toggle="modal" data-target="#sidebarDrawer"
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
        Branches
    </h1>
    <div class="flex sm:items-center flex-wrap gap-6">
        <!-- Dropdown Container -->
        <div class="relative mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl w-full">
            <?php include '../includes/dashboard/branches/header.php'; ?>
            <div
                class="w-auto text-center grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
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
                /*if (isset($_GET['status']) && !empty($_GET['status'])) {
                    if ($_GET['status'] == 'Opening') {
                        $filter .= " AND branch.status = 'Opening'";
                    } elseif ($_GET['status'] == 'Closed') {
                        $filter .= " AND branch.status = 'Closed'";
                    } elseif ($_GET['status'] == 'Setup') {
                        $filter .= " AND branch.status = 'Setup'";
                    } elseif ($_GET['status'] == 'Deprecated') {
                        $filter .= " AND branch.status = 'Deprecated'";
                    }
                }*/
                if (!empty($_GET['status'])) {
                    $statuses = $_GET['status'];
                    if (!is_array($statuses)) {
                        $statuses = [$statuses];
                    }
                    $escapedStatuses = array_map(function ($status) use ($conn) {
                        return "'" . $conn->real_escape_string($status) . "'";
                    }, $statuses);
                    $filter .= " AND branch.status IN (" . implode(',', $escapedStatuses) . ")";
                }
                if (!empty($_GET['state'])) {
                    $states = $_GET['state'];
                    if (!is_array($states)) {
                        $states = [$states];
                    }
                    $escapedStates = array_map(function ($state) use ($conn) {
                        return "'" . $conn->real_escape_string($state) . "'";
                    }, $states);
                    $filter .= " AND branch.state IN (" . implode(',', $escapedStates) . ")";
                }
                $branchQuery = "SELECT * FROM branch WHERE 1" . $filter . " ORDER BY branchId DESC";
                $stmt = $conn->prepare($branchQuery);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $branchResult = $stmt->get_result();
                //$branchResult = $conn->query($branchQuery);
                if ($branchResult->num_rows > 0):
                    while ($data = $branchResult->fetch_assoc()):
                        ?>
                        <div
                            class="rounded-lg border text-start overflow-hidden mt-5 bg-white border-slate-200 shadow-slate-950/5 w-full max-w-[26rem]a shadow-lg">
                            <div class="p-2 h-max rounded relative">
                                <?php if ($data['image']): ?>
                                    <img class="w-full h-48 object-cover rounded"
                                        src="/Foods-and-Beverage-System/uploads/branches/<?php echo htmlspecialchars($data['image']); ?>"
                                        alt="test" />
                                <?php else: ?>
                                    <img class="w-full h-full rounded"
                                        src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.sopandai.com%2Fwp-content%2Fuploads%2F2023%2F01%2FMMU.png.webp&f=1&nofb=1&ipt=ed618de2de637fb9769308656bab69713f756476ef4ce6bd2ae83063b07b3f18"
                                        alt="ui/ux review check" />
                                <?php endif; ?>
                                <?php if ($data['status'] === 'Opening'): ?>
                                    <div
                                        class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-5 right-5">
                                        <div class="relative flex size-3.5 items-center justify-center">
                                            <span
                                                class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping duration-300"></span>
                                            <span class="relative inline-flex size-2 rounded-full bg-green-600"></span>
                                        </div>
                                        <span>Opening</span>
                                    </div>
                                <?php elseif ($data['status'] === 'Closed'): ?>
                                    <div
                                        class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-5 right-5">
                                        <i class='bx bxs-no-entry'></i>
                                        <span>Closed</span>
                                    </div>
                                <?php elseif ($data['status'] === 'Setup'): ?>
                                    <div
                                        class="flex items-center gap-2 text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-5 right-5">
                                        <i class='bx bxs-time'></i>
                                        <span>Setup</span>
                                    </div>
                                <?php elseif ($data['status'] === 'Deprecated'): ?>
                                    <div
                                        class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-5 right-5">
                                        <i class='bx bxs-x-circle '></i>
                                        <span>Deprecated</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="w-full h-max rounded px-3.5 py-2.5 ">
                                <div class="mb-2 flex items-center justify-between">
                                    <h6
                                        class="font-sans antialiased font-bold text-base md:text-lg lg:text-xl text-current line-clamp-1">
                                        <?php echo htmlspecialchars($data['name']) ?>
                                    </h6>

                                    <div class="dropdown" data-placement="bottom">
                                        <button data-toggle="dropdown" aria-expanded="false"
                                            class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-800/5 hover:border-slate-800/5 shadow-none hover:shadow-none">
                                            <i class='bx bx-dots-vertical-rounded text-xl'></i>
                                        </button>
                                        <div data-role="menu"
                                            class="hidden min-w-40 grid max-w-lg grid-cols-1 gap-3a mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-10">

                                            <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($data['slug']); ?>"
                                                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                <i class='bx bxs-door-open mr-2 text-lg'></i>
                                                View Details
                                            </a>
                                            <button type="button" onclick='fillUpdateForm(
                                                <?php echo json_encode($data["branchId"]); ?>,
                                                <?php echo json_encode($data["name"]); ?>,
                                                <?php echo json_encode($data["slug"]); ?>,
                                                <?php echo json_encode($data["address"]); ?>,
                                                <?php echo json_encode($data["status"]); ?>,
                                                <?php echo json_encode($data["startTime"]); ?>,
                                                <?php echo json_encode($data["endTime"]); ?>,
                                                <?php echo json_encode($data["contactNumber"]); ?>,
                                                <?php echo json_encode($data["state"]); ?>,
                                                <?php echo json_encode($data["image"]); ?>
                                                )'
                                                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                <i class='bx bxs-edit mr-2 text-lg'></i>
                                                Update Branch
                                            </button>

                                            <a href="sign-out.php"
                                                class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                                                <i class='bx bxs-low-vision mr-2 text-lg'></i>
                                                Set Inactive
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="flex gap-2 items-center">
                                    <p class="font-sans antialiased text-base"><i class='bx bxs-map text-xl text-primary'></i>
                                    </p>
                                    <span class="font-sans line-clamp-1">
                                        <?php if (!empty($data['address'])): ?>
                                            <div title="<?php echo htmlspecialchars($data['address']); ?>">
                                                <span class="font-medium"><?php echo htmlspecialchars($data['address']); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="italic text-secondaryForeground" title="The address is not released.">The
                                                address is not released.
                                            </div>
                                        <?php endif ?>
                                    </span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <p class="font-sans antialiased text-base"><i class='bx bxs-phone text-xl text-primary'></i>
                                    </p>
                                    <span class="font-sans line-clamp-1">
                                        <?php if (!empty($data['contactNumber'])): ?>
                                            <div title="<?php echo htmlspecialchars($data['contactNumber']); ?>">
                                                <span
                                                    class="font-medium"><?php echo htmlspecialchars($data['contactNumber']); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="italic text-secondaryForeground"
                                                title="The contact number is not released.">The contact number is
                                                not released.</div>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <p class="font-sans antialiased text-base "><i
                                            class='bx bxs-hourglass text-xl text-primary'></i>
                                    </p>
                                    <span class="font-sans">
                                        <?php if (!empty($data['endTime'])): ?>
                                            <span
                                                class="text-green-500 font-medium"><?php echo htmlspecialchars($data['startTime']); ?></span>
                                            - <span
                                                class="text-red-500 font-medium"><?php echo htmlspecialchars($data['endTime']); ?></span>
                                        <?php else: ?>
                                            <div class="italic text-secondaryForeground" title="The time is not scheduled.">The time
                                                is not scheduled.</div>
                                        <?php endif ?>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full px-3.5 pb-3.5 rounded pt-3">
                                <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($data['slug']); ?>">
                                    <button
                                        class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                        data-shape="default" data-width="full">
                                        Visit
                                    </button>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                else:
                    include_once 'not-found.php';
                endif; ?>
                <?php include 'update-branch-dialog.php'; ?>
</section>
<script>
    const sidebar = document.getElementById('sidebar');
    const openSidebarButton = document.getElementById('open-sidebar');

    openSidebarButton.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('-translate-x-full');
    });

    // Close the sidebar when clicking outside of it
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !openSidebarButton.contains(e.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });

    function fillUpdateForm(branchId, name, slug, address, status, startTime, endTime, contactNumber, state, image) {
        console.log(branchId, name);

        document.getElementById("branchId").value = branchId;
        document.getElementById("updateName").value = name;
        document.getElementById("updateSlug").value = slug;
        document.getElementById("updateAddress").value = address;
        document.getElementById("updateStatus").value = status;
        document.getElementById("updateStartTime").value = startTime;
        document.getElementById("updateEndTime").value = endTime;
        document.getElementById("updateContactNumber").value = contactNumber;
        document.getElementById("updateState").value = state;
        //document.getElementById("previewImage").src = "/Foods-and-Beverage-System/uploads/branches/" + image;

        if (image) {
            document.getElementById("previewImage").src = "/Foods-and-Beverage-System/uploads/branches/" + image;
        } else {
            document.getElementById("previewImage").src = "https://via.placeholder.com/150";
        }

        // OPEN dialog
        document.getElementById("updateBranchDialog").classList.remove("opacity-0", "pointer-events-none");

        // ESC close
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                document.getElementById("updateBranchDialog").classList.add("opacity-0", "pointer-events-none");
            }
        });
    }
</script>