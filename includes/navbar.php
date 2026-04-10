<div>
    <nav
        class="rounded-lg border shadow-lg overflow-hidden p-2 bg-white border-slate-200 shadow-slate-950/5 mx-auto w-full max-w-7xl ">
        <div class="flex items-center w-full">
            <a href="home.php"
                class="font-sans antialiased text-lg ml-2 mr-10 py-1 font-extrabold text-primary flex items-center"><img
                    src="./assets/logo.png" class="w-[50px]" />Floudemo</a>
            <hr class="ml-1 mr-1.5 hidden h-5 w-px border-l border-t-0 border-secondary-dark lg:block" />
            <div class="flex justify-between items-center w-full">
                <div class="hidden lg:block">
                    <ul class="mt-4 flex flex-col gap-x-3 gap-y-1.5 lg:mt-0 lg:flex-row lg:items-center">
                        <li>
                            <a href="/web/home"
                                class="font-sans nav-link antialiased text-sm flex flex-row items-center gap-x-2 p-1 hover:text-primary">
                                <i class="bx bxs-home"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="/web/order-location"
                                class="font-sans nav-link antialiased text-sm flex flex-row items-center gap-x-2 p-1 hover:text-primary">
                                <i class='bx bxs-food-menu'></i>
                                Menu
                            </a>
                        </li>
                        <li>
                            <a href="/orders"
                                class="font-sans nav-link antialiased text-sm flex flex-row items-center gap-x-2 p-1 hover:text-primary">
                                <i class='bx bxs-bowl-hot'></i>
                                <span class="flex flex-row w-[65px]">0 Order(s)</span>
                            </a>
                        </li>
                        <li>
                            <a href="/web/branches"
                                class="font-sans nav-link antialiased text-sm flex flex-row items-center gap-x-2 p-1 hover:text-primary">
                                <i class='bx bxs-building'></i>
                                Branches
                            </a>
                        </li>
                        <li>
                            <a href="/contact"
                                class="font-sans nav-link antialiased text-sm flex items-center gap-x-2 p-1 hover:text-primary">
                                <i class='bx bxs-contact'></i>
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>


                <div class="dropdown w-full flex justify-end" data-placement="bottom-start">
                    <img data-toggle="dropdown" aria-expanded="false"
                        src="https://raw.githubusercontent.com/creativetimofficial/public-assets/master/ct-assets/team-4.jpg"
                        alt="profile-picture" class="object-cover w-11 h-11 rounded-full cursor-pointer">
                    <div data-role="menu"
                        class="hidden mt-2 bg-white border border-slate-200 rounded-lg shadow-xl shadow-slate-950/[0.025] p-1 z-30 w-[180px] cursor-default">
                        <div class="p-1 text-mutedForeground">
                            <p class="font-sans antialiased text-sm text-current font-semibold">My Account</p>
                        </div>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-user-circle mr-2 text-lg'></i>
                            Profile
                        </a>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-cog mr-2 text-lg'></i>
                            Settings
                        </a>
                        <a href="/web/dashboard/"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-dashboard mr-2 text-lg'></i>
                            Dashboard
                        </a>
                        <hr class="!my-1 -mx-1 border-slate-200">
                        <a href="menu"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-food-menu mr-2 text-lg'></i>
                            Menu
                        </a>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-bowl-hot mr-2 text-lg'></i>
                            0 Order(s)
                        </a>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bx-history mr-2 text-lg'></i>
                            History
                        </a>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-building mr-2 text-lg'></i>
                            Branches
                        </a>
                        <hr class="!my-1 -mx-1 border-slate-200">
                        <div class="h-max p-1 text-mutedForeground">
                            <p class="font-sans antialiased text-sm text-current font-semibold">Questions</p>
                        </div>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-contact mr-2 text-lg'></i>
                            Contact
                        </a>
                        <a href="#"
                            class="block p-1 text-sm focus:bg-accent focus:text-accentForeground text-slate-600 hover:text-accentForeground hover:bg-accent rounded-md flex items-center">
                            <i class='bx bxs-help-circle mr-2 text-lg'></i>
                            Help and Support
                        </a>
                        <hr class="!my-1 -mx-1 border-slate-200">
                        <a href="sign-out.php"
                            class="block p-1 text-sm text-red-500 hover:bg-red-200 rounded-md flex items-center font-bold">
                            <i class='bx bx-log-out mr-2 text-lg'></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </nav>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://unpkg.com/@material-tailwind/html@3.0.0-beta.7/dist/material-tailwind.umd.min.js"
        defer></script>
</div>