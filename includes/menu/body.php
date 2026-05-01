<?php
if (isset($_SESSION['branchId'])) {
    $branchId = $_SESSION['branchId'];

    $stmtBranch = $conn->prepare("SELECT * FROM branch WHERE branchId=?");
    $stmtBranch->bind_param("i", $branchId);
    $stmtBranch->execute();
    $branchResult = $stmtBranch->get_result();
    $branch = $branchResult->fetch_assoc();
    $stmtBranch->close();
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-32">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Category Tabs -->
    <div class="flex gap-2 overflow-x-auto pb-2 mb-6 scrollbar-hide">
        <button onclick="filterCategory('all')" id="cat-all"
            class="category-tab whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium border transition bg-primary border-primary text-slate-800">
            All
        </button>
        <?php foreach ($categoryResult as $category): ?>
            <button onclick="filterCategory(<?php echo $category['categoryId']; ?>)"
                id="cat-<?php echo $category['categoryId']; ?>"
                class="category-tab whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium border transition bg-white border-slate-200 text-slate-600 hover:border-primary hover:text-primary">
                <?php echo htmlspecialchars($category['name']); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Food Grid -->
    <?php foreach ($categoryResult as $category): ?>
        <div class="category-section mb-8" data-category="<?php echo $category['categoryId']; ?>">
            <h1 class="font-bold text-2xl mb-4 text-slate-700"><?php echo htmlspecialchars($category['name']); ?></h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php
                $stmtFood = $conn->prepare("SELECT * FROM food WHERE categoryId = ? AND branchId = ? AND visibleStatus = 'Visible' ORDER BY foodId ASC");
                $stmtFood->bind_param("ii", $category['categoryId'], $branchId);
                $optionQuery = "SELECT foodId, groupName FROM food_option_group";
                $optionResult = $conn->query($optionQuery);
                $foodOptions = [];
                while ($optRow = $optionResult->fetch_assoc()) {
                    $foodOptions[$optRow['foodId']][] = $optRow['groupName'];
                }
                $optionResult->free();
                $stmtFood->execute();
                $foods = $stmtFood->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmtFood->close();
                foreach ($foods as $food):
                    ?>
                    <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-full">
                        <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md ">
                            <div class="flex justify-between">
                                <div
                                    class="flex items-center gap-2 text-secondaryForeground border border-secondaryForeground bg-secondary rounded-lg text-xs w-auto mx-auto absolute p-1 px-2 top-2 left-2">
                                    <span><?php echo htmlspecialchars($category['name']); ?></span>
                                </div>
                                <?php if ($food['status'] === 'Available'): ?>
                                    <div
                                        class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="text-green-600 size-3.5">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span>Available</span>
                                    </div>
                                <?php elseif ($food['status'] === 'Sold Out'): ?>
                                    <div
                                        class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto absolute p-1 px-2 top-2 right-2">
                                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="size-3.5 text-red-600">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                                fill="currentColor"></path>
                                        </svg>
                                        <span>Sold Out</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($food['image']): ?>
                                <img class="h-full object-cover w-full"
                                    src="/Foods-and-Beverage-System/uploads/menus/<?php echo htmlspecialchars($food['image']); ?>"
                                    alt="test" />
                            <?php else: ?>
                                <img class="h-full object-cover w-full"
                                    src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.sopandai.com%2Fwp-content%2Fuploads%2F2023%2F01%2FMMU.png.webp&f=1&nofb=1&ipt=ed618de2de637fb9769308656bab69713f756476ef4ce6bd2ae83063b07b3f18"
                                    alt="ui/ux review check" />
                            <?php endif; ?>
                        </div>
                        <div class="pl-4 pr-1">
                            <div class="flex items-center justify-between">
                                <h6 class="text-slate-800 text-xl font-semibold">
                                    <a href="/web/dashboard/item?name=<?php echo htmlspecialchars($food['name']); ?>"
                                        class="hover:underline"><?php echo htmlspecialchars($food['name']); ?></a>
                                </h6>
                                <span class="pr-1 font-medium text-green-600">RM <?php echo $food['basePrice']; ?></span>
                            </div>
                            <div class="flex gap-2 overflow-hidden flex-nowrap">
                                <?php if (!empty($foodOptions[$food['foodId']])): ?>
                                    <?php
                                    $options = $foodOptions[$food['foodId']];
                                    $maxVisible = 3;
                                    $shown = array_slice($options, 0, $maxVisible);
                                    $remaining = count($options) - $maxVisible;
                                    foreach ($shown as $optionName): ?>
                                        <div data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span
                                                class="font-sans text-current leading-none my-0.5 mx-1.5"><?php echo htmlspecialchars($optionName); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if ($remaining > 0): ?>
                                        <div data-open="true" data-shape="pill"
                                            class="relative inline-flex shrink-0 items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                            <span class="font-sans text-current leading-none my-0.5 mx-1.5">+
                                                <?php echo $remaining; ?> more...</span>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="px-4 pb-4 pt-0 mt-2">
                            <a href="/web/dashboard/item?name=a">
                                <button
                                    class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                    data-shape="default" data-width="full">
                                    Order
                                </button>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<input type="hidden" id="currentFoodId">
<input type="hidden" id="currentBasePrice">

<script>
    let currentQty = 1;
    let currentBasePrice = 0;

    function openFoodDialog(food) {
        if (food.status === 'Sold Out' || food.status === 'Discontinued') return;

        document.getElementById('currentFoodId').value = food.foodId;
        document.getElementById('currentBasePrice').value = food.basePrice;
        document.getElementById('dialogFoodName').innerText = food.name;
        document.getElementById('dialogFoodDesc').innerText = food.description || '';
        document.getElementById('dialogFoodPrice').innerText = 'RM ' + parseFloat(food.basePrice).toFixed(2);

        // Image
        const imgDiv = document.getElementById('dialogFoodImage');
        if (food.image) {
            imgDiv.outerHTML = `<img id="dialogFoodImage" src="/Foods-and-Beverage-System/uploads/foods/${food.image}" class="w-full h-48 object-cover">`;
        }

        currentQty = 1;
        currentBasePrice = parseFloat(food.basePrice);
        document.getElementById('qtyDisplay').innerText = 1;

        // Load options via AJAX
        document.getElementById('dialogOptions').innerHTML = '<p class="text-sm text-slate-400">Loading options...</p>';
        fetch(`get-food-options.php?foodId=${food.foodId}`)
            .then(res => res.json())
            .then(groups => {
                let html = '';
                groups.forEach(group => {
                    html += `<div class="mb-4">
                    <p class="font-semibold text-sm text-slate-700 mb-2">${group.groupName}</p>
                    <div class="flex flex-col gap-2">`;
                    group.items.forEach(item => {
                        html += `<label class="flex items-center justify-between border border-slate-200 rounded-lg px-3 py-2 cursor-pointer hover:border-primary transition">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="option_${group.optionGroupId}" value="${item.optionItemId}" 
                                onchange="updateTotal()" class="option-checkbox accent-amber-400">
                            <span class="text-sm">${item.itemName}</span>
                        </div>
                        ${item.extraPrice > 0 ? `<span class="text-xs text-primary font-medium">+RM ${parseFloat(item.extraPrice).toFixed(2)}</span>` : ''}
                    </label>`;
                    });
                    html += `</div></div>`;
                });
                document.getElementById('dialogOptions').innerHTML = html || '<p class="text-sm text-slate-400 italic">No options available.</p>';
                updateTotal();
            });

        document.getElementById('foodDialog').classList.remove('opacity-0', 'pointer-events-none');
    }

    function closeFoodDialog() {
        document.getElementById('foodDialog').classList.add('opacity-0', 'pointer-events-none');
    }

    function changeQty(delta) {
        currentQty = Math.max(1, currentQty + delta);
        document.getElementById('qtyDisplay').innerText = currentQty;
        updateTotal();
    }

    function updateTotal() {
        let extraPrice = 0;
        document.querySelectorAll('.option-checkbox:checked').forEach(cb => {
            const label = cb.closest('label');
            const priceText = label.querySelector('span.text-primary');
            if (priceText) {
                extraPrice += parseFloat(priceText.innerText.replace('+RM ', '')) || 0;
            }
        });
        const total = (currentBasePrice + extraPrice) * currentQty;
        document.getElementById('totalPriceDisplay').innerText = total.toFixed(2);
    }

    function addToCart() {
        const foodId = document.getElementById('currentFoodId').value;
        const options = Array.from(document.querySelectorAll('.option-checkbox:checked')).map(cb => cb.value);

        let body = `addToCart=1&foodId=${foodId}&quantity=${currentQty}`;
        options.forEach(opt => body += `&options[]=${opt}`);

        fetch('menu.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeFoodDialog();
                    // updates cart badge
                    const badge = document.querySelector('a[href="cart.php"] span');
                    if (badge) {
                        badge.innerText = parseInt(badge.innerText) + currentQty;
                    }
                }
            });
    }

    function filterCategory(categoryId) {
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.classList.remove('bg-primary', 'border-primary');
            tab.classList.add('bg-white', 'border-slate-200', 'text-slate-600');
        });

        const activeTab = document.getElementById('cat-' + categoryId);
        activeTab.classList.add('bg-primary', 'border-primary');
        activeTab.classList.remove('bg-white', 'border-slate-200', 'text-slate-600');

        if (categoryId === 'all') {
            document.querySelectorAll('.category-section').forEach(s => s.style.display = '');
        } else {
            document.querySelectorAll('.category-section').forEach(s => {
                s.style.display = s.dataset.category == categoryId ? '' : 'none';
            });
        }
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeFoodDialog();
    });
</script>