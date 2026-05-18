<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggleMethod'])) {
    $methodId = intval($_POST['methodId']);
    $isEnabled = intval($_POST['isEnabled']);

    $updateMethodTogglesql = "UPDATE order_method SET isEnabled = ? WHERE methodId = ?";
    $stmt = $conn->prepare($updateMethodTogglesql);
    $stmt->bind_param("ii", $isEnabled, $methodId);
    $stmt->execute();
    $stmt->close();

    echo "<script>window.location.href='/web/dashboard/order-method';</script>";
    exit();
}

$branchQuery = "SELECT b.branchId, b.name, b.status, b.slug,
    GROUP_CONCAT(om.methodId ORDER BY om.methodId) as methodIds,
    GROUP_CONCAT(om.methodName ORDER BY om.methodId) as methodNames,
    GROUP_CONCAT(om.isEnabled ORDER BY om.methodId) as methodStatuses
    FROM branch b
    LEFT JOIN order_method om ON b.branchId = om.branchId
    GROUP BY b.branchId
    ORDER BY b.branchId ASC";
$branchResult = $conn->query($branchQuery);
?>

<section
    class="relative overflow-y-scroll h-screen flex flex-col bg-linear-to-b from-blue-50 via-transparent to-transparent pb-12 pt-8 w-full">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8 w-full">
            <!-- Sidebar -->
            <!---<button class="text-gray-500 hover:text-gray-600" id="open-sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>--->
            <button type="button" data-toggle="modal" data-target="#sidebarDrawer"
                class="text-gray-500 hover:text-gray-600">
                <span class="lg:hidden flex font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </span>
            </button>
            <?php include 'drawer.php'; ?>
            Method
        </h1>
    </div>
    <div class="flex sm:items-center flex-wrap">
        <!-- Dropdown Container -->
        <div
            class="w-full text-center grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 space-y-0 max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8 mb-10">
            <?php while ($branch = $branchResult->fetch_assoc()):
                $methodIds = explode(',', $branch['methodIds']);
                $methodNames = explode(',', $branch['methodNames']);
                $methodStatuses = explode(',', $branch['methodStatuses']);

                $icons = ['Dine In' => 'bx-fork', 'Take Away' => 'bxs-shopping-bag-alt', 'Delivery' => 'bxs-map-pin'];
                ?>
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
                    <!-- Branch Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>" class="hover:underline">
                                <h2 class="font-bold text-lg text-slate-800">
                                    <?php echo htmlspecialchars($branch['name']); ?>
                                </h2>
                            </a>
                            <?php if ($branch['status'] === 'Opening'): ?>
                                <span
                                    class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-600 border border-green-300">Opening</span>
                            <?php elseif ($branch['status'] === 'Closed'): ?>
                                <span
                                    class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600 border border-red-300">Closed</span>
                            <?php else: ?>
                                <span
                                    class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-300"><?php echo $branch['status']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Method Toggles -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <?php foreach ($methodIds as $i => $methodId):
                            $name = $methodNames[$i];
                            $enabled = $methodStatuses[$i] == 1;
                            $icon = $icons[$name] ?? 'bx-circle';
                            $newStatus = $enabled ? 0 : 1;
                            ?>
                            <form method="POST" class="w-full">
                                <input type="hidden" name="toggleMethod" value="1">
                                <input type="hidden" name="methodId" value="<?php echo $methodId; ?>">
                                <input type="hidden" name="isEnabled" value="<?php echo $newStatus; ?>">

                                <button type="submit" class="w-full flex items-center justify-center rounded-lg border px-4 py-3 transition-all duration-300
        <?php echo $enabled ? 'bg-amber-50 border-amber-300' : 'bg-slate-50 border-slate-200'; ?>">

                                    <div class="flex items-center gap-3">
                                        <i class="bx <?php echo $icon; ?> text-2xl
            <?php echo $enabled ? 'text-amber-500' : 'text-slate-400'; ?>"></i>
                                    </div>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endwhile; ?>


        </div>
</section>


<script>
    function toggleMethod(methodId, newStatus, btn) {
        fetch(window.location.href, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `toggleMethod=1&methodId=${methodId}&isEnabled=${newStatus}`
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const icon = btn.querySelector('i');

                    if (newStatus == 1) {
                        btn.classList.remove('bg-slate-50', 'border-slate-200');
                        btn.classList.add('bg-amber-50', 'border-amber-300');
                        icon.classList.remove('text-slate-400');
                        icon.classList.add('text-amber-500');
                        btn.setAttribute('onclick', `toggleMethod(${methodId}, 0, this)`);
                    } else {
                        btn.classList.remove('bg-amber-50', 'border-amber-300');
                        btn.classList.add('bg-slate-50', 'border-slate-200');
                        icon.classList.remove('text-amber-500');
                        icon.classList.add('text-slate-400');
                        btn.setAttribute('onclick', `toggleMethod(${methodId}, 1, this)`);
                    }
                }
            })
            .catch(err => console.error(err));
    }
</script>