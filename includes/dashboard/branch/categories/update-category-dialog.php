<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateCategory'])) {

    $categoryId = $_POST['categoryId'];
    $branchId = $_POST['branchId'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Update query
    $updateQuery = "UPDATE food_category
                    SET name = ?, status = ?
                    WHERE categoryId = ? AND branchId = ?";

    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssii", $name, $status, $categoryId, $branchId);

    if ($updateStmt->execute()) {
        echo "<script>window.location.href = 'bcategories?slug=" . $branch['slug'] . "';</script>";
        exit();
    } else {
        $errorMessage = "Update failed: " . $conn->error;
    }

    $updateStmt->close();
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateCategoryDialog">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
        <form method="POST">
            <div class="flex justify-between mb-4">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Update a Category</h1>
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
            <input type="hidden" name="categoryId" id="categoryId">
            <input type="hidden" name="updateCategory" value="1">
            <input type="hidden" id="branchId" name="branchId">
            <div class="">
                <div class="">
                    <label for="name" class="block text-sm font-medium text-foreground mb-1 text-start">Name</label>
                    <input type="text" name="name" id="updateCategoryName"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="status" class="block text-sm font-medium text-foreground mb-1 text-start">Status</label>

                    <select name="status" id="updateStatus"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                        <option selected disabled value="">Choose a Status</option>
                        <option value="Visible">Visible</option>
                        <option value="Invisible">Invisible</option>
                        <option value="Deprecated">Deprecated</option>
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