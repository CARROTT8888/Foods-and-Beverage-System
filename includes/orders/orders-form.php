<?php
$order = null;

// 1. Load existing data for the form
if (!empty($_GET['orderId'])) {
    $orderId = intval($_GET['orderId']);
    $stmtOrder = $conn->prepare("SELECT * FROM `order` WHERE orderId = ?");
    $stmtOrder->bind_param("i", $orderId);
    $stmtOrder->execute();
    $orderResult = $stmtOrder->get_result();
    $order = $orderResult->fetch_assoc();
    $stmtOrder->close();
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updateOrderDetails'])) {
    $orderId = intval($_POST['orderId']);
    $fields = [];
    $params = [];
    $types = "";

    if (isset($_POST['extraNote'])) {
        $fields[] = "extraNote=?";
        $params[] = trim($_POST['extraNote']);
        $types .= "s";
    }

    // executing validation only for users who selected method delivery
    if ($orderRow['methodName'] === 'Delivery') {
        $deliveryAddress = trim($_POST['deliveryAddress'] ?? '');
        $deliveryState = trim($_POST['deliveryState'] ?? '');
        $deliveryPostalCode = trim($_POST['deliveryPostalCode'] ?? '');
        $deliveryDistrict = trim($_POST['deliveryDistrict'] ?? '');

        if (empty($deliveryAddress) || empty($deliveryState) || empty($deliveryPostalCode) || empty($deliveryDistrict)) {
            $errorMessage = "Please Fill In All Delivery Details.";
        } else {
            $fields[] = "deliveryAddress=?";
            $params[] = $deliveryAddress;
            $types .= "s";

            $fields[] = "deliveryState=?";
            $params[] = $deliveryState;
            $types .= "s";

            $fields[] = "deliveryPostalCode=?";
            $params[] = $deliveryPostalCode;
            $types .= "i";

            $fields[] = "deliveryDistrict=?";
            $params[] = $deliveryDistrict;
            $types .= "s";
        }
    }

    if (empty($errorMessage) && !empty($fields)) {
        $sql = "UPDATE `order` SET " . implode(', ', $fields) . " WHERE orderId=?";
        $params[] = $orderId;
        $types .= "i";

        $stmtOrder = $conn->prepare($sql);
        $stmtOrder->bind_param($types, ...$params);

        if ($stmtOrder->execute()) {
            $stmtOrder->close();
            echo "<script>window.location.href='/web/checkout';</script>";
            exit();
        }

        $stmtOrder->close();
    }
}
?>

<input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
<input type="hidden" name="updateOrderDetails" value="1">
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 mb-6 md:col-span-1 col-span-2">
    <h2 class="font-bold text-slate-700 mb-4">Complete your Order(s)</h2>
    <div class="">
        <label for="extraNote" class="block text-sm font-medium text-foreground mb-1 text-start">Extra
            Note</label>
        <input type="text" name="extraNote" id="updateExtraNote"
            value="<?php echo htmlspecialchars($orderRow['extraNote'] ?? ''); ?>"
            placeholder="If you don't have any extra idea, just leave it."
            class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
    </div>

    <?php if ($orderRow['methodName'] === 'Delivery'): ?>
        <div class="mt-6">
            <label for="deliveryAddress" class="block text-sm font-medium text-foreground mb-1 text-start">Delivery
                Address <span class="text-destructive">*</span></label>
            <input type="text" name="deliveryAddress" id="updateDeliveryAddress" required
                value="<?php echo htmlspecialchars($orderRow['deliveryAddress'] ?? ''); ?>"
                class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
        </div>

        <div class="mt-6 flex justify-between gap-2">
            <div class="w-full">
                <label for="deliveryState" class="block text-sm font-medium text-foreground mb-1 text-start">Delivery
                    State <span class="text-destructive">*</span></label>
                <select name="deliveryState" id="updateDeliveryState" required
                    class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                    <option selected disabled value="">Choose a State</option>
                    <?php
                    $states = ["Johor", "Kedah", "Kelantan", "Melaka", "Negeri Sembilan", "Pahang", "Perak", "Perlis", "Pulau Pinang", "Sabah", "Sarawak", "Selangor", "Terengganu"];
                    foreach ($states as $state):
                        ?>
                        <option value="<?php echo $state; ?>" <?php echo (($orderRow['deliveryState'] ?? '') === $state) ? 'selected' : ''; ?>>
                            <?php echo $state; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full">
                <label for="deliveryPostalCode" class="block text-sm font-medium text-foreground mb-1 text-start">
                    Delivery Postal Code <span class="text-destructive">*</span>
                </label>
                <input type="number" name="deliveryPostalCode" id="updateDeliveryPostalCode" required
                value="<?php echo htmlspecialchars($orderRow['deliveryPostalCode'] ?? ''); ?>"
                    class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
            </div>
            <div class="w-full">
                <label for="deliveryDistrict" class="block text-sm font-medium text-foreground mb-1 text-start">
                    Delivery District <span class="text-destructive">*</span>
                </label>
                <input type="text" name="deliveryDistrict" id="updateDeliveryDistrict" required
                    value="<?php echo htmlspecialchars($orderRow['deliveryDistrict'] ?? ''); ?>"
                    class="w-full border-2 border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
            </div>
        </div>
    <?php endif; ?>
</div>