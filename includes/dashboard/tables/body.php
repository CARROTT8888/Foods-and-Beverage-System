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
            <div
                class="w-auto text-center max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
                <?php
                $filter = "";
                $params = [];
                $types = "";
                /*$search = $_GET['search'] ?? '';
                if (!empty($search)) {
                    $filter = " AND (branch.name LIKE ? OR branch.address LIKE ? OR branch.state LIKE ?)";
                    $searchValue = "%" . $search . "%";
                    $params = [$searchValue, $searchValue, $searchValue];
                    $types = "sss";
                }
                ;*/
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
                $tableQuery = "SELECT * FROM seat_table WHERE 1" . $filter . " ORDER BY tableId DESC";
                $stmt = $conn->prepare($tableQuery);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $tableResult = $stmt->get_result();
                if ($tableResult->num_rows > 0):
                    while ($data = $tableResult->fetch_assoc()):
                        ?>
                        <div
                            class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                            <i class='bx bx-dots-vertical-rounded absolute w-full flex justify-end text-2xl top-2'></i>
                            <div class="p-3 text-center">
                                <div class="flex justify-center mb-4">

                                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                        class="h-10 w-10 text-green-600">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                            fill="currentColor"></path>
                                    </svg>

                                </div>
                                <div class="flex justify-center mb-2">
                                    <h5 class="text-slate-800 text-2xl font-bold">
                                        A06
                                    </h5>
                                </div>
                                <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                                    4 Seat(s)
                                </p>
                            </div>
                        </div>
                    <?php endwhile ?>
                <?php endif ?>
                <div class="w-full overflow-x-auto rounded-lg border border-slate-200 mt-4">
                    <table class="w-full text-left">
                        <thead
                            class="border-b border-slate-200 bg-slate-100 text-sm font-medium text-slate-600 dark:bg-surface-dark">
                            <tr>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Table Code
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Available Seat
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
                                    Date
                                </th>
                                <th class="px-2.5 py-2 text-start font-medium">
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
                            <tr>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-spotify.svg"
                                            alt="Spotify" />
                                        <small class="font-sans antialiased text-sm text-current font-bold">
                                            Spotify
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        $2,500
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        Wed 3:00pm
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 bg-green-500/10 border-transparent text-green-500 shadow-none">
                                            <span class="font-sans text-current my-0.5 mx-1.5">
                                                paid
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/visa.png"
                                                alt="visa" />
                                        </div>
                                        <div class="flex flex-col">
                                            <small class="font-sans antialiased text-sm text-current capitalize">
                                                visa 1234
                                            </small>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                06/2026
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <button
                                        class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                        <svg class="h-4 w-4" width="1.5em" height="1.5em" viewBox="0 0 24 24"
                                            stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            color="currentColor">
                                            <path
                                                d="M14.3632 5.65156L15.8431 4.17157C16.6242 3.39052 17.8905 3.39052 18.6716 4.17157L20.0858 5.58579C20.8668 6.36683 20.8668 7.63316 20.0858 8.41421L18.6058 9.8942M14.3632 5.65156L4.74749 15.2672C4.41542 15.5993 4.21079 16.0376 4.16947 16.5054L3.92738 19.2459C3.87261 19.8659 4.39148 20.3848 5.0115 20.33L7.75191 20.0879C8.21972 20.0466 8.65806 19.8419 8.99013 19.5099L18.6058 9.8942M14.3632 5.65156L18.6058 9.8942"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-amazon.svg"
                                            alt="Amazon" />
                                        <small class="font-sans antialiased text-sm text-current font-bold">
                                            Amazon
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        $5,000
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        Wed 1:00pm
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 bg-green-500/10 border-transparent text-green-500 shadow-none">
                                            <span class="font-sans text-current my-0.5 mx-1.5">
                                                paid
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/mastercard.png"
                                                alt="master-card" />
                                        </div>
                                        <div class="flex flex-col">
                                            <small class="font-sans antialiased text-sm text-current capitalize">
                                                master card 1234
                                            </small>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                06/2026
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <button
                                        class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                        <svg class="h-4 w-4" width="1.5em" height="1.5em" viewBox="0 0 24 24"
                                            stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            color="currentColor">
                                            <path
                                                d="M14.3632 5.65156L15.8431 4.17157C16.6242 3.39052 17.8905 3.39052 18.6716 4.17157L20.0858 5.58579C20.8668 6.36683 20.8668 7.63316 20.0858 8.41421L18.6058 9.8942M14.3632 5.65156L4.74749 15.2672C4.41542 15.5993 4.21079 16.0376 4.16947 16.5054L3.92738 19.2459C3.87261 19.8659 4.39148 20.3848 5.0115 20.33L7.75191 20.0879C8.21972 20.0466 8.65806 19.8419 8.99013 19.5099L18.6058 9.8942M14.3632 5.65156L18.6058 9.8942"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-pinterest.svg"
                                            alt="Pinterest" />
                                        <small class="font-sans antialiased text-sm text-current font-bold">
                                            Pinterest
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        $3,400
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        Mon 7:40pm
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 bg-warning/10 border-transparent text-amber-500 shadow-none">
                                            <span class="font-sans text-current my-0.5 mx-1.5">
                                                pending
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/mastercard.png"
                                                alt="master-card" />
                                        </div>
                                        <div class="flex flex-col">
                                            <small class="font-sans antialiased text-sm text-current capitalize">
                                                master card 1234
                                            </small>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                06/2026
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <button
                                        class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                        <svg class="h-4 w-4" width="1.5em" height="1.5em" viewBox="0 0 24 24"
                                            stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            color="currentColor">
                                            <path
                                                d="M14.3632 5.65156L15.8431 4.17157C16.6242 3.39052 17.8905 3.39052 18.6716 4.17157L20.0858 5.58579C20.8668 6.36683 20.8668 7.63316 20.0858 8.41421L18.6058 9.8942M14.3632 5.65156L4.74749 15.2672C4.41542 15.5993 4.21079 16.0376 4.16947 16.5054L3.92738 19.2459C3.87261 19.8659 4.39148 20.3848 5.0115 20.33L7.75191 20.0879C8.21972 20.0466 8.65806 19.8419 8.99013 19.5099L18.6058 9.8942M14.3632 5.65156L18.6058 9.8942"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-google.svg"
                                            alt="Google" />
                                        <small class="font-sans antialiased text-sm text-current font-bold">
                                            Google
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        $1,000
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <small class="font-sans antialiased text-sm text-current">
                                        Wed 5:00pm
                                    </small>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 bg-green-500/10 border-transparent text-green-500 shadow-none">
                                            <span class="font-sans text-current my-0.5 mx-1.5">
                                                paid
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/visa.png"
                                                alt="visa" />
                                        </div>
                                        <div class="flex flex-col">
                                            <small class="font-sans antialiased text-sm text-current capitalize">
                                                visa 1234
                                            </small>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                06/2026
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 border-b border-surface-light">
                                    <button
                                        class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                        <svg class="h-4 w-4" width="1.5em" height="1.5em" viewBox="0 0 24 24"
                                            stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            color="currentColor">
                                            <path
                                                d="M14.3632 5.65156L15.8431 4.17157C16.6242 3.39052 17.8905 3.39052 18.6716 4.17157L20.0858 5.58579C20.8668 6.36683 20.8668 7.63316 20.0858 8.41421L18.6058 9.8942M14.3632 5.65156L4.74749 15.2672C4.41542 15.5993 4.21079 16.0376 4.16947 16.5054L3.92738 19.2459C3.87261 19.8659 4.39148 20.3848 5.0115 20.33L7.75191 20.0879C8.21972 20.0466 8.65806 19.8419 8.99013 19.5099L18.6058 9.8942M14.3632 5.65156L18.6058 9.8942"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img class="inline-block object-center w-11 h-11 rounded-md border border-surface-light bg-slate-100 object-contain p-1 dark:bg-surface-dark"
                                            src="https://docs.material-tailwind.com/img/logos/logo-netflix.svg"
                                            alt="netflix" />
                                        <small class="font-sans antialiased text-sm text-current font-bold">
                                            Netflix
                                        </small>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <small class="font-sans antialiased text-sm text-current">
                                        $14,000
                                    </small>
                                </td>
                                <td class="p-4">
                                    <small class="font-sans antialiased text-sm text-current">
                                        Wed 3:30am
                                    </small>
                                </td>
                                <td class="p-4">
                                    <div class="w-max">
                                        <div
                                            class="relative inline-flex w-max items-center border font-sans font-medium rounded-md text-xs p-0.5 bg-red-500/10 border-transparent text-red-500 shadow-none">
                                            <span class="font-sans text-current my-0.5 mx-1.5">
                                                cancelled
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-12 rounded-md border border-surface-light p-1">
                                            <img class="inline-block object-center rounded h-full w-full object-contain p-1"
                                                src="https://demos.creative-tim.com/test/corporate-ui-dashboard/assets/img/logos/visa.png"
                                                alt="visa" />
                                        </div>
                                        <div class="flex flex-col">
                                            <small class="font-sans antialiased text-sm text-current capitalize">
                                                visa 1234
                                            </small>
                                            <small class="font-sans antialiased text-sm text-current opacity-70">
                                                06/2026
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <button
                                        class="inline-grid place-items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md bg-transparent border-transparent text-slate-800 hover:bg-slate-200/10 hover:border-slate-600/10 shadow-none hover:shadow-none outline-none group">
                                        <svg class="h-4 w-4" width="1.5em" height="1.5em" viewBox="0 0 24 24"
                                            stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            color="currentColor">
                                            <path
                                                d="M14.3632 5.65156L15.8431 4.17157C16.6242 3.39052 17.8905 3.39052 18.6716 4.17157L20.0858 5.58579C20.8668 6.36683 20.8668 7.63316 20.0858 8.41421L18.6058 9.8942M14.3632 5.65156L4.74749 15.2672C4.41542 15.5993 4.21079 16.0376 4.16947 16.5054L3.92738 19.2459C3.87261 19.8659 4.39148 20.3848 5.0115 20.33L7.75191 20.0879C8.21972 20.0466 8.65806 19.8419 8.99013 19.5099L18.6058 9.8942M14.3632 5.65156L18.6058 9.8942"
                                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
</script>