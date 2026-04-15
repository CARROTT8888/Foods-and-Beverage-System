<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateTable'])) {

    $tableId = $_POST['tableId'];
    $branchId = $_POST['branchId'];
    $tableName = $_POST['tableName'];
    $totalSeat = $_POST['totalSeat'];
    $availableSeat = $_POST['availableSeat'];
    $status = $_POST['status'];

    // Update query
    $updateQuery = "UPDATE seat_table
                    SET tableName = ?, totalSeat = ?, availableSeat = ?, status = ?
                    WHERE tableId = ? AND branchId = ?";

    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("siisii", $tableName, $totalSeat, $availableSeat, $status, $tableId, $branchId);

    if ($updateStmt->execute()) {
        echo "<script>window.location.href = 'btables?slug=" . $branch['slug'] . "';</script>";
        exit();
    } else {
        $errorMessage = "Update failed: " . $conn->error;
    }

    $updateStmt->close();
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateTableDialog">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
        <form method="POST" action="btables.php?slug=<?php echo htmlspecialchars($branch['slug']); ?>">
            <div class="flex justify-between mb-4">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Update a Table</h1>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none data-[shape=circular]:rounded-full text-sm min-w-[34px] min-h-[34px] rounded-md bg-transparent border-transparent text-red-500 hover:bg-red-200/10 hover:border-red-200/10 shadow-none hover:shadow-none outline-none">
                    <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5">
                        <path
                            d="M6.75827 17.2426L12.0009 12M17.2435 6.75736L12.0009 12M12.0009 12L6.75827 6.75736M12.0009 12L17.2435 17.2426"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="updateTable" value="1">
            <input type="hidden" id="tableId" name="tableId">
            <input type="hidden" id="branchId" name="branchId">
            <div class="">
                <div class="">
                    <label for="tableName"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Name</label>
                    <input type="text" name="tableName" id="updateTableName"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="totalSeat" class="block text-sm font-medium text-foreground mb-1 text-start">Total
                        Seat</label>
                    <input type="number" name="totalSeat" id="updateTotalSeat" class="border p-2 w-full">
                </div>

                <div class="mt-6">
                    <label for="availableSeat"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Available Seat</label>
                    <input type="number" name="availableSeat" id="updateAvailableSeat" class="border p-2 w-full">
                </div>

                <div class="mt-6">
                    <label for="status" class="block text-sm font-medium text-foreground mb-1 text-start">Status</label>
                    <select name="status" id="updateStatus"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                        <option selected disabled value="">Choose a Status</option>
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                        <option value="Reserved">Reserved</option>
                        <option value="Dirty">Dirty</option>
                        <option value="Blocked">Blocked</option>
                    </select>
                </div>
            </div>
            <div class="mt-6">
                <button type="submit"
                    class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">Update</button>
                <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or
                    tab 'ESC' key to close the dialog.</span>
            </div>
        </form>
    </div>
</div>