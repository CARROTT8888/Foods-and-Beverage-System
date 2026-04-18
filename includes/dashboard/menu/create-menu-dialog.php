<?php
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createMenu'])) {

    $foodName = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $basePrice = $_POST['basePrice'] ?? '';
    $visibleStatus = "Invisible";
    $categoryId = intval($_POST['categoryId'] ?? 0);
    $branchId = intval($_POST['branchId'] ?? 0);
    $status = "Available";

    // Validation
    if (empty($foodName) || empty($basePrice) || $categoryId == 0 || $branchId == 0) {
        $errorMessage = "Please fill in all required fields.";
    } else {

        // check duplicate menu name based on branch + category
        $checkQuery = "SELECT foodId FROM food WHERE name = ? AND branchId = ? AND categoryId = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("sii", $foodName, $branchId, $categoryId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errorMessage = "This menu name already exists in the selected branch & category.";
            $checkStmt->close();
        } else {
            $checkStmt->close();

            $query = "INSERT INTO food (name, description, basePrice, status, visibleStatus, categoryId, branchId)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssdssii", $foodName, $description, $basePrice, $status, $visibleStatus, $categoryId, $branchId);

            if ($stmt->execute()) {
                echo "<script>window.location.href='/web/dashboard/menu';</script>";
                exit();
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="menuDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Create a Food or Beverage</h1>
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
                <label for="name" class="block text-sm font-medium text-foreground mb-1">Name</label>
                <input type="text" name="name" placeholder="Enter a name"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-foreground mb-1">Description</label>
                <input type="text" name="description" placeholder="Enter a description"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
            </div>
            <div>
                <label for="basePrice" class="block text-sm font-medium text-foreground mb-1">Base Price (RM)</label>
                <input type="number" name="basePrice" placeholder="Enter a price" step="0.01"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
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
            <div>
                <label for="categoryId" class="block text-sm font-medium text-foreground mb-1">Category</label>
                <select type="text" id="categoryId" name="categoryId" placeholder="Enter a table code."
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                    <option value="" selected disabled>Please Select a Branch First.</option>
                </select>
            </div>
            <?php if (!empty($errorMessage)): ?>
                <p class="text-red-500 text-sm text-center"><?= $errorMessage ?></p>
            <?php endif; ?>
            <button type="submit" name="createMenu"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Create
            </button>
            <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or tab
                'ESC' key to close the dialog.</span>
        </form>
    </div>
</div>
<script>
    document.getElementById("branchId").addEventListener("change", function () {
        const branchId = this.value;
        const categoryDropdown = document.getElementById("categoryId");

        // reset category dropdown
        categoryDropdown.innerHTML = `<option value="" selected disabled>Loading...</option>`;

        fetch("/web/includes/dashboard/menu/get_categories_from_branch.php?branchId=" + branchId)
            .then(response => response.json())
            .then(data => {
                categoryDropdown.innerHTML = `<option value="" selected disabled>Select Category</option>`;

                if (data.length === 0) {
                    categoryDropdown.innerHTML = `<option value="" disabled>No categories available</option>`;
                    return;
                }

                data.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.categoryId;
                    option.textContent = category.name;
                    categoryDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error loading categories:", error);
                categoryDropdown.innerHTML = `<option value="" disabled>Error loading categories</option>`;
            });
    });
</script>