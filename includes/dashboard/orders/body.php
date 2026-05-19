<section
    class="relative overflow-y-scroll h-screen flex flex-col bg-linear-to-b from-blue-50 via-transparent to-transparent pb-12 pt-8 w-full">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <h1
            class="max-w-7xla mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8 w-full flex items-center">
            <!-- Sidebar -->
            <button class="text-gray-500 hover:text-gray-600 md:hidden flex" id="open-sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

            Status
        </h1>
    </div>
    <div class="flex sm:items-center flex-wrap">
        <?php include 'header.php'; ?>
        <!-- Dropdown Container -->
        <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 ">

            <div class="w-auto text-center max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
                <div class="w-full overflow-x-auto rounded-lg border border-slate-200 mt-4">
                    <table class="text-left w-full">
                        <thead
                            class="border-b border-secondary bg-accent text-sm font-medium text-accentForeground dark:bg-surface-dark">
                            <tr>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Order ID
                                </th>
                                <th class="px-5 py-2 text-start font-medium">
                                    Created Date
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Customer Name
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Order Method
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Order Location
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Order Status
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Payment Status
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
                                $filter = " AND (food_category.name LIKE ?)";
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
                                $filter .= " AND food_category.status IN (" . implode(',', $escapedStatuses) . ")";
                            }

                            if (!empty($_GET['branch'])) {
                                $branches = $_GET['branch'] ?? [];
                                if (!is_array($branches)) {
                                    $branches = [$branches];
                                }
                                $escapedBranches = array_map(function ($id) use ($conn) {
                                    return (int) $id;
                                }, $selectedBranches);
                                $filter .= " AND food_category.branchId IN (" . implode(',', $escapedBranches) . ")";
                            }
                            $limitRecords = 10;
                            $perPage = isset($_GET['perPage']) && is_numeric($_GET['perPage']) ? (int) $_GET['perPage'] : 1;
                            if ($perPage < 1) {
                                $perPage = 1;
                            }
                            ;
                            $offset = ($perPage - 1) * $limitRecords;
                            $countQuery = "
                            SELECT COUNT(*) as total FROM payment
                            JOIN `order` ON payment.orderId = order.orderId
                            JOIN branch ON order.branchId = branch.branchId
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
                            SELECT payment.*, order.orderId, order.orderStatus, order_method.methodName, seat_table.tableName,
                            branch.name AS branchName, branch.address, branch.slug,
                            user.name AS username, user.contactNumber
                            FROM payment
                            JOIN `order` ON payment.orderId = order.orderId
                            JOIN order_method ON order.methodId = order_method.methodId
                            JOIN branch ON order.branchId = branch.branchId
                            JOIN user ON order.userId = user.userId
                            LEFT JOIN seat_table ON `order`.tableId = seat_table.tableId
                            WHERE 1 $filter
                            ORDER BY payment.paymentId DESC
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
                                    <?php if ($data['orderStatus'] !== 'Cancelled'): ?>
                                        <tr>
                                            <td class="p-4 border-b border-surface-light">
                                                <div class="flex items-center gap-3">
                                                    <!---<img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-spotify.svg"
                                            alt="Spotify" />--->
                                        <a
                                            href="/web/pdf/display-order-pdf?orderId=<?php echo htmlspecialchars($data['orderId']) ?>">
                                            <small
                                                class="font-sans antialiased text-sm text-current font-bold hover:underline">#
                                                <?php echo htmlspecialchars($data['orderId']); ?>
                                            </small>
                                        </a>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small
                                        class="font-sans antialiased text-sm text-current capitalize hover:underline">
                                        <?php echo htmlspecialchars($data['createdAt']); ?>
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex flex-col">
                                        <small class="font-sans antialiased text-sm text-current capitalize">
                                            <?php echo htmlspecialchars($data['username']); ?>
                                        </small>
                                        <small class="font-sans antialiased text-sm text-current opacity-70">
                                            <?php echo htmlspecialchars($data['contactNumber']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex flex-col">
                                        <small class="font-sans antialiased text-sm text-current capitalize">
                                            <?php echo htmlspecialchars($data['methodName']); ?>
                                        </small>
                                        <?php if (!empty($data['tableName'])): ?>
                                        <small class="font-semibold antialiased text-sm text-current opacity-70">
                                            <?php echo htmlspecialchars($data['tableName']); ?>
                                        </small>
                                        <?php endif; ?>
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
                                                    <?php echo htmlspecialchars($data['branchName']); ?>
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
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 border-transparent text-green-500 shadow-none">
                                            <?php if ($data['orderStatus'] === 'Done'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-bell text-lg'></i>
                                                </div>
                                                <span>Done</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'In Progress'): ?>
                                            <div
                                                class="flex items-center gap-2 text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bx-loader text-lg'></i>
                                                </div>
                                                <span>In Progress</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Pending'): ?>
                                            <div
                                                class="flex items-center gap-2 text-orange-500 border border-orange-500 bg-orange-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-hourglass-top text-lg'></i>
                                                </div>
                                                <span>Pending</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Shipping'): ?>
                                            <div
                                                class="flex items-center gap-2 text-blue-500 border border-blue-500 bg-blue-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-car text-lg'></i>
                                                </div>
                                                <span>Shipping</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Delivered'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-car text-lg'></i>
                                                </div>
                                                <span>Delivered</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Cancelled'): ?>
                                            <div
                                                class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-x-circle text-lg'></i>
                                                </div>
                                                <span>Cancelled</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 border-transparent text-green-500 shadow-none">
                                            <?php if ($data['paymentStatus'] === 'Pending'): ?>
                                            <div
                                                class="flex items-center gap-2 text-orange-500 border border-orange-500 bg-orange-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-hourglass-top text-lg'></i>
                                                </div>
                                                <span>Pending</span>
                                            </div>
                                            <?php elseif ($data['paymentStatus'] === 'Success'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-check-circle text-lg'></i>
                                                </div>
                                                <span>Paid</span>
                                            </div>
                                            <?php elseif ($data['paymentStatus'] === 'Cancelled'): ?>
                                            <div
                                                class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-x-circle text-lg'></i>
                                                </div>
                                                <span>Cancelled</span>
                                            </div>
                                            <?php endif; ?>
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

                                            <a href="/web/pdf/display-order-pdf?orderId=<?php echo htmlspecialchars($data['orderId']); ?>"
                                                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                <i class='bx bxs-receipt mr-2 text-lg'></i>
                                                View Receipt
                                            </a>
                                            <a href="/web/pdf/download-order-pdf?orderId=<?php echo htmlspecialchars($data['orderId']); ?>"
                                                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                <i class='bx bxs-download mr-2 text-lg'></i>
                                                Download Receipt
                                            </a>
                                            <button type="button" onclick='fillUpdateForm(
                                                <?php echo json_encode($data["orderId"]); ?>,
                                                <?php echo json_encode($data["orderStatus"]); ?>,
                                                )'
                                                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                                                <i class='bx bxs-edit mr-2 text-lg'></i>
                                                Update Order Status
                                            </button>

                                            <button type="button" onclick='deleteForm(
                                                <?php echo json_encode($data["categoryId"]); ?>,
                                                <?php echo json_encode($data["branchId"]); ?>
                                                )'
                                                class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                                                <i class='bx bxs-trash mr-2 text-lg'></i>
                                                Cancel Order
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="bg-secondary cursor-not-allowed">
                                <td class="p-4 border-b border-surface-light line-through cursor-not-allowed">
                                    <div class="flex items-center gap-3">
                                        <!---<img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-spotify.svg"
                                            alt="Spotify" />--->
                                        <small
                                            class="font-sans antialiased text-sm text-current font-bold hover:underline">#
                                            <?php echo htmlspecialchars($data['orderId']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light line-through">
                                    <small
                                        class="font-sans antialiased text-sm text-current capitalize hover:underline">
                                        <?php echo htmlspecialchars($data['createdAt']); ?>
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light line-through">
                                    <div class="flex flex-col">
                                        <small class="font-sans antialiased text-sm text-current capitalize">
                                            <?php echo htmlspecialchars($data['username']); ?>
                                        </small>
                                        <small class="font-sans antialiased text-sm text-current opacity-70">
                                            <?php echo htmlspecialchars($data['contactNumber']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light line-through">
                                    <div class="flex flex-col">
                                        <small class="font-sans antialiased text-sm text-current capitalize">
                                            <?php echo htmlspecialchars($data['methodName']); ?>
                                        </small>
                                        <?php if (!empty($data['tableName'])): ?>
                                        <small class="font-semibold antialiased text-sm text-current opacity-70">
                                            <?php echo htmlspecialchars($data['tableName']); ?>
                                        </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light line-through">
                                    <div class="flex items-center gap-3">
                                        <!---<div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/visa.png"
                                                alt="visa" />
                                        </div>--->
                                        <div class="flex flex-col cursor-not-allowed">

                                            <small
                                                class="font-sans antialiased text-sm text-current capitalize hover:underline">
                                                <?php echo htmlspecialchars($data['branchName']); ?>
                                            </small>
                                            <?php if (!empty($data['address'])): ?>
                                            <small
                                                class="font-sans antialiased text-sm text-current opacity-70 hover:underline">
                                                <?php echo htmlspecialchars($data['address']); ?>
                                            </small>
                                            <?php else: ?>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                -
                                            </small>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 border-transparent text-green-500 shadow-none">
                                            <?php if ($data['orderStatus'] === 'Done'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-bell text-lg'></i>
                                                </div>
                                                <span>Done</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'In Progress'): ?>
                                            <div
                                                class="flex items-center gap-2 text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bx-loader text-lg'></i>
                                                </div>
                                                <span>In Progress</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Pending'): ?>
                                            <div
                                                class="flex items-center gap-2 text-orange-500 border border-orange-500 bg-orange-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-hourglass-top text-lg'></i>
                                                </div>
                                                <span>Pending</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Shipping'): ?>
                                            <div
                                                class="flex items-center gap-2 text-blue-500 border border-blue-500 bg-blue-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-car text-lg'></i>
                                                </div>
                                                <span>Shipping</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Delivered'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-car text-lg'></i>
                                                </div>
                                                <span>Delivered</span>
                                            </div>
                                            <?php elseif ($data['orderStatus'] === 'Cancelled'): ?>
                                            <div
                                                class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-x-circle text-lg'></i>
                                                </div>
                                                <span>Cancelled</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 border-transparent text-green-500 shadow-none">
                                            <?php if ($data['paymentStatus'] === 'Pending'): ?>
                                            <div
                                                class="flex items-center gap-2 text-orange-500 border border-orange-500 bg-orange-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-hourglass-top text-lg'></i>
                                                </div>
                                                <span>Pending</span>
                                            </div>
                                            <?php elseif ($data['paymentStatus'] === 'Success'): ?>
                                            <div
                                                class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-check-circle text-lg'></i>
                                                </div>
                                                <span>Paid</span>
                                            </div>
                                            <?php elseif ($data['paymentStatus'] === 'Cancelled'): ?>
                                            <div
                                                class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto p-1 px-2">
                                                <div class="relative flex size-3.5 items-center justify-center">
                                                    <i class='bx bxs-x-circle text-lg'></i>
                                                </div>
                                                <span>Cancelled</span>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="dropdown">
                                        <button data-toggle="dropdown" aria-expanded="false"
                                            class="inline-grid cursor-not-allowed place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                            <i class='bx bx-dots-vertical-rounded text-secondaryForeground'></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
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
                        <!--<?php include 'update-category-dialog.php'; ?>
                        <?php include 'delete-category-dialog.php'; ?>
                        <?php include 'cannot-delete-alert-dialog.php'; ?>-->
                        <?php if (isset($_GET['cannotDelete']) && $_GET['cannotDelete'] == 1): ?>
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    openCannotDeleteDialog();
                                });
                                document.addEventListener("keydown", function (event) {
                                    if (event.key === "Escape") {
                                        event.preventDefault(); // Stop default browser action
                                        event.stopPropagation(); // Stop other scripts from interfering

                                        window.location.assign('/web/dashboard/categories');
                                    }
                                }, true);
                            </script>
                        <?php endif; ?>
                        <?php include 'update-order-status-dialog.php'; ?>
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

    function fillUpdateForm(orderId, orderStatus) {
        //console.log(branchId, name);

        document.getElementById("orderId").value = orderId;
        document.getElementById("updateStatus").value = orderStatus;
        //document.getElementById("updateBranch").value = branchId;

        document.getElementById("updateOrderStatusDialog").classList.remove("opacity-0", "pointer-events-none");

        // ESC close
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                document.getElementById("updateOrderStatusDialog").classList.add("opacity-0", "pointer-events-none");
            }
        });
    }

    function deleteForm(categoryId, branchId) {
        document.getElementById("deleteCategoryId").value = categoryId;
        document.getElementById("deleteBranchId").value = branchId;

        document.getElementById("deleteCategoryDialog").classList.remove("opacity-0", "pointer-events-none");

        // ESC close
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                document.getElementById("deleteCategoryDialog").classList.add("opacity-0", "pointer-events-none");
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