<?php
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createFoodOption'])) {

    $foodId = intval($_POST['foodId'] ?? 0);
    $groupName = trim($_POST['groupName'] ?? '');
    $itemName = $_POST['itemName'] ?? [];
    $extraPrice = $_POST['extraPrice'] ?? [];

    $validItems = false;
    foreach ($itemName as $name) {
        if (trim($name) !== '') {
            $validItems = true;
            break;
        }
    }

    if (empty($groupName) || empty($foodId) || !$validItems) {
        $errorMessage = "Please fill in all required fields.";
    }

    if (empty($errorMessage)) {
        $conn->begin_transaction();

        try {
            $existingGroupId = null;
            $checkQuery = "SELECT optionGroupId FROM food_option_group WHERE groupName = ? AND foodId = ?";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("si", $groupName, $foodId);
            $checkStmt->execute();
            $checkStmt->bind_result($existingGroupId);
            $checkStmt->fetch();
            $checkStmt->close();

            if ($existingGroupId) {
                $newGroupId = $existingGroupId;
            } else {
                $groupQuery = "INSERT INTO food_option_group (groupName, foodId) VALUES (?, ?)";
                $stmt = $conn->prepare($groupQuery);
                $stmt->bind_param("si", $groupName, $foodId);
                $stmt->execute();
                $newGroupId = $conn->insert_id;
                $stmt->close();
            }

            $itemQuery = "INSERT INTO food_option_item (itemName, extraPrice, optionGroupId) VALUES (?, ?, ?)";
            $itemStmt = $conn->prepare($itemQuery);

            foreach ($itemName as $index => $name) {
                $name = trim($name);
                if ($name === '') continue; // skip this step

                $price = (isset($extraPrice[$index]) && $extraPrice[$index] !== '') ? floatval($extraPrice[$index]) : 0;
                
                $itemStmt->bind_param("sdi", $name, $price, $newGroupId);
                $itemStmt->execute();
            }

            $itemStmt->close();
            $conn->commit();

            echo "<script>window.location.href='/web/dashboard/menu';</script>";
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $errorMessage = "Transaction failed: " . $e->getMessage();
        }
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="foodOptionDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Add Food Options</h1>
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
            <input type="hidden" name="foodId" id="modalFoodId" value="">
            <div>
                <label for="groupName" class="block text-sm font-medium text-foreground mb-1">Group Name (e.g. Spicy
                    Level)</label>
                <input type="text" name="groupName" placeholder="Enter group name"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div class="space-y-2" id="itemContainer">
                <label for="" class="block text-sm font-medium text-foreground mb-1">Items' Name and Extra
                    Prices</label>
                <div class="flex gap-2">
                    <input type="text" name="itemName[]" placeholder="Item (e.g. Normal)"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                        required />
                    <input type="number" name="extraPrice[]" step="0.01" placeholder="Extra Prices (RM)"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                        required />
                </div>
            </div>
            <button type="button" onclick="addNewItemRow()" class="text-xs text-blue-600 hover:underline">+ Add Another
                Item</button>

            <button type="submit" name="createFoodOption"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Create
            </button>
            <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or tab
                'ESC' key to close the dialog.</span>
        </form>
    </div>
</div>

<script>
    function addNewItemRow() {
        const container = document.getElementById('itemContainer');
        const div = document.createElement('div');
        div.className = 'flex gap-2 items-center';
        div.innerHTML = `
        <input type="text" name="itemName[]" placeholder="Item (e.g. Normal)"
            class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
        <input type="number" name="extraPrice[]" step="0.01" min="0" placeholder="Extra Price (RM)"
            class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
        <button type="button" onclick="this.parentElement.remove()"
            class="text-red-400 hover:text-red-600 text-lg font-bold px-1">✕</button>
    `;
        container.appendChild(div);
    }
</script>