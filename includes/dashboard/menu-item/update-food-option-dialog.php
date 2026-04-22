<?php
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateFoodOption'])) {
    $optionGroupId = intval($_POST['optionGroupId'] ?? 0);
    $groupName = trim($_POST['groupName'] ?? '');
    $itemId = $_POST['optionItemId'] ?? [];
    $itemNames = $_POST['itemName'] ?? [];
    $extraPrices = $_POST['extraPrice'] ?? [];

    if ($optionGroupId <= 0 || empty($groupName)) {
        $errorMessage = "Group name cannot be empty";
    }

    if (!$errorMessage) {
        $conn->begin_transaction();

        try {
            // update group name
            $updateGroupQuery = "UPDATE food_option_group SET groupName=? WHERE optionGroupId=?";
            $stmtGroup = $conn->prepare($updateGroupQuery);
            $stmtGroup->bind_param("si", $groupName, $optionGroupId);
            $stmtGroup->execute();
            $stmtGroup->close();

            // get existing item ids from database
            $existingItems = [];
            $fetchQuery = "SELECT optionItemId FROM food_option_item WHERE optionGroupId=?";
            $fetchStmt = $conn->prepare($fetchQuery);
            $fetchStmt->bind_param("i", $optionGroupId);
            $fetchStmt->execute();
            $result = $fetchStmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $existingItems[] = $row['optionItemId'];
            }
            $fetchStmt->close();

            // track submitted id that exists
            $submittedExistingIds = [];

            // update existing items/insert new items
            $updateItemQuery = "UPDATE food_option_item SET itemName=?, extraPrice=? WHERE optionItemId=? AND optionGroupId=?";
            $updateStmt = $conn->prepare($updateItemQuery);

            $insertItemQuery = "INSERT INTO food_option_item (itemName, extraPrice, optionGroupId) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertItemQuery);
            foreach ($itemNames as $index => $name) {
                $name = trim($name);
                $price = isset($extraPrices[$index]) && $extraPrices[$index] !== '' ? floatval($extraPrices[$index]) : 0;

                if ($name === '')
                    continue;

                $itemId = isset($itemIds[$index]) ? intval($itemIds[$index]) : 0;

                if ($itemId > 0) {
                    // update existing
                    $submittedExistingIds[] = $itemId;
                    $updateStmt->bind_param("sdii", $name, $price, $itemId, $optionGroupId);
                    $updateStmt->execute();
                } else {
                    // insert new
                    $insertStmt->bind_param("sdi", $name, $price, $optionGroupId);
                    $insertStmt->execute();
                }
            }

            $updateStmt->close();
            $insertStmt->close();

            // delete removed items
            $itemsToDelete = array_diff($existingItems, $submittedExistingIds);

            if (!empty($itemsToDelete)) {
                $placeholders = implode(',', array_fill(0, count($itemsToDelete), '?'));
                $deleteQuery = "DELETE FROM food_option_item WHERE optionItemId IN ($placeholders) AND optionGroupId=?";

                $deleteStmt = $conn->prepare($deleteQuery);

                $types = str_repeat("i", count($itemsToDelete)) . "i";
                $params = array_merge($itemsToDelete, [$optionGroupId]);

                $deleteStmt->bind_param($types, ...$params);
                $deleteStmt->execute();
                $deleteStmt->close();
            }

            $conn->commit();

            echo "<script>window.location.href='/web/dashboard/menu';</script>";
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            $errorMessage = "Update failed: " . $e->getMessage();
        }
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateFoodOptionDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <div class="flex justify-between items-center">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Update Food Options</h1>
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
            <input type="hidden" name="updateFoodOption" value="1">
            <input type="hidden" name="optionGroupId" id="updateOptionGroupId">
            <div>
                <label for="groupName" class="block text-sm font-medium text-foreground mb-1">Group Name (e.g. Spicy
                    Level)</label>
                <input type="text" name="groupName" id="updateGroupName" placeholder="Enter group name"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div class="space-y-2" id="updateItemContainer">
                <label class="block text-sm font-medium mb-1">Items</label>
            </div>
            <button type="button" onclick="addNewItemRow()" class="text-xs text-blue-600 hover:underline">+ Add Another
                Item</button>

            <button type="submit"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Update
            </button>
            <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or tab
                'ESC' key to close the dialog.</span>
        </form>
    </div>
</div>