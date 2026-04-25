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
        <div
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
    <?php endwhile; ?>
<?php endif; ?>