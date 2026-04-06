<section
    class="relative overflow-y-scroll h-screen bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8 max-w-7xl w-full">
    <h1 class="max-w-7xl mx-auto items-center mb-8 font-extrabold text-5xl px-4 sm:px-6 lg:px-8 w-full">
        <!-- Sidebar -->
        <!---<button class="text-gray-500 hover:text-gray-600" id="open-sidebar">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>--->
        <button type="button" data-toggle="modal" data-target="#sidebarDrawerBranch"
            class="text-gray-500 hover:text-gray-600">
            <span class="lg:hidden flex font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </span>
        </button>
        <?php include '../includes/dashboard/branch/drawer.php'; ?>
        Menu
    </h1>
    <div class="flex sm:items-center flex-wrap gap-6">
        <!-- Dropdown Container -->
        <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 ">
            <?php include '../includes/dashboard/branch/menu/header.php'; ?>
            <div
                class="w-auto text-center grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
                <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-full">
                    <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md">
                        <img src="../assets/burger-sample.jpg" alt="card-image" />
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <h6 class="text-slate-800 text-xl font-semibold">
                                Wooden House, Florida
                            </h6>

                            <div class="flex items-center gap-0 5 ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-yellow-600">
                                    <path fill-rule="evenodd"
                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-slate-600 ml-1.5">5.0</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Cucumber</span>
                            </div>
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Spicy Level</span>
                            </div>
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Size Level</span>
                            </div>
                            <div data-open="true" data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">

                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Learn More</span>
                                <div
                                    class="grid place-items-center shrink-0 rounded-full p-px -translate-x-1 ms-1 w-5 h-5 stroke-2">
                                    <i class='bx bx-plus '></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="px-4 pb-4 pt-0 mt-2">
                        <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>">
                            <button
                                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                data-shape="default" data-width="full">
                                Visit
                            </button>
                        </a>
                    </div>
                </div>
                <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-full">
                    <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md">
                        <img src="../assets/burger-sample.jpg" alt="card-image" />
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <h6 class="text-slate-800 text-xl font-semibold">
                                Wooden House, Florida
                            </h6>

                            <div class="flex items-center gap-0 5 ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-yellow-600">
                                    <path fill-rule="evenodd"
                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-slate-600 ml-1.5">5.0</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Cucumber</span>
                            </div>
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Spicy Level</span>
                            </div>
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Size Level</span>
                            </div>
                            <div data-open="true" data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">

                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Learn More</span>
                                <div
                                    class="grid place-items-center shrink-0 rounded-full p-px -translate-x-1 ms-1 w-5 h-5 stroke-2">
                                    <i class='bx bx-plus '></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="px-4 pb-4 pt-0 mt-2">
                        <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>">
                            <button
                                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                data-shape="default" data-width="full">
                                Visit
                            </button>
                        </a>
                    </div>
                </div>
                <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-full">
                    <div class="relative h-56 m-2.5 overflow-hidden text-white rounded-md">
                        <img src="../assets/burger-sample.jpg" alt="card-image" />
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <h6 class="text-slate-800 text-xl font-semibold">
                                Wooden House, Florida
                            </h6>

                            <div class="flex items-center gap-0 5 ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-yellow-600">
                                    <path fill-rule="evenodd"
                                        d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-slate-600 ml-1.5">5.0</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Cucumber</span>
                            </div>
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Spicy Level</span>
                            </div>
                            <div data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">
                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Size Level</span>
                            </div>
                            <div data-open="true" data-shape="pill"
                                class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-xs p-0.5 shadow-sm bg-accent border-accent text-primary">

                                <span class="font-sans text-current leading-none my-0.5 mx-1.5">Learn More</span>
                                <div
                                    class="grid place-items-center shrink-0 rounded-full p-px -translate-x-1 ms-1 w-5 h-5 stroke-2">
                                    <i class='bx bx-plus '></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="px-4 pb-4 pt-0 mt-2">
                        <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>">
                            <button
                                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary border-secondary text-foreground hover:bg-amber-400 hover:text-secondaryForeground"
                                data-shape="default" data-width="full">
                                Visit
                            </button>
                        </a>
                    </div>
                </div>
            </div>
</section>
<!--<script>
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

    function fillUpdateForm(branchId, name, slug, address, status, startTime, endTime, contactNumber, state) {
        console.log(branchId, name);

        document.getElementById("branchId").value = branchId;
        document.getElementById("updateName").value = name;
        document.getElementById("updateSlug").value = slug;
        document.getElementById("updateAddress").value = address;
        document.getElementById("updateStatus").value = status;
        document.getElementById("updateStartTime").value = startTime;
        document.getElementById("updateEndTime").value = endTime;
        document.getElementById("updateContactNumber").value = contactNumber;
        document.getElementById("updateState").value = state;

        document.getElementById("updateBranchDialog").classList.remove("opacity-0", "pointer-events-none");
    }
</script>-->