<?php
include '../database/fnbdb.php';
$errorMessage = "";

// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}
;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    $checkNameQuery = $conn->prepare("SELECT branchId FROM branch WHERE name = ?");
    $checkNameQuery->bind_param("s", $name);
    $checkNameQuery->execute();
    $checkNameQuery->store_result();
    if ($checkNameQuery->num_rows > 0) {
        $nameAlreadyExists = true;
        $errorMessage = "The branch's name has already exist.";
        $checkNameQuery->close();
    } else {
        $checkNameQuery->close();
        $branchQuery = "INSERT INTO branch (name, slug) VALUES (?, ?)";
        $checkNameQuery = $conn->prepare($branchQuery);
        $checkNameQuery->bind_param("ss", $name, $slug);
        if ($checkNameQuery->execute()) {
            header("Location: /web/dashboard/branches.php");
            exit();
        }
    }
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
    id="sidebarDrawerBranch" aria-hidden="true">
    <div class="text-sm w-56 p-4 bg-white border h-screen border-gray-300/30 rounded-md font-medium flex flex-col"
        onclick="event.stopPropagation()">
        <a href="/web/dashboard/branch?slug=<?php echo htmlspecialchars($branch['slug']); ?>"
            class="rounded mt-3 mb-5 flex h-max items-center cursor-pointer">
            <img src="../assets/logo.png" alt="brand"
                class="inline-block object-cover object-center w-10 h-10 rounded-sm" />
            <p class="font-sans antialiased text-2xl text-current font-extrabold">Branch</p>
        </a>
        <ul class="flex flex-col gap-2 flex-1 overflow-y-auto">
            <a href="/web/dashboard"
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
            <!-- DO NOT TOUCH IT!!!!!! --->
            <li class="">
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
            </li>
        </ul>
        <div class="w-full rounded p-2 mt-auto hover:bg-accent cursor-pointer">
            <div class="flex items-center gap-4">
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
        </div>
    </div>
</div>