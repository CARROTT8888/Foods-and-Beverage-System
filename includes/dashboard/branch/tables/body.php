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
        <button type="button" data-toggle="modal" data-target="#sidebarDrawer"
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
        Tables
    </h1>
    <div class="flex sm:items-center flex-wrap gap-6">
        <!-- Dropdown Container -->
        <div class="relative mx-auto max-w-7xl w-full px-4 sm:px-6 lg:px-8 ">
            <?php include '../includes/dashboard/branch/tables/header.php'; ?>
            <div
                class="w-auto text-center grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-4 max-w-7xl mx-auto items-center px-4 sm:px-6 lg:px-8">
                <div
                    class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                    <i class='bx bx-dots-vertical-rounded absolute w-full flex justify-end text-2xl top-2'></i>
                    <div class="p-3 text-center">
                        <div class="flex justify-center mb-4">

                            <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                class="h-10 w-10 text-green-600">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                    fill="currentColor"></path>
                            </svg>

                        </div>
                        <div class="flex justify-center mb-2">
                            <h5 class="text-slate-800 text-2xl font-bold">
                                A06
                            </h5>
                        </div>
                        <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                            4 Seat(s)
                        </p>
                    </div>
                </div>
                <div
                    class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                    <i class='bx bx-dots-vertical-rounded absolute w-full flex justify-end text-2xl top-2'></i>
                    <div class="p-3 text-center">
                        <div class="flex justify-center mb-4">

                            <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                class="h-10 w-10 text-green-600">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                    fill="currentColor"></path>
                            </svg>

                        </div>
                        <div class="flex justify-center mb-2">
                            <h5 class="text-slate-800 text-2xl font-bold">
                                A06
                            </h5>
                        </div>
                        <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                            4 Seat(s)
                        </p>
                    </div>
                    
                </div>
                <div
                    class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                    <i class='bx bx-dots-vertical-rounded absolute w-full flex justify-end text-2xl top-2'></i>
                    <div class="p-3 text-center">
                        <div class="flex justify-center mb-4">

                            <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                class="h-10 w-10 text-green-600">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                    fill="currentColor"></path>
                            </svg>

                        </div>
                        <div class="flex justify-center mb-2">
                            <h5 class="text-slate-800 text-2xl font-bold">
                                A06
                            </h5>
                        </div>
                        <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                            4 Seat(s)
                        </p>
                    </div>
                    
                </div>
                <div
                    class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                    <i class='bx bx-dots-vertical-rounded absolute w-full flex justify-end text-2xl top-2'></i>
                    <div class="p-3 text-center">
                        <div class="flex justify-center mb-4">

                            <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                class="h-10 w-10 text-green-600">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                    fill="currentColor"></path>
                            </svg>

                        </div>
                        <div class="flex justify-center mb-2">
                            <h5 class="text-slate-800 text-2xl font-bold">
                                A06
                            </h5>
                        </div>
                        <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                            4 Seat(s)
                        </p>
                    </div>
                    
                </div>
                <div
                    class="relative min-h-60 w-auto flex flex-col justify-center items-center my-6 text-green-500  border border-green-500 bg-green-100 shadow-sm rounded-lg p-2">
                    <i class='bx bx-dots-vertical-rounded absolute w-full flex justify-end text-2xl top-2'></i>
                    <div class="p-3 text-center">
                        <div class="flex justify-center mb-4">

                            <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                class="h-10 w-10 text-green-600">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                                    fill="currentColor"></path>
                            </svg>

                        </div>
                        <div class="flex justify-center mb-2">
                            <h5 class="text-slate-800 text-2xl font-bold">
                                A06
                            </h5>
                        </div>
                        <p class="block text-green-600 leading-normal font-semibold mb-4 max-w-lg">
                            4 Seat(s)
                        </p>
                    </div>
                    
                </div>
            </div>

</section>
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
</script>