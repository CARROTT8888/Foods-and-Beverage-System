<?php
if (!isset($_SESSION['orderId'])) {
    $sql = "INSERT INTO `order` (userId, branchId, totalPrice) VALUES (?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $branchId);
    $stmt->execute();
    $_SESSION['orderId'] = $stmt->insert_id;
    $stmt->close();
} else {
    $sql = "UPDATE `order` SET branchId = ? WHERE orderId = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $branchId, $_SESSION['orderId'], $userId);
    $stmt->execute();
    $stmt->close();
}

$orderId = $_SESSION['orderId'];
$branch = null;

if (isset($_SESSION['branchId'])) {
    $branchId = $_SESSION['branchId'];

    $stmtBranch = $conn->prepare("SELECT * FROM branch WHERE branchId=?");
    $stmtBranch->bind_param("i", $branchId);
    $stmtBranch->execute();
    $branchResult = $stmtBranch->get_result();
    $branch = $branchResult->fetch_assoc();
    $stmtBranch->close();
}

if (!$branch) {
    return;
}

$branchId = $branch['branchId'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['selectMethod'])) {
    $methodName = trim($_POST['methodName']);
    $tableId = $_POST['tableId'] ?? null;

    $allowedMethods = ['Dine In', 'Take Away', 'Delivery'];
    if (!in_array($methodName, $allowedMethods)) {
        header("Location: order-method.php");
        exit();
    }

    $stmtMethod = $conn->prepare("SELECT methodId FROM order_method WHERE methodName = ? AND branchId = ? AND isEnabled = 1");
    $stmtMethod->bind_param("si", $methodName, $branchId);
    $stmtMethod->execute();
    $methodResult = $stmtMethod->get_result();
    $methodRow = $methodResult->fetch_assoc();
    $stmtMethod->close();

    if (!$methodRow) {
        die("Method not found.");
    }

    $methodId = $methodRow['methodId'];
    $orderId = $_SESSION['orderId'];

    if ($tableId) {
        $sql = "UPDATE `order` SET methodId = ?, tableId = ? WHERE orderId = ? AND userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $methodId, $tableId, $orderId, $userId);
    } else {
        $sql = "UPDATE `order` SET methodId = ?, tableId = NULL WHERE orderId = ? AND userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $methodId, $orderId, $userId);
    }

    $stmt->execute();
    $stmt->close();

    $_SESSION['methodId'] = $methodId;

    echo "<script>window.location.href='/web/menu';</script>";
    exit();
}

$stmt = $conn->prepare("SELECT isEnabled FROM order_method WHERE branchId=? AND methodName=?");
$dineinName = "Dine In";
$stmt->bind_param("is", $branchId, $dineinName);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
$dineinEnabled = ($data && $data['isEnabled'] == 1);
$stmt = $conn->prepare("SELECT isEnabled FROM order_method WHERE branchId=? AND methodName=?");
$takeawayName = "Take Away";
$stmt->bind_param("is", $branchId, $takeawayName);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
$takeawayEnabled = ($data && $data['isEnabled'] == 1);
$stmt = $conn->prepare("SELECT isEnabled FROM order_method WHERE branchId=? AND methodName=?");
$deliveryName = "Delivery";
$stmt->bind_param("is", $branchId, $deliveryName);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();
$deliveryEnabled = ($data && $data['isEnabled'] == 1);
?>

<section
    class="relative overflow-x-hidden bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8">
    <?php include 'header.php'; ?>
    <div class="mt-4 flex justify-center flex-col">
        <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8">What do you prefer
            to
            order?</h1>
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mx-auto items-center px-4 sm:px-6 lg:px-8 max-w-7xl">

            <?php if ($dineinEnabled): ?>
                <button type="button" onclick="openDialog()"
                    class="cursor-pointer rounded-lg hover:h-[31rem] hover:shadow-amber-400 hover:shadow-2xl transition-all duration-300 xl:hover:w-[400px] border shadow-sm bg-white border-slate-200 shadow-slate-950/5 relative flex h-[30rem] w-full max-w-md flex-col items-end justify-center overflow-hidden text-center pb-12 pt-20">
                    <div class="p-2">
                        <div class="absolute inset-0 m-0 h-full w-full rounded-none bg-primary bg-right">
                            <div
                                class="absolute inset-0 h-full w-full bg-linear-to-t from-slate-400/80 via-yellow-200 to-slate-300/10">
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full rounded relative bottom-0 flex h-full flex-col items-start justify-center px-6 py-14 md:px-12">
                        <i class='bx bx-fork text-9xl'></i>
                        <h4 class="font-sans antialiased font-bold text-xl md:text-2xl lg:text-3xl text-black">
                            Book A Seat
                        </h4>
                        <p class="mt-4 max-w-[26rem] text-left text-base/6 text-secondaryForeground">Reserve a table to eat
                            inside the branch.</p>
                    </div>
                </button>
                <?php include 'seat-selection-dialog.php'; ?>
            <?php else: ?>
                <button disabled title="Locked, not available now."
                    class="cursor-not-allowed rounded-lg transition-all duration-300 border shadow-sm bg-white border-slate-200 shadow-slate-950/5 relative flex h-[30rem] w-full max-w-md flex-col items-end justify-center overflow-hidden text-center pb-12 pt-20">
                    <div class="p-2">
                        <div class="absolute inset-0 m-0 h-full w-full rounded-none bg-primary bg-right">
                            <div
                                class="absolute inset-0 h-full w-full bg-linear-to-t from-slate-400/80 via-yellow-500 to-slate-600/10">
                            </div>
                            <i class="bx bxs-lock text-2xl mt-2 flex justify-end mr-2"></i>
                        </div>
                    </div>
                    <div
                        class="w-full rounded relative bottom-0 flex h-full flex-col items-start justify-center px-6 py-14 md:px-12">
                        <i class='bx bx-fork text-9xl'></i>
                        <h4 class="font-sans antialiased font-bold text-xl md:text-2xl lg:text-3xl text-black">
                            Book A Seat
                        </h4>
                        <p class="mt-4 max-w-[26rem] text-left text-base/6 text-secondaryForeground">Reserve a table to eat
                            inside the branch.</p>
                    </div>
                </button>
            <?php endif; ?>

            <?php if ($takeawayEnabled): ?>
                <button onclick="submitMethod('Take Away')"
                    class="cursor-pointer rounded-lg hover:h-[31rem] hover:shadow-amber-400 hover:shadow-2xl transition-all duration-300 xl:hover:w-[400px] border shadow-sm bg-white border-slate-200 shadow-slate-950/5 relative flex h-[30rem] w-full max-w-md flex-col items-end justify-center overflow-hidden text-center pb-12 pt-20">
                    <div class="p-2">
                        <div class="absolute inset-0 m-0 h-full w-full rounded-none bg-primary bg-right">
                            <div
                                class="absolute inset-0 h-full w-full bg-linear-to-t from-slate-400/80 via-yellow-200 to-slate-300/10">
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full rounded relative bottom-0 flex h-full flex-col items-start justify-center px-6 py-14 md:px-12">
                        <i class='bx bxs-shopping-bag-alt text-9xl'></i>
                        <h4 class="font-sans antialiased font-bold text-xl md:text-2xl lg:text-3xl text-black">
                            Take-Away
                        </h4>
                        <p class="mt-4 max-w-[26rem] text-left text-base/6 text-secondaryForeground">Order your food and
                            pick it
                            up at the restaurant. Save your time wating.</p>
                    </div>
                </button>
            <?php else: ?>
                <button disabled title="Locked, not available now."
                    class="cursor-not-allowed rounded-lg transition-all duration-300 border shadow-sm bg-white border-slate-200 shadow-slate-950/5 relative flex h-[30rem] w-full max-w-md flex-col items-end justify-center overflow-hidden text-center pb-12 pt-20">
                    <div class="p-2">
                        <div class="absolute inset-0 m-0 h-full w-full rounded-none bg-primary bg-right">
                            <div
                                class="absolute inset-0 h-full w-full bg-linear-to-t from-slate-400/80 via-yellow-500 to-slate-600/10">
                            </div>
                            <i class="bx bxs-lock text-2xl mt-2 flex justify-end mr-2"></i>
                        </div>
                    </div>
                    <div
                        class="w-full rounded relative bottom-0 flex h-full flex-col items-start justify-center px-6 py-14 md:px-12">
                        <i class='bx bxs-shopping-bag-alt text-9xl'></i>
                        <h4 class="font-sans antialiased font-bold text-xl md:text-2xl lg:text-3xl text-black">
                            Take-Away
                        </h4>
                        <p class="mt-4 max-w-[26rem] text-left text-base/6 text-secondaryForeground">Order your food and
                            pick it
                            up at the restaurant. Save your time wating.</p>
                    </div>
                </button>
            <?php endif; ?>
            <?php if ($deliveryEnabled): ?>
                <button onclick="submitMethod('Delivery')"
                    class="cursor-pointer rounded-lg hover:h-[31rem] hover:shadow-amber-400 hover:shadow-2xl transition-all duration-300 xl:hover:w-[400px] border shadow-sm bg-white border-slate-200 shadow-slate-950/5 relative flex h-[30rem] w-full max-w-md flex-col items-end justify-center overflow-hidden text-center pb-12 pt-20">
                    <div class="p-2">
                        <div class="absolute inset-0 m-0 h-full w-full rounded-none bg-primary bg-right">
                            <div
                                class="absolute inset-0 h-full w-full bg-linear-to-t from-slate-400/80 via-yellow-200 to-slate-300/10">
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-full rounded relative bottom-0 flex h-full flex-col items-start justify-center px-6 py-14 md:px-12">
                        <i class='bx bxs-map-pin text-9xl'></i>
                        <h4 class="font-sans antialiased font-bold text-xl md:text-2xl lg:text-3xl text-black">
                            Delivery
                        </h4>
                        <p class="mt-4 max-w-[26rem] text-left  text-base/6 text-secondaryForeground">Enjoy your meals at
                            home
                            with our reliable delivery service, right to your location.</p>
                    </div>
                </button>
            <?php else: ?>
                <button disabled title="Locked, not available now."
                    class="cursor-not-allowed rounded-lg transition-all text-secondaryForeground duration-300 border shadow-sm bg-secondary border-slate-200 shadow-slate-950/5 relative flex h-[30rem] w-full max-w-md flex-col items-end justify-center overflow-hidden text-center pb-12 pt-20">
                    <div class="p-2">
                        <div class="absolute inset-0 m-0 h-full w-full rounded-none">
                            <div
                                class="absolute inset-0 h-full w-full">
                            </div>
                            <i class="bx bxs-lock text-2xl mt-2 flex justify-end mr-2"></i>
                        </div>
                    </div>
                    <div
                        class="w-full rounded relative bottom-0 flex h-full flex-col items-start justify-center px-6 py-14 md:px-12">
                        <i class='bx bxs-map-pin text-9xl'></i>
                        <h4 class="font-sans antialiased font-bold text-xl md:text-2xl lg:text-3xl text-secondaryForeground">
                            Delivery
                        </h4>
                        <p class="mt-4 max-w-[26rem] text-left  text-base/6 text-secondaryForeground">Not Available Now</p>
                    </div>
                </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    function submitMethod(method) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'order-method.php';
        form.innerHTML = `
        <input type="hidden" name="methodName" value="${method}">
        <input type="hidden" name="selectMethod" value="1">
    `;
        document.body.appendChild(form);
        form.submit();
    }
</script>