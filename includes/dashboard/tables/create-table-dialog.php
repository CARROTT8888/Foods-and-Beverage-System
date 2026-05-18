<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createTable'])) {
    $tableName = $_POST['tableName'] ?? null;
    $branchId = $_POST['branchId'] ?? null;
    $totalSeat = $_POST['totalSeat'] ?? null;
    $status = "Available";

    // auto set available seat same as total seat
    $availableSeat = $totalSeat;

    // check for duplocate table code
    $checkQuery = "SELECT tableId FROM seat_table WHERE tableName = ? AND branchId = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('si', $tableName, $branchId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $errorMessage = 'The table code has already exists.';
        $checkStmt->close();
    } else {
        $checkStmt->close();
        // insert new table (fixed column/value count)
        $query = "INSERT INTO seat_table (tableName, branchId, totalSeat, availableSeat, status) VALUES (?, ?, ?, ?, ?)";
        $addseatstmt = $conn->prepare($query);
        //corrected bind_param types: s = string, i = integer, d = double/decimal\
        $addseatstmt->bind_param('siiis', $tableName, $branchId, $totalSeat, $availableSeat, $status);
        if ($addseatstmt->execute()) {
            echo "<script>window.location.href='/web/dashboard/tables';</script>";
            exit();
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
        $addseatstmt->close();
    }
}
;
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="seatTableDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Create a Table</h1>
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
            <div>
                <label for="tableName" class="block text-sm font-medium text-foreground mb-1">Table Code</label>
                <input type="text" name="tableName" placeholder="Enter a table code"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div>
                <label for="totalSeat" class="block text-sm font-medium text-foreground mb-1">Total Seat</label>
                <input type="number" name="totalSeat" placeholder="Enter a total seat number"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div>
                <label for="branchId" class="block text-sm font-medium text-foreground mb-1">Branch</label>
                <select type="text" id="branchId" name="branchId" placeholder="Enter a table code."
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                    <option value="" selected disabled>Select Branch</option>
                    <?php
                    $branchQuery = "SELECT branchId, name FROM branch";
                    $result = $conn->query($branchQuery);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['branchId']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="createTable"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Create
            </button>
            <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or tab
                'ESC' key to close the dialog.</span>
        </form>
    </div>
</div>