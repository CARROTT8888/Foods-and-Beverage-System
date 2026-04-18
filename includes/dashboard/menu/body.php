<section
    class="relative overflow-y-scroll h-screen bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8 max-w-7xl w-full">
    <div>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </span>
            </button>
            <?php include '../includes/dashboard/menu/drawer.php'; ?>
            Menu
        </h1>
    </div>
    <div class="flex sm:items-center flex-wrap gap-6">
        <!-- Dropdown Container -->
        <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 ">
            <?php include 'header.php'; ?>
            <div
                class="w-auto text-center grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 space-y-0 max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
                <?php
                $filter = "";
                $params = [];
                $types = "";
                $search = $_GET['search'] ?? '';
                if (!empty($search)) {
                    $filter = " AND (food.name LIKE ?)";
                    $search = "%" . $search . "%";
                    $params = [$searchValue];
                    $types = "s";
                }
                ;
                if (!empty($_GET['status'])) {
                    $statuses = $_GET['status'];
                    if (!is_array($statuses)) {
                        $statuses = [$statuses];
                    }
                    $escapedStatuses = array_map(function ($status) use ($conn) {
                        return "'" . $conn->real_escape_string($status) . "'";
                    }, $statuses);
                    $filter .= " AND food.status IN (" . implode(',', $escapedStatuses) . ")";
                }
                $menuQuery = "
                            SELECT food.*, food_category.name AS categoryName
                            FROM food
                            JOIN food_category ON food.categoryId = food_category.categoryId
                            JOIN branch ON food.branchId = branch.branchId
                            WHERE 1 $filter
                            ORDER BY food.foodId DESC
                            ";
                $stmt = $conn->prepare($menuQuery);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $menuResult = $stmt->get_result();
                if ($menuResult->num_rows > 0):
                    while ($data = $menuResult->fetch_assoc()):
                        ?>
                        <?php if ($data['visibleStatus'] === 'Invisible'): ?>
                            <div
                                class="relative flex flex-col my-6 bg-slate-50 shadow-sm border border-slate-200 rounded-lg w-full">
                                <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md ">
                                    <div class="flex justify-between">
                                        <div
                                            class="flex items-center gap-2 text-secondaryForeground border border-secondaryForeground bg-secondary rounded-lg text-xs w-auto mx-auto absolute p-1 px-2 top-2 left-2">
                                            <span><?php echo htmlspecialchars($data['categoryName']); ?></span>
                                        </div>
                                        <?php if ($data['status'] === 'Available'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                    class="text-green-600 size-3.5">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                                <span>Available</span>
                                            </div>
                                        <?php elseif ($data['status'] === 'Sold Out'): ?>
                                            <div
                                                class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                    class="size-3.5 text-red-600">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                                <span>Sold Out</span>
                                            </div>
                                        <?php elseif ($data['status'] === 'Discontinued'): ?>
                                            <div
                                                class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-slate-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                                                </svg>
                                                <span>Discontinued</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <img src="../assets/burger-sample.jpg" alt="card-image" />
                                </div>
                                <div class="pl-4 pr-1">
                                    <div class="flex items-center">
                                        <h6 class="text-slate-800 text-xl font-semibold">
                                            <?php echo htmlspecialchars($data['name']); ?> <i
                                                class='bx bxs-low-vision text-secondaryForeground'></i>
                                        </h6>

                                        <div class="flex items-center gap-0 5 ml-auto">
                                            <div class="dropdown">
                                                <button data-toggle="dropdown" aria-expanded="false"
                                                    class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                                    <i class='bx bx-dots-vertical-rounded'></i>
                                                </button>
                                                <div data-role="menu"
                                                    class="hidden min-w-40 grid max-w-lg grid-cols-1 gap-3a mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-10 absolute">

                                                    <a href=""
                                                        class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                        <i class='bx bxs-bowl-rice mr-2 text-lg'></i>
                                                        View Details
                                                    </a>
                                                    <a href=""
                                                        class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                        <i class='bx bxs-door-open mr-2 text-lg'></i>
                                                        View Menu
                                                    </a>
                                                    <button type="button" onclick='fillUpdateForm(
                                                <?php echo json_encode($data["tableId"]); ?>,
                                                <?php echo json_encode($data["tableName"]); ?>,
                                                <?php echo json_encode($data["totalSeat"]); ?>,
                                                <?php echo json_encode($data["availableSeat"]); ?>,
                                                <?php echo json_encode($data["status"]); ?>,
                                                <?php echo json_encode($data["branchId"]); ?>
                                                )'
                                                        class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                        <i class='bx bxs-edit mr-2 text-lg'></i>
                                                        Update Table
                                                    </button>
                                                    <?php if ($data['visibleStatus'] === 'Invisible'): ?>
                                                        <button type="button"
                                                            onclick="fillUpdateForm2(<?= $data['foodId'] ?>, 'Visible')"
                                                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                            <i class='bx bxs-show mr-2 text-lg'></i>
                                                            Set Visible
                                                        </button>
                                                    <?php endif; ?>

                                                    <a href="sign-out.php"
                                                        class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                                                        <i class='bx bxs-trash mr-2 text-lg'></i>
                                                        Delete Menu
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 overflow-hidden flex-nowrap">
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Cucumber</span>
                                        </div>
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Spicy Level</span>
                                        </div>
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Size Level</span>
                                        </div>
                                        <div data-open="true" data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">

                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Learn More</span>
                                            <div
                                                class="grid place-items-center shrink-0 rounded-full p-px -translate-x-1 ms-1 w-5 h-5 stroke-2">
                                                <i class='bx bx-plus '></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 pt-0 mt-2">
                                    <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>">
                                        <button
                                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                            data-shape="default" data-width="full">
                                            Learn More
                                        </button>
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-full">
                                <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md ">
                                    <div class="flex justify-between">
                                        <div
                                            class="flex items-center gap-2 text-secondaryForeground border border-secondaryForeground bg-secondary rounded-lg text-xs w-auto mx-auto absolute p-1 px-2 top-2 left-2">
                                            <span>Hamburger</span>
                                        </div>
                                        <?php if ($data['status'] === 'Available'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                    class="text-green-600 size-3.5">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                                <span>Available</span>
                                            </div>
                                        <?php elseif ($data['status'] === 'Sold Out'): ?>
                                            <div
                                                class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                    class="size-3.5 text-red-600">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                                        fill="currentColor"></path>
                                                </svg>
                                                <span>Sold Out</span>
                                            </div>
                                        <?php elseif ($data['status'] === 'Discontinued'): ?>
                                            <div
                                                class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-slate-600" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                                                </svg>
                                                <span>Discontinued</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <img src="../assets/burger-sample.jpg" alt="card-image" />
                                </div>
                                <div class="pl-4 pr-1">
                                    <div class="flex items-center">
                                        <h6 class="text-slate-800 text-xl font-semibold">
                                            <?php echo htmlspecialchars($data['name']); ?> <i
                                                class='bx bxs-show text-secondaryForeground'></i>
                                        </h6>

                                        <div class="flex items-center gap-0 5 ml-auto">
                                            <div class="dropdown">
                                                <button data-toggle="dropdown" aria-expanded="false"
                                                    class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                                    <i class='bx bx-dots-vertical-rounded'></i>
                                                </button>
                                                <div data-role="menu"
                                                    class="hidden min-w-40 grid max-w-lg grid-cols-1 gap-3a mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-10 absolute">
                                                    <a href=""
                                                        class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                        <i class='bx bxs-bowl-rice mr-2 text-lg'></i>
                                                        View Details
                                                    </a>
                                                    <a href=""
                                                        class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                        <i class='bx bxs-door-open mr-2 text-lg'></i>
                                                        View Menu
                                                    </a>
                                                    <button type="button" onclick='fillUpdateForm(
                                                <?php echo json_encode($data["tableId"]); ?>,
                                                <?php echo json_encode($data["tableName"]); ?>,
                                                <?php echo json_encode($data["totalSeat"]); ?>,
                                                <?php echo json_encode($data["availableSeat"]); ?>,
                                                <?php echo json_encode($data["status"]); ?>,
                                                <?php echo json_encode($data["branchId"]); ?>
                                                )'
                                                        class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                        <i class='bx bxs-edit mr-2 text-lg'></i>
                                                        Update Menu
                                                    </button>
                                                    <?php if ($data['visibleStatus'] === 'Visible'): ?>
                                                        <button type="button"
                                                            onclick="fillUpdateForm2(<?= $data['foodId'] ?>, 'Invisible')"
                                                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                            <i class='bx bxs-low-vision mr-2 text-lg'></i>
                                                            Set Invisible
                                                        </button>
                                                    <?php endif; ?>

                                                    <a href="sign-out.php"
                                                        class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                                                        <i class='bx bxs-trash mr-2 text-lg'></i>
                                                        Delete Menu
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 overflow-hidden flex-nowrap">
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Cucumber</span>
                                        </div>
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Spicy Level</span>
                                        </div>
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Size Level</span>
                                        </div>
                                        <div data-open="true" data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">

                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">Learn More</span>
                                            <div
                                                class="grid place-items-center shrink-0 rounded-full p-px -translate-x-1 ms-1 w-5 h-5 stroke-2">
                                                <i class='bx bx-plus '></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 pt-0 mt-2">
                                    <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>">
                                        <button
                                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                            data-shape="default" data-width="full">
                                            Learn More
                                        </button>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
                <?php include 'update-menu-visible-status.php'; ?>
            </div>
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

    function fillUpdateForm(branchId, name, slug, address, status, startTime, endTime, contactNumber, state) {
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

        document.getElementById("updateBranchDialog").classList.remove("opacity-0", "pointer-events-none");
    }
    function fillUpdateForm2(foodId, visibleStatus) {
        //console.log(branchId, name);

        document.getElementById("foodId").value = foodId;
        document.getElementById("visibleStatus").value = visibleStatus;

        document.getElementById("confirmText").innerText =
            "Are you sure you want to change visible status to " + visibleStatus + "?";

        document.getElementById("updateFoodVisibleStatusDialog").classList.remove("opacity-0", "pointer-events-none");
    }
</script>