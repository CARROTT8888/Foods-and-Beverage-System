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
?>

<section
    class="relative overflow-auto bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8">
    <div class="items-center">
        <button onclick="window.location.href='order-location.php?updateLocation=1'"
            class="flex w-auto gap-2 justify-center items-center border font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full focus:shadow-none text-sm rounded-md py-2 px-4 bg-transparent border-transparent text-slate-800 hover:bg-accent hover:text-accentForeground shadow-none hover:shadow-none">
            <i class='bx bx-chevron-left text-2xl'></i>
            Update Location
        </button>
    </div>
    <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8">What do you prefer to
        order?</h1>
    <div class="flex lg:flex-row flex-col justify-center gap-4 mx-auto items-center px-4 sm:px-6 lg:px-8 min-w-full">

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
            </div>
        </button>
        <?php include 'seat-selection-dialog.php'; ?>

        <div
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
            </div>
        </div>
        <div
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
            </div>
        </div>
    </div>
</section>