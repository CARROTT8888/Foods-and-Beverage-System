<div
    class="text-sm w-61.5 p-4 bg-white border h-screen border-gray-300/30 rounded-md font-medium lg:flex hidden flex-col">
    <a href="/web/dashboard/branches">
        <button
            class="inline-flex border font-sans font-medium text-center transition-all duration-300 ease-in items-center disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-1 px-2 shadow-sm hover:shadow bg-transparent text-primaryForeground hover:bg-accent hover:text-accentForeground">
            <i class='bx bx-arrow-back mr-2'></i>Back to All Branches
        </button>
    </a>
    <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>"
        class="rounded mt-3 mb-5 flex h-max items-center cursor-pointer">
        <img src="../assets/logo.png" alt="brand"
            class="inline-block object-cover object-center w-10 h-10 rounded-sm" />
        <p class="font-sans antialiased text-2xl text-current font-extrabold">Branch</p>
    </a>
    <ul class="flex flex-col gap-2 flex-1 overflow-y-auto">
        <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>"
            class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bxs-dashboard mr-2 text-xl'></i>
            <span>Dashboard</span>
        </a>
        <li
            class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bxs-bowl-hot mr-2 text-xl'></i>
            <span>Orders</span>
        </li>
        <a href="/web/dashboard/bmenu?slug=<?php echo htmlspecialchars($branch['slug']); ?>"
            class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bxs-food-menu mr-2 text-xl'></i>
            <span>Menu</span>
        </a>
        <a href="/web/dashboard/btables?slug=<?php echo htmlspecialchars($branch['slug']); ?>"
            class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bx-table mr-2 text-xl'></i>
            <span>Tables</span>
        </a>
        <i
            class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bxs-user mr-2 text-xl'></i>
            <span>Users</span>
        </i>
        <a href="dashboard.php"
            class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bxs-star mr-2 text-xl'></i>
            <span>Review</span>
        </a>
        <li
            class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
            <i class='bx bxs-report mr-2 text-xl'></i>
            <span>Reports</span>
        </li>
    </ul>
    <div class="w-full rounded p-2 mt-auto hover:bg-accent hover:text-accentForeground dropdown cursor-pointer"
        data-placement="bottom-start">
        <div class="flex items-center gap-4" data-toggle="dropdown" aria-expanded="false">
            <img class="inline-block h-11 w-11 rounded-full object-cover object-center"
                src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/ct-assets/team-4.jpg"
                alt="avatar" />
            <div>
                <p title="<?php echo htmlspecialchars($_SESSION['name']) ?>"
                    class="font-sans text-base text-current antialiased line-champ-1 truncate w-[100px]">
                    <?php echo htmlspecialchars($_SESSION['name']) ?>
                </p>
                <p title="<?php echo htmlspecialchars($_SESSION['email']) ?>"
                    class="font-sans text-sm text-slate-600 antialiased line-champ-1 truncate w-[100px]">
                    <?php echo htmlspecialchars($_SESSION['email']) ?>
                </p>
            </div>
        </div>
        <div data-role="menu"
            class="hidden mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-30 w-[180px] cursor-default">
            <div class="p-1 text-mutedForeground">
                <p class="font-sans antialiased text-sm text-current font-semibold">My Account</p>
            </div>
            <a href="#"
                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center cursor-pointer">
                <i class='bx bxs-user-circle mr-2 text-lg'></i>
                Profile
            </a>
            <a href="#"
                class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center cursor-pointer">
                <i class='bx bxs-cog mr-2 text-lg'></i>
                Settings
            </a>
            <hr class="!my-1 -mx-1 border-slate-200">
            <a href="/web/sign-out"
                class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                <i class='bx bx-log-out mr-2 text-lg'></i>
                Logout
            </a>
        </div>
    </div>
</div>