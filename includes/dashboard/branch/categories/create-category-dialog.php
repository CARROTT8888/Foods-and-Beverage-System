<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['updateCategory'])) {
    $categoryName = $_POST['name'];
    $branchId = $_POST['branchId'];
    $status = $_POST['status'];

    // check for duplocate table code
    $checkQuery = "SELECT branchId FROM food_category WHERE name = ? AND branchId = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('si', $categoryName, $branchId);
    $checkStmt->execute();
    $checkStmt->store_result();

    // check for category's data based on slug from branch
    $slugQuery = "SELECT slug FROM branch WHERE branchId = ?";
    $slugStmt = $conn->prepare($slugQuery);
    $slugStmt->bind_param("i", $branchId);
    $slugStmt->execute();
    $slugResult = $slugStmt->get_result();
    $row = $slugResult->fetch_assoc();
    $slug = $row['slug'];
    $slugStmt->close();

    if ($checkStmt->num_rows > 0) {
        $errorMessage = 'The category name has already exists.';
        $checkStmt->close();
    } else {
        $checkStmt->close();
        // insert new category (fixed column/value count)
        $query = "INSERT INTO food_category (name, branchId, status) VALUES (?, ?, ?)";
        $addcategorystmt = $conn->prepare($query);
        //corrected bind_param types: s = string, i = integer, d = double/decimal\
        $addcategorystmt->bind_param('sis', $categoryName, $branchId, $status);
        if ($addcategorystmt->execute()) {
            echo "<script>window.location.href = 'bcategories?slug=" . $branch['slug'] . "';</script>";
            exit();
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
        $addcategorystmt->close();
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="categoryDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Create a Category</h1>
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
            <input type="hidden" name="branchId" value="<?php echo $branch['branchId']; ?>">
            <div>
                <label for="name" class="block text-sm font-medium text-foreground mb-1">Name</label>
                <input type="text" name="name" placeholder="Enter a tcategory name"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-foreground mb-1">Status</label>
                <select type="text" id="status" name="status"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                    <option value="" selected disabled>Select Status</option>
                    <option value="Visible">Visible</option>
                    <option value="Invisible">Invisible</option>
                    <option value="Deprecated">Deprecated</option>
                </select>
            </div>
            <button type="submit"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Create
            </button>
            <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or tab
                'ESC' key to close the dialog.</span>
        </form>
    </div>
</div>