<div class="max-w-5xl mx-auto mt-10 px-6 pb-20 grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
    <div class="md:sticky md:top-20 flex flex-col gap-4">
        <div class="image-hover rounded-2xl overflow-hidden aspect-[4/3] bg-stone-200 shadow-lg relative">
            <?php if ($food['image']): ?>
                <img class="w-full h-full object-cover block hover:scale-[1.03] transition ease-in"
                    src="/Foods-and-Beverage-System/uploads/menus/<?php echo htmlspecialchars($food['image']); ?>"
                    alt="<?php echo htmlspecialchars($food['name']); ?>">
            <?php else: ?>
                <img class="w-full h-full object-cover block hover:scale-[1.03]"
                    src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.sopandai.com%2Fwp-content%2Fuploads%2F2023%2F01%2FMMU.png.webp&f=1&nofb=1&ipt=ed618de2de637fb9769308656bab69713f756476ef4ce6bd2ae83063b07b3f18"
                    alt="No image">
            <?php endif; ?>

            <?php if ($food['visibleStatus'] === 'Invisible'): ?>
                <div
                    class="absolute inset-0 bg-stone-900/50 flex items-center justify-center gap-2 text-white text-sm font-medium tracking-wide">
                    <i class='bx bxs-low-vision'></i> Hidden from customers
                </div>
            <?php endif; ?>
        </div>

        <div class="flex justify-between">
            <div class="flex gap-2 flex-wrap">
                <button type="button" onclick='fillUpdateForm(
                    <?php echo json_encode($food["foodId"]); ?>,
                    <?php echo json_encode($food["name"]); ?>,
                    <?php echo json_encode($food["description"]); ?>,
                    <?php echo json_encode($food["basePrice"]); ?>,
                    <?php echo json_encode($food["status"]); ?>,
                    <?php echo json_encode($food["branchId"]); ?>,
                    <?php echo json_encode($food["categoryId"]); ?>
                )'
                    class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                    <i class='bx bxs-edit text-lg'></i> <span class="lg:flex hidden">Edit Menu</span>
                </button>
                <?php if ($food['visibleStatus'] === 'Visible'): ?>
                    <button id="dropdownBtn" type="button" onclick="fillUpdateForm2(<?= $food['foodId'] ?>, 'Invisible')"
                        class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                        <i class='bx bxs-show text-lg'></i>Set Invisible</span>
                    </button>
                <?php elseif ($food['visibleStatus'] === 'Invisible'): ?>
                    <button id="dropdownBtn" type="button" onclick="fillUpdateForm2(<?= $food['foodId'] ?>,  'Visible')"
                        class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                        <i class='bx bxs-show text-lg'></i>Set Visible</span>
                    </button>
                <?php endif; ?>
            </div>
            <button id="dropdownBtn" type="button" onclick="openDialog()"
                class="justify-self-start inline-flex gap-2 items-center justify-center border mb-10 align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-full py-2 px-4 shadow-sm hover:shadow-md bg-secondary border-slate-300 text-primaryForeground hover:bg-accent hover:border-accentForeground hover:text-accentForeground outline-none group w-auto">
                <i class="bx bxs-trash text-lg text-red-500"></i>
            </button>
        </div>
    </div>

    <div class="flex flex-col gap-7">
        <div class="flex flex-col gap-2.5">
            <div class="flex gap-2">
                <div
                    class="flex items-center gap-2 text-secondaryForeground border border-secondaryForeground bg-secondary rounded-lg text-xs w-auto p-1 px-2 ">
                    <span><?php echo htmlspecialchars($food['categoryName']); ?></span>
                </div>
                <?php if ($food['status'] === 'Available'): ?>
                    <div
                        class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto p-1 px-2">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="currentColor" class="text-green-600 size-3.5">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                fill="currentColor"></path>
                        </svg>
                        <span>Available</span>
                    </div>
                <?php elseif ($food['status'] === 'Sold Out'): ?>
                    <div
                        class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto p-1 px-2 ">
                        <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            color="currentColor" class="size-3.5 text-red-600">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                                fill="currentColor"></path>
                        </svg>
                        <span>Sold Out</span>
                    </div>
                <?php elseif ($food['status'] === 'Discontinued'): ?>
                    <div
                        class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto p-1 px-2 ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-slate-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                        </svg>
                        <span>Discontinued</span>
                    </div>
                <?php endif; ?>
            </div>

            <h1 class="font-display text-4xl font-extrabold leading-tight text-stone-900">
                <?php echo htmlspecialchars($food['name']); ?>
            </h1>
            <div class="">Branch: <a
                    href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($food['branchSlug']); ?>"
                    class="font-bold hover:underline"><?php echo htmlspecialchars($food['branchName']); ?></a>
            </div>

            <div class="flex items-baseline gap-2">
                <span class="font-display text-3xl font-bold text-green-700">
                    <span
                        class="text-base font-medium mr-0.5">RM</span><?php echo number_format($food['basePrice'], 2); ?>
                </span>
            </div>
        </div>

        <?php if (!empty($food['description'])): ?>
            <div class="bg-white border border-stone-200 rounded-2xl px-5 py-5">
                <h3 class="text-sm text-slate-400 mb-2.5">Description</h3>
                <p class="text-md leading-7 text-slate-500">
                    <?php echo nl2br(htmlspecialchars($food['description'])); ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="flex flex-col gap-4">
            <h2
                class="font-display text-xl font-semibold text-stone-900 border-b-2 border-amber-400 pb-2 flex items-center gap-2">
                <i class='bx bxs-customize text-amber-400 text-xl'></i>
                Customization Options
            </h2>

            <?php if (!empty($optionGroups)): ?>
                <?php foreach ($optionGroups as $group): ?>
                    <div class="bg-white border border-stone-200 rounded-2xl overflow-hidden shadow-sm">

                        <div
                            class="px-4 py-3 bg-gradient-to-r from-amber-50 to-white border-b border-stone-200 flex items-center gap-2">
                            <i class='bx bxs-tag-alt text-amber-600 text-base'></i>
                            <span class="text-sm font-semibold text-stone-800 capitalize">
                                <?php echo htmlspecialchars($group['groupName']); ?>
                            </span>
                            <span class="ml-auto text-[0.72rem] text-slate-400">
                                <?php echo count($group['items']); ?>
                                option<?php echo count($group['items']) !== 1 ? 's' : ''; ?>
                            </span>
                            <button type="button" onclick='openUpdateFoodOptionDialog(
                                <?= (int) $group["optionGroupId"] ?>,
                                <?= json_encode($group["groupName"]) ?>,
                                <?= htmlspecialchars(json_encode($group["items"]), ENT_QUOTES, 'UTF-8') ?>
                            )' class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none data-[shape=circular]:rounded-full text-sm min-w-[34px] min-h-[34px] rounded-md bg-transparent border-transparent text-primary hover:bg-amber-200/10 hover:border-amber-200/10 shadow-none hover:shadow-none outline-none">
                                <i class="bx bxs-edit"></i>
                            </button>
                        </div>

                        <div class="flex flex-col divide-y divide-stone-100">
                            <?php foreach ($group['items'] as $item): ?>
                                <div class="flex items-center justify-between px-4 py-2.5 hover:bg-stone-50 transition-colors">
                                    <span class="flex items-center gap-2 text-sm text-stone-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                        <?php echo htmlspecialchars($item['itemName']); ?>
                                    </span>
                                    <?php if ($item['extraPrice'] == 0): ?>
                                        <span class="text-xs font-medium text-green-600">Free</span>
                                    <?php else: ?>
                                        <span class="text-sm font-semibold text-stone-800">+ RM
                                            <?php echo number_format($item['extraPrice'], 2); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div
                    class="bg-white border border-dashed border-stone-200 rounded-2xl p-7 text-center text-slate-400 text-sm">
                    <i class='bx bxs-plus-square text-4xl block mb-2 text-secondaryForeground'></i>
                    No customization options added yet.
                </div>
            <?php endif; ?>
            <?php include 'update-menu-visible-status.php'; ?>
            <?php include 'update-menu-dialog.php'; ?>
            <?php include 'create-food-option-dialog.php'; ?>
            <?php include 'update-food-option-dialog.php'; ?>
        </div>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const openSidebarButton = document.getElementById('open-sidebar');

    openSidebarButton.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('-translate-x-full');
    });

    // Close the sidebar when clicking outside of it
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !openSidebarButton.contains(e.target)) {
            sidebar.classList.add('-translate-x-full');
        }
    });

    function fillUpdateForm(foodId, name, description, basePrice, status, branchId, categoryId, image) {
        document.getElementById("updateFoodId").value = foodId;
        document.getElementById("updateName").value = name;
        document.getElementById("updateDescription").value = description;
        document.getElementById("updateBasePrice").value = basePrice;
        document.getElementById("updateStatus").value = status;
        document.getElementById("updateBranch").value = branchId;

        if (image) {
            document.getElementById("previewImage").src =
                "/Foods-and-Beverage-System/uploads/menus/" + image;
        } else {
            document.getElementById("previewImage").src =
                "https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.sopandai.com%2Fwp-content%2Fuploads%2F2023%2F01%2FMMU.png.webp&f=1&nofb=1&ipt=ed618de2de637fb9769308656bab69713f756476ef4ce6bd2ae83063b07b3f18";
        }

        document.querySelector("input[name='old_image']").value = image ?? "";


        const categoryDropdown = document.getElementById("updateCategory");
        categoryDropdown.innerHTML = `<option value="" selected disabled>Loading...</option>`;

        fetch("/web/includes/dashboard/menu/get_categories_from_branch.php?branchId=" + branchId)
            .then(response => response.json())
            .then(data => {
                categoryDropdown.innerHTML = `<option value="" disabled>Select Category</option>`;
                data.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.categoryId;
                    option.textContent = category.name;
                    if (category.categoryId == categoryId) {
                        option.selected = true;
                    }
                    categoryDropdown.appendChild(option);
                });
            });
        document.getElementById("updateMenuDialog").classList.remove("opacity-0", "pointer-events-none");
    }
    function fillUpdateForm2(foodId, visibleStatus) {
        //console.log(branchId, name);

        document.getElementById("foodId").value = foodId;
        document.getElementById("visibleStatus").value = visibleStatus;

        document.getElementById("confirmText").innerText =
            "Are you sure you want to change visible status to " + visibleStatus + "?";

        document.getElementById("updateFoodVisibleStatusDialog").classList.remove("opacity-0", "pointer-events-none");
    }

    function handleEditFoodOption(btn) {
        const optionGroupId = btn.dataset.groupid;
        const groupName = btn.dataset.groupname;
        const items = JSON.parse(btn.dataset.items);

        openUpdateFoodOptionDialog(optionGroupId, groupName, items);
    }
    function openUpdateFoodOptionDialog(optionGroupId, groupName, items) {
        document.getElementById("updateOptionGroupId").value = optionGroupId;
        document.getElementById("updateGroupName").value = groupName;

        const container = document.getElementById("updateItemContainer");
        container.innerHTML = "";

        items.forEach(item => {
            const div = document.createElement("div");
            div.className = "flex gap-2 items-center";

            div.innerHTML = `
            <input type="hidden" name="itemId[]" value="${item.optionItemId}">
            <input type="text" name="itemName[]" value="${item.itemName}"
                class="w-full border px-4 py-2 rounded-md" required>
            <input type="number" name="extraPrice[]" step="0.01" value="${item.extraPrice}"
                class="w-full border px-4 py-2 rounded-md" required>
            <button type="button" onclick="this.parentElement.remove()"
                class="text-red-400 hover:text-red-600 text-lg font-bold px-1">✕</button>
        `;
            container.appendChild(div);
        });

        document.getElementById("updateFoodOptionDialog")
            .classList.remove("opacity-0", "pointer-events-none");
    }

    function closeUpdateFoodOptionDialog() {
        document.getElementById("updateFoodOptionDialog")
            .classList.add("opacity-0", "pointer-events-none");
    }

    // Add NEW row (no itemId)
    function addNewUpdateItemRow() {
        const container = document.getElementById("updateItemContainer");

        const div = document.createElement("div");
        div.className = "flex gap-2 items-center";

        div.innerHTML = `
        <input type="hidden" name="itemId[]" value="">
        <input type="text" name="itemName[]" placeholder="New Item"
            class="w-full border px-4 py-2 rounded-md" required>
        <input type="number" name="extraPrice[]" step="0.01" placeholder="0.00"
            class="w-full border px-4 py-2 rounded-md" required>
        <button type="button" onclick="this.parentElement.remove()"
            class="text-red-400 hover:text-red-600 text-lg font-bold px-1">✕</button>
    `;
        container.appendChild(div);
    }
</script>