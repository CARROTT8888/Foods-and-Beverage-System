<?php
$branchId = $branch['branchId']; // current branch

$filter = " AND seat_table.branchId = ?";
$params = [$branchId];
$types = "i";

$tableQuery = "SELECT * FROM seat_table WHERE 1" . $filter . " ORDER BY tableId ASC";
$stmt = $conn->prepare($tableQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$tableResult = $stmt->get_result();
if ($tableResult->num_rows > 0):
    while ($data = $tableResult->fetch_assoc()):
        ?>
        <?php if ($data['status'] === 'Available'): ?>
            <div title="Available"
                class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                <div class="p-3 text-center">
                    <div class="flex justify-center mb-4">

                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="currentColor" class="h-10 w-10 text-green-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                fill="currentColor"></path>
                        </svg>

                    </div>
                    <div class="flex justify-center mb-2">
                        <h5 class="text-slate-800 text-2xl font-bold">
                            <?php echo htmlspecialchars($data['tableName']); ?>
                        </h5>
                    </div>
                    <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                        <?php echo $data['availableSeat']; ?> Seat(s)
                    </p>
                </div>
            </div>
        <?php elseif ($data['status'] === 'Occupied'): ?>
            <div title="Occupied"
                class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-red-500  border border-red-500 bg-red-100 shadow-sm rounded-lg p-2">
                <div class="p-3 text-center">
                    <div class="flex justify-center mb-4">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="currentColor" class="h-10 w-10 text-red-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <div class="flex justify-center mb-2">
                        <h5 class="text-slate-800 text-2xl font-bold">
                            <?php echo htmlspecialchars($data['tableName']); ?>
                        </h5>
                    </div>
                    <p class="block text-red-600 leading-normal font-semibold mb-4 max-w-lg">
                        <?php echo $data['availableSeat']; ?> Seat(s)
                    </p>
                </div>
            </div>
        <?php elseif ($data['status'] === 'Dirty'): ?>
            <div title="Dirty"
                class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-amber-500  border border-amber-500 bg-amber-100 shadow-sm rounded-lg p-2">
                <div class="p-3 text-center">
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M3 9h18M3 15h18M3 21h18" />
                            <circle cx="8" cy="7" r="1" fill="currentColor" />
                            <circle cx="12" cy="13" r="1" fill="currentColor" />
                            <circle cx="16" cy="7" r="1" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="flex justify-center mb-2">
                        <h5 class="text-slate-800 text-2xl font-bold">
                            <?php echo htmlspecialchars($data['tableName']); ?>
                        </h5>
                    </div>
                    <p class="block text-amber-600 leading-normal font-semibold mb-4 max-w-lg">
                        <?php echo $data['availableSeat']; ?> Seat(s)
                    </p>
                </div>
            </div>
        <?php elseif ($data['status'] === 'Reserved'): ?>
            <div title="Reserved"
                class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-orange-500  border border-orange-500 bg-orange-100 shadow-sm rounded-lg p-2">
                <div class="p-3 text-center">
                    <div class="flex justify-center mb-4">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="currentColor" class="h-10 w-10 text-orange-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25ZM12.5675 17.5008C12.8446 17.1929 12.8196 16.7187 12.5117 16.4416C12.2038 16.1645 11.7296 16.1894 11.4525 16.4973L11.4425 16.5084C11.1654 16.8163 11.1904 17.2905 11.4983 17.5676C11.8062 17.8447 12.2804 17.8197 12.5575 17.5119L12.5675 17.5008Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <div class="flex justify-center mb-2">
                        <h5 class="text-slate-800 text-2xl font-bold">
                            <?php echo htmlspecialchars($data['tableName']); ?>
                        </h5>
                    </div>
                    <p class="block text-orange-600 leading-normal font-semibold mb-4 max-w-lg">
                        <?php echo $data['availableSeat']; ?> Seat(s)
                    </p>
                </div>
            </div>
        <?php elseif ($data['status'] === 'Blocked'): ?>
            <div title="Blocked"
                class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-slate-500  border border-slate-500 bg-slate-100 shadow-sm rounded-lg p-2">
                <div class="p-3 text-center">
                    <div class="flex justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                        </svg>
                    </div>
                    <div class="flex justify-center mb-2">
                        <h5 class="text-slate-800 text-2xl font-bold">
                            <?php echo htmlspecialchars($data['tableName']); ?>
                        </h5>
                    </div>
                    <p class="block text-slate-600 leading-normal font-semibold mb-4 max-w-lg">
                        <?php echo $data['availableSeat']; ?> Seat(s)
                    </p>
                </div>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>