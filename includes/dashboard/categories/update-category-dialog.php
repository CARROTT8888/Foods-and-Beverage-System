<?php
$table = null;

// 1. Load existing data for the form
if (!empty($_GET['categoryId'])) {
    $categoryId = intval($_GET['categoryId']);
    $stmt = $conn->prepare("SELECT * FROM food_category WHERE categoryId = ? AND branchId = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $table = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST['updateCategory'])) {
    $categoryId = intval($_POST['categoryId']);
    $newCategoryName = trim($_POST['name'] ?? '');
    $newBranchId = intval($_POST['branchId'] ?? '');

    $fields = [];
    $params = [];
    $types = "";

    // 2. Validation: Ensure we have the minimum requirements to identify the record
    if (empty($newCategoryName) || empty($newBranchId)) {
        echo "<script>alert('Category Name and Branch are required.');</script>";
    } else {
        // 3. Duplicate check: Is there another category with this name in this branch?
        $checkQuery = "SELECT categoryId FROM food_category WHERE name = ? AND branchId = ? AND categoryId != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("sii", $newCategoryName, $newBranchId, $categoryId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('This table name already exists in this branch.');</script>";
            $checkStmt->close();
        } else {
            $checkStmt->close();

            // 4. Build Dynamic Update
            $fields[] = "name = ?";
            $params[] = $newCategoryName;
            $types .= "s";

            $fields[] = "branchId = ?";
            $params[] = $newBranchId;
            $types .= "i";

            if (!empty($_POST['status'])) {
                $fields[] = "status = ?";
                $params[] = $_POST['status'];
                $types .= "s";
            }

            // 5. Execute Update
            if (!empty($fields)) {
                $sql = "UPDATE food_category SET " . implode(", ", $fields) . " WHERE categoryId = ?";
                $params[] = $categoryId;
                $types .= "i";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param($types, ...$params);

                if ($stmt->execute()) {
                    echo "<script>window.location.href='/web/dashboard/categories';</script>";
                    exit();
                } else {
                    echo "Error updating record: " . $conn->error;
                }
                $stmt->close();
            }
        }
    }
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
            <div class="">
                <div class="">
                    <label for="name"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Name</label>
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

                <div class="mt-6">
                    <label for="branchId" class="block text-sm font-medium text-foreground mb-1 text-start">Status</label>
                    <select type="text" id="updateBranch" name="branchId" placeholder="Enter a table code."
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