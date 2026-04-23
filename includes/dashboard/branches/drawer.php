<?php
include '../database/fnbdb.php';
$errorMessage = "";

// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}
;

$filter = "";
$countstatusOpeningsql = "SELECT COUNT(*) FROM branch WHERE status = 'Opening'";
$stmtcount = $conn->prepare($countstatusOpeningsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusOpening);
$stmtcount->fetch();
$stmtcount->close();
$countstatusClosedsql = "SELECT COUNT(*) FROM branch WHERE status = 'Closed'";
$stmtcount = $conn->prepare($countstatusClosedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusClosed);
$stmtcount->fetch();
$stmtcount->close();
$countstatusSetupsql = "SELECT COUNT(*) FROM branch WHERE status = 'Setup'";
$stmtcount = $conn->prepare($countstatusSetupsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusSetup);
$stmtcount->fetch();
$stmtcount->close();
$countstatusDeprecatedsql = "SELECT COUNT(*) FROM branch WHERE status = 'Deprecated'";
$stmtcount = $conn->prepare($countstatusDeprecatedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusDeprecated);
$stmtcount->fetch();
$stmtcount->close();

$countnobranchsql = "SELECT COUNT(*) FROM branch";
$stmtcount = $conn->prepare($countnobranchsql);
$stmtcount->execute();
$stmtcount->bind_result($totalBranchNumber);
$stmtcount->fetch();
$stmtcount->close();
?>

<div class="fixed inset-0 bg-slate-950/50 opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="sidebarDrawer" aria-hidden="true">
    <div class="text-sm w-56 p-4 bg-white border h-screen border-gray-300/30 rounded-md font-medium flex flex-col"
        onclick="event.stopPropagation()">
        <a href="/web/dashboard" class="rounded mt-3 mb-5 flex h-max items-center cursor-pointer">
            <img src="../assets/logo.png" alt="brand"
                class="inline-block object-cover object-center w-10 h-10 rounded-sm" />
            <p class="font-sans antialiased text-2xl text-current font-extrabold">Dashboard</p>
        </a>
        <ul class="flex flex-col gap-2 flex-1 overflow-y-auto">
            <a href="/web/dashboard"
                class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bxs-dashboard mr-2 text-xl'></i>
                <span>Dashboard</span>
            </a>
            <div class="w-full h-px bg-gray-300/70 my-2"></div>
            <li class="">
                <div class="flex justify-between items-center">
                    <div data-toggle="collapse" data-target="#sidebarCollapseList2" aria-expanded="false"
                        aria-controls="sidebarCollapseList2"
                        class="flex items-center hover:text-mutedForeground min-w-40 cursor-pointer py-1.5 px-2.5 rounded-md align-middle transition-all duration-300 ease-in ">
                        <div class="flex items-center gap-1">
                            <a href="/web/dashboard/branches"
                                class="sidebar-link hover:text-primary hover:underline">Branches</a>
                            <span data-icon
                                class="grid place-items-center shrink-0 transition-transform duration-300 ease-in-out">
                                <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" color="currentColor"
                                    class="h-4 w-4 stroke-[1.5] ">
                                    <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                    
                </div>
                <div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
                    id="createBranchDialog2" aria-hidden="true">
                    <div
                        class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
                        <form method="POST" action="" class="p-2 space-y-5">
                            <h1 class="text-lg text-slate-800 font-semibold">Let's Create a Branch</h1>
                            <div>
                                <label for="name" class="block text-sm font-medium text-foreground mb-1">Name</label>
                                <input type="text" name="name" placeholder="name"
                                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                                    required />
                            </div>
                            <div>
                                <label for="slug" class="block text-sm font-medium text-foreground mb-1">Slug</label>
                                <input type="text" name="slug" placeholder="slug"
                                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                                    required />
                            </div>
                            <button type="submit"
                                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                                Create
                            </button>
                        </form>
                    </div>
                </div>
                <div class="overflow-hidden transition-[max-height] duration-300 ease-in-out max-h-0"
                    id="sidebarCollapseList2">
                    <ul class="flex flex-col gap-0.5 min-w-60 mt-2">
                        <?php
                        $branchQuery = "SELECT * FROM branch WHERE 1 ORDER BY branchId DESC";
                        $branchResult = $conn->query($branchQuery);
                        if ($branchResult->num_rows > 0):
                            while ($data = $branchResult->fetch_assoc()):
                                ?>
                                <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($data['slug']); ?>">
                                    <li
                                        class="pl-10 flex items-center cursor-pointer py-0.5 px-2 rounded-md align-middle select-none font-sans transition-all duration-300 ease-in bg-transparent w-47 hover:bg-accent hover:text-accentForeground">
                                        <i class='bx bxs-building mr-3 text-xl'></i>
                                        <p class="line-clamp-1 w-50"><?php echo htmlspecialchars($data['name']); ?></p>
                                    </li>
                                </a>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <div class="w-full h-px bg-gray-300/70 my-2"></div>
            <a href="/web/dashboard/orders"
                class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bxs-bowl-hot mr-2 text-xl'></i>
                <span>Orders</span>
            </a>
            <a href="/web/dashboard/menu"
                class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bxs-food-menu mr-2 text-xl'></i>
                <span>Menu</span>
            </a>
            <a href="/web/dashboard/tables"
                class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bx-table mr-2 text-xl'></i>
                <span>Tables</span>
            </a>
            <a href="/web/dashboard/users"
                class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bxs-user mr-2 text-xl'></i>
                <span>Users</span>
            </a>
            <a href="/web/dashboard/reviews"
                class="sidebar-link flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bxs-star mr-2 text-xl'></i>
                <span>Review</span>
            </a>
            <a href="/web/dashboard/reports"
                class="flex items-center gap-3 cursor-pointer px-3 py-1 rounded hover:bg-accent hover:text-accentForeground transition">
                <i class='bx bxs-report mr-2 text-xl'></i>
                <span>Reports</span>
            </a>
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
</div>