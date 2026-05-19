<section
    class="relative overflow-y-scroll h-screen bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8 max-w-7xl w-full">
    <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8 w-full">
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
        <?php include '../includes/dashboard/tables/drawer.php'; ?>
        Tables
    </h1>
    <div class="flex sm:items-center flex-wrap gap-6">
        <!-- Dropdown Container -->
        <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 ">
            <?php include '../includes/dashboard/tables/header.php'; ?>
            <div class="w-auto text-center max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
                <div class="w-full overflow-x-auto rounded-lg border border-slate-200 mt-4">
                    <table class="w-full text-left">
                        <thead
                            class="border-b border-slate-200 bg-accent text-sm font-medium text-accentForeground dark:bg-surface-dark">
                            <tr>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Table Code
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Total Seat(s)
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Available Seat(s)
                                </th>
                                <th class="px-5 py-2 text-start font-medium">
                                    Status
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Branch
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
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
                            if (!empty($_GET['status'])) {
                                $statuses = $_GET['status'];
                                if (!is_array($statuses)) {
                                    $statuses = [$statuses];
                                }
                                $escapedStatuses = array_map(function ($status) use ($conn) {
                                    return "'" . $conn->real_escape_string($status) . "'";
                                }, $statuses);
                                $filter .= " AND seat_table.status IN (" . implode(',', $escapedStatuses) . ")";
                            }

                            if (!empty($_GET['branch'])) {
                                $branches = $_GET['branch'] ?? [];
                                if (!is_array($branches)) {
                                    $branches = [$branches];
                                }
                                $escapedBranches = array_map(function ($id) {
                                    return (int) $id;
                                }, $branches);
                                $filter .= " AND seat_table.branchId IN (" . implode(',', $escapedBranches) . ")";
                            }
                            $limitRecords = 10;
                            $perPage = isset($_GET['perPage']) && is_numeric($_GET['perPage']) ? (int) $_GET['perPage'] : 1;
                            if ($perPage < 1) {
                                $perPage = 1;
                            }
                            ;
                            $offset = ($perPage - 1) * $limitRecords;
                            $countQuery = "
                            SELECT COUNT(*) as total FROM seat_table
                            JOIN branch ON seat_table.branchId = branch.branchId
                            WHERE 1 $filter
                            ";
                            $countStmt = $conn->prepare($countQuery);

                            if (!empty($params)) {
                                $countStmt->bind_param($types, ...$params);
                            }

                            $countStmt->execute();
                            $countResult = $countStmt->get_result();
                            $totalRow = $countResult->fetch_assoc();
                            $totalRecords = $totalRow['total'];
                            $totalPages = ceil($totalRecords / $limitRecords);
                            $tableQuery = "
                            SELECT seat_table.*, branch.name, branch.address, branch.slug
                            FROM seat_table
                            JOIN branch ON seat_table.branchId = branch.branchId
                            WHERE 1 $filter
                            ORDER BY seat_table.tableId DESC
                            LIMIT $limitRecords OFFSET $offset
                            ";
                            $stmt = $conn->prepare($tableQuery);
                            if (!empty($params)) {
                                $stmt->bind_param($types, ...$params);
                            }
                            $stmt->execute();
                            $tableResult = $stmt->get_result();
                            if ($tableResult->num_rows > 0):
                                while ($data = $tableResult->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="p-4 border-b border-surface-light">
                                            <div class="flex items-center gap-3">
                                                <!---<img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-spotify.svg"
                                            alt="Spotify" />--->
                                        <a
                                            href="/web/dashboard/btables?slug=<?php echo htmlspecialchars($data['slug']) ?>">
                                            <small
                                                class="font-sans antialiased text-sm text-current font-bold hover:underline">
                                                <?php echo htmlspecialchars($data['tableName']); ?>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light gap-3">
                                    <small class="font-sans antialiased text-sm text-current">
                                        <?php echo htmlspecialchars($data['totalSeat']); ?>
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light gap-3">
                                    <small class="font-sans antialiased text-sm text-current">
                                        <?php echo htmlspecialchars($data['availableSeat']); ?>
                                    </small>
                                </td>

                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 border-transparent text-green-500 shadow-none">
                                            <?php if ($data['status'] === 'Available'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                        class="h-5 w-5 text-green-600">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                                <span>Available</span>
                                            </div>
                                            <?php elseif ($data['status'] === 'Occupied'): ?>
                                            <div
                                                class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                        class="h-5 w-5 text-red-600">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                                <span>Occupied</span>
                                            </div>
                                            <?php elseif ($data['status'] === 'Dirty'): ?>
                                            <div
                                                class="flex items-center gap-2 text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 3h18M3 9h18M3 15h18M3 21h18" />
                                                        <circle cx="8" cy="7" r="1" fill="currentColor" />
                                                        <circle cx="12" cy="13" r="1" fill="currentColor" />
                                                        <circle cx="16" cy="7" r="1" fill="currentColor" />
                                                    </svg>
                                                </div>
                                                <span>Dirty</span>
                                            </div>
                                            <?php elseif ($data['status'] === 'Reserved'): ?>
                                            <div
                                                class="flex items-center gap-2 text-orange-500 border border-orange-500 bg-orange-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                                        class="h-5 w-5 text-orange-600">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25ZM12.5675 17.5008C12.8446 17.1929 12.8196 16.7187 12.5117 16.4416C12.2038 16.1645 11.7296 16.1894 11.4525 16.4973L11.4425 16.5084C11.1654 16.8163 11.1904 17.2905 11.4983 17.5676C11.8062 17.8447 12.2804 17.8197 12.5575 17.5119L12.5675 17.5008Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                                <span>Reserved</span>
                                            </div>
                                            <?php elseif ($data['status'] === 'Blocked'): ?>
                                            <div
                                                class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                                                    </svg>
                                                </div>
                                                <span>Blocked</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <!---<div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/visa.png"
                                                alt="visa" />
                                        </div>--->
                                        <div class="flex flex-col">
                                            <a
                                                href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($data['slug']) ?>">
                                                <small
                                                    class="font-sans antialiased text-sm text-current capitalize hover:underline">
                                                    <?php echo htmlspecialchars($data['name']); ?>
                                                </small>
                                            </a>
                                            <?php if (!empty($data['address'])): ?>
                                            <a
                                                href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($data['slug']) ?>">
                                                <small
                                                    class="font-sans antialiased text-sm text-current opacity-70 hover:underline">
                                                    <?php echo htmlspecialchars($data['address']); ?>
                                                </small>
                                            </a>
                                            <?php else: ?>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                -
                                            </small>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="dropdown">
                                        <button data-toggle="dropdown" aria-expanded="false"
                                            class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                            <i class='bx bx-dots-vertical-rounded'></i>
                                        </button>
                                        <div data-role="menu"
                                            class="hidden min-w-40 grid max-w-lg grid-cols-1 gap-3a mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-10 absolute">
                                            <a href="/web/dashboard/btables?slug=<?php echo htmlspecialchars($data['slug']); ?>"
                                                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                <i class='bx bxs-door-open mr-2 text-lg'></i>
                                                View Tables
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

                                            <button type="button" onclick='deleteForm(
                                                <?php echo json_encode($data["tableId"]); ?>,
                                                <?php echo json_encode($data["branchId"]); ?>
                                                )'
                                                class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                                                <i class='bx bxs-trash mr-2 text-lg'></i>
                                                Delete Table
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile ?>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between border-t border-slate-200 py-4"><small
                        class="font-sans antialiased text-sm text-current">Page
                        <?php echo $perPage; ?> of
                        <?php echo $totalPages; ?>
                    </small>
                    <div class="flex gap-2">
                        <?php
                        $query = $_GET;
                        ?>
                        <?php if ($perPage > 1):
                            $query['perPage'] = $perPage - 1;
                            ?>
                        <a href="/web/dashboard/tables?<?php echo http_build_query($query); ?>"
                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-1.5 px-3 shadow-sm hover:shadow bg-transparent border-slate-200 text-slate-800 hover:bg-slate-200">
                            Previous
                        </a>
                        <?php else: ?>
                        <button disabled
                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in text-sm rounded-md py-1.5 px-3 shadow-sm bg-transparent border-slate-200 text-slate-400 cursor-not-allowed">
                            Previous
                        </button>
                        <?php endif; ?>
                        <?php if ($perPage < $totalPages):
                            $query['perPage'] = $perPage + 1;
                            ?>
                        <a href="/web/dashboard/tables?<?php echo http_build_query($query); ?>"
                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-1.5 px-3 shadow-sm hover:shadow bg-transparent border-slate-200 text-slate-800 hover:bg-slate-200">
                            Next
                        </a>
                        <?php else: ?>
                        <button disabled
                            class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in text-sm rounded-md py-1.5 px-3 shadow-sm bg-transparent border-slate-200 text-slate-400 cursor-not-allowed">
                            Next
                        </button>
                        <?php endif; ?>
                        <?php include 'update-table-dialog.php'; ?>
                        <?php include 'delete-table-dialog.php'; ?>
                        <?php include 'cannot-delete-alert-dialog.php'; ?>
                        <?php if (isset($_GET['cannotDelete']) && $_GET['cannotDelete'] == 1): ?>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                openCannotDeleteDialog();
                            });
                            document.addEventListener("keydown", function (event) {
                                if (event.key === "Escape") {
                                    event.preventDefault(); // Stop default browser action
                                    event.stopPropagation(); // Stop other scripts from interfering

                                    window.location.assign('/web/dashboard/tables');
                                }
                            }, true);
                        </script>
                        <?php endif; ?>
                    </div>
                </div>
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

    function fillUpdateForm(tableId, tableName, totalSeat, availableSeat, status, branchId) {
        //console.log(branchId, name);

        document.getElementById("tableId").value = tableId;
        document.getElementById("updateTableName").value = tableName;
        document.getElementById("updateTotalSeat").value = totalSeat;
        document.getElementById("updateAvailableSeat").value = availableSeat;
        document.getElementById("updateStatus").value = status;
        document.getElementById("updateBranch").value = branchId;

        document.getElementById("updateTableDialog").classList.remove("opacity-0", "pointer-events-none");

        // ESC close
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                document.getElementById("updateTableDialog").classList.add("opacity-0", "pointer-events-none");
            }
        });
    }

    function deleteForm(tableId, branchId) {
        document.getElementById("deleteTableId").value = tableId;
        document.getElementById("deleteBranchId").value = branchId;

        document.getElementById("deleteTableDialog").classList.remove("opacity-0", "pointer-events-none");

        // ESC close
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                document.getElementById("deleteTableDialog").classList.add("opacity-0", "pointer-events-none");
            }
        });
    }

    function openCannotDeleteDialog() {
        document.getElementById("cannotDeleteTableDialog")
            .classList.remove("opacity-0", "pointer-events-none");
    }
    function closeCannotDeleteDialog() {
        document.getElementById("cannotDeleteTableDialog")
            .classList.add("opacity-0", "pointer-events-none");
    }
</script>