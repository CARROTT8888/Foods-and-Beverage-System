<?php
include './database/fnbdb.php';
$filter = "";
$countstatusOpeningsql = "SELECT COUNT(*) FROM branch WHERE status = 'Opening'";
$stmtcount = $conn->prepare($countstatusOpeningsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusOpening);
$stmtcount->fetch();
$stmtcount->close();
$countstatusClosedsql = "SELECT COUNT(*) FROM branch WHERE status = 'Closed'";
$stmtcount = $conn->prepare($countstatusClosedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusClosed);
$stmtcount->fetch();
$stmtcount->close();
$countstatusSetupsql = "SELECT COUNT(*) FROM branch WHERE status = 'Setup'";
$stmtcount = $conn->prepare($countstatusSetupsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusSetup);
$stmtcount->fetch();
$stmtcount->close();
$countstatusDeprecatedsql = "SELECT COUNT(*) FROM branch WHERE status = 'Deprecated'";
$stmtcount = $conn->prepare($countstatusDeprecatedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusDeprecated);
$stmtcount->fetch();
$stmtcount->close();
$countnobranchsql = "SELECT COUNT(*) FROM branch";
$stmtcount = $conn->prepare($countnobranchsql);
$stmtcount->execute();
$stmtcount->bind_result($totalBranchNumber);
$stmtcount->fetch();
$stmtcount->close();
?>

<section
    class="relative overflow-hidden bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8 ">
    <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8">Select a Location</h1>
    <div class="flex sm:items-center flex-wrap gap-6 ">
        <!-- Dropdown Container -->
        <div class="relative mx-auto px-4 sm:px-6 lg:px-8 ">
            <?php include 'header.php'; ?>
            <div
                class="w-auto text-center grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
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
                            class="rounded-lg border text-start overflow-hidden bg-white border-slate-200 shadow-slate-950/5 w-full max-w-[26rem] shadow-lg">
                            <div class="p-2 h-max rounded relative">
                                <img class="w-full h-48 object-cover rounded"
                                    src="/Foods-and-Beverage-System/uploads/branches/<?php echo htmlspecialchars($data['image']); ?>"
                                    alt="ui/ux review check" />
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
                                    <p class="font-sans antialiased text-base text-current flex items-center gap-1.5"><svg
                                            width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                            class="h-[18px] w-[18px] text-amber-500">
                                            <path
                                                d="M8.58737 8.23597L11.1849 3.00376C11.5183 2.33208 12.4817 2.33208 12.8151 3.00376L15.4126 8.23597L21.2215 9.08017C21.9668 9.18848 22.2638 10.0994 21.7243 10.6219L17.5217 14.6918L18.5135 20.4414C18.6409 21.1798 17.8614 21.7428 17.1945 21.3941L12 18.678L6.80547 21.3941C6.1386 21.7428 5.35909 21.1798 5.48645 20.4414L6.47825 14.6918L2.27575 10.6219C1.73617 10.0994 2.03322 9.18848 2.77852 9.08017L8.58737 8.23597Z"
                                                fill="currentColor" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                        </svg>5.0</p>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <p class="font-sans antialiased text-base"><i class='bx bxs-map text-xl'></i>
                                    </p>
                                    <span class="font-sans text-secondaryForeground line-clamp-1">
                                        <?php if (!empty($data['address'])): ?>
                                            <div title="<?php echo htmlspecialchars($data['address']); ?>">
                                                <?php echo htmlspecialchars($data['address']); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="italic" title="The address is not released.">The address is not released.
                                            </div>
                                        <?php endif ?>
                                    </span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <p class="font-sans antialiased text-base"><i class='bx bxs-phone text-xl'></i></p>
                                    <span class="font-sans text-secondaryForeground line-clamp-1">
                                        <?php if (!empty($data['contactNumber'])): ?>
                                            <div title="<?php echo htmlspecialchars($data['contactNumber']); ?>">
                                                <?php echo htmlspecialchars($data['contactNumber']); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="italic" title="The contact number is not released.">The contact number is
                                                not released.</div>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="flex gap-2 items-center">
                                    <p class="font-sans antialiased text-base "><i class='bx bxs-hourglass text-xl'></i>
                                    </p>
                                    <span class="font-sans text-secondaryForeground">
                                        <?php if (!empty($data['endTime'])): ?>
                                            <?php echo htmlspecialchars($data['startTime']); ?> -
                                            <?php echo htmlspecialchars($data['endTime']); ?>
                                        <?php else: ?>
                                            <div class="italic" title="The time is not scheduled.">The time is not scheduled.</div>
                                        <?php endif ?>
                                    </span>
                                </div>
                            </div>
                            <div class="w-full px-3.5 pb-3.5 rounded pt-3"><button
                                    class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                    data-shape="default" data-width="full">Order</button></div>
                        </div>
                        <?php
                    endwhile;
                else:
                    include 'not-found.php';
                endif; ?>
</section>