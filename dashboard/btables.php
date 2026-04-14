<?php
session_start();
// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}

include '../database/fnbdb.php';
$branch = null;
if (isset($_GET['slug']) && is_string($_GET['slug'])) {
    $slug = $_GET['slug'];
    $stmt = $conn->prepare("SELECT * FROM branch WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $branch = $result->fetch_assoc();
    } else {
        die("
        <div class='col-span-full flex flex-col items-center justify-center py-16 text-center'>
    <img src='../assets/404.png' alt='404' class='w-[300px] h-[300px]' />
    <h2 class='md:text-6xl text-4xl font-bold text-gray-800'>Result Not Found!</h2>
    <p class='text-base mt-4 text-gray-500'>Oppps, maybe you should think about the keyword properly before search
        again!</p>
    <div class='flex items-center gap-4 mt-6'>
        <button type='button'
            class='bg-primary hover:bg-indigo-600 px-7 py-2.5 text-white rounded active:scale-95 transition-all'>
            Go back home
        </button>
        <button type='button' class='group flex items-center gap-2 px-7 py-2.5 active:scale-95 transition'>
            Contact support
            <svg class='group-hover:translate-x-0.5 mt-1 transition' width='15' height='11' viewBox='0 0 15 11'
                fill='none' xmlns='http://www.w3.org/2000/svg'>
                <path d='M1 5.5h13.092M8.949 1l5.143 4.5L8.949 10' stroke='#1F2937' stroke-width='1.5'
                    stroke-linecap='round' stroke-linejoin='round' />
            </svg>
        </button>
    </div>
</div>
<script src='https://cdn.tailwindcss.com/3.4.16'></script>
    <script src='https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4'></script>
        ");
    }
    $stmt->close();
} else {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        die("<div class='alert alert-danger'>Error: Invalid Request.</div>");
    }
}

$filter = "";
$branchId = $branch['branchId'];
$countstatusAvailablesql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Available' AND branchId = ?";
$stmtcount = $conn->prepare($countstatusAvailablesql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusAvailable);
$stmtcount->fetch();
$stmtcount->close();
$countstatusOccupiedsql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Occupied' AND branchId = ?";
$stmtcount = $conn->prepare($countstatusOccupiedsql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusOccupied);
$stmtcount->fetch();
$stmtcount->close();
$countstatusReservedsql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Reserved' AND branchId = ?";
$stmtcount = $conn->prepare($countstatusReservedsql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusReserved);
$stmtcount->fetch();
$stmtcount->close();
$countstatusDirtysql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Dirty' AND branchId = ?";
$stmtcount = $conn->prepare($countstatusDirtysql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusDirty);
$stmtcount->fetch();
$stmtcount->close();
$countstatusBlockedsql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Blocked' AND branchId = ?";
$stmtcount = $conn->prepare($countstatusBlockedsql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusBlocked);
$stmtcount->fetch();
$stmtcount->close();
$countstatussql = "SELECT COUNT(*) FROM seat_table WHERE branchId = ?";
$stmtcount = $conn->prepare($countstatussql);
$stmtcount->bind_param("i", $branchId);
$stmtcount->execute();
$stmtcount->bind_result($totalTableNumber);
$stmtcount->fetch();
$stmtcount->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableName = $_POST['tableName'];
    $branchId = $_POST['branchId'];
    $totalSeat = $_POST['totalSeat'];
    $status = "Available";

    // auto set available seat same as total seat
    $availableSeat = $totalSeat;

    // check for duplicate table code
    $checkQuery = "SELECT tableId FROM seat_table WHERE tableName = ? AND branchId = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('si', $tableName, $branchId);
    $checkStmt->execute();
    $checkStmt->store_result();

    // check for table's data based on slug from branch
    $slugQuery = "SELECT slug FROM branch WHERE branchId = ?";
    $slugStmt = $conn->prepare($slugQuery);
    $slugStmt->bind_param("i", $branchId);
    $slugStmt->execute();
    $slugResult = $slugStmt->get_result();
    $row = $slugResult->fetch_assoc();
    $slug = $row['slug'];
    $slugStmt->close();

    if ($checkStmt->num_rows > 0) {
        $errorMessage = 'The table code has already exists.';
        $checkStmt->close();
    } else {
        $checkStmt->close();
        // insert new table (fixed column/value count)
        $query = "INSERT INTO seat_table (tableName, branchId, totalSeat, availableSeat, status) VALUES (?, ?, ?, ?, ?)";
        $addseatstmt = $conn->prepare($query);
        //corrected bind_param types: s = string, i = integer, d = double/decimal\
        $addseatstmt->bind_param('siiis', $tableName, $branchId, $totalSeat, $availableSeat, $status);
        if ($addseatstmt->execute()) {
            header("Location: /web/dashboard/btables?slug=" . $slug);
            exit();
        } else {
            $errorMessage = "Error: " . $conn->error;
        }
        $addseatstmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="app.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Tables - <?php echo htmlspecialchars($branch['name']) ?> - Floudemo</title>
    <link rel="Icon" href="../assets/logo.png" sizes="64x64">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/@material-tailwind/html@3.0.0-beta.7/dist/material-tailwind.umd.min.js"
        defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        /*mint: {
                            500: 'oklch(0.72 0.11 178)'
                        },*/
                        foreground: 'oklch(0.2686 0 0)',
                        primary: 'oklch(0.7686 0.1647 70.0804)',
                        primaryForeground: 'oklch(0 0 0)',
                        secondary: 'oklch(0.9670 0.0029 264.5419)',
                        secondaryForeground: 'oklch(0.4461 0.0263 256.8018)',
                        muted: 'oklch(0.9846 0.0017 247.8389)',
                        mutedForeground: 'oklch(0.5510 0.0234 264.3637)',
                        accent: 'oklch(0.9869 0.0214 95.2774)',
                        accentForeground: 'oklch(0.4732 0.1247 46.2007)',
                        destructive: 'oklch(0.6368 0.2078 25.3313)',
                    },
                    borderRadius: {
                        'custom': '0.375rem'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    },
                }
            }
        };
        document.addEventListener("DOMContentLoaded", function () {
            const currentPath = window.location.pathname;
            document.querySelectorAll(".sidebar-link").forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath === linkPath) {
                    link.classList.add("bg-primary", "text-foreground", "font-bold", "hover:bg-amber-300", "hover:text-secondaryForeground", "transition-all", "duration-300");
                    link.setAttribute("aria-current", "page");
                }
            });
            /*const dialog = document.getElementById("seatTableDialog");
            window.openDrawer = function () {
                dialog.classList.remove("opacity-0", "pointer-events-none");
                dialog.classList.add("opacity-100");
            };
            window.closeDrawer = function () {
                dialog.classList.remove("opacity-100");
                dialog.classList.add("opacity-0", "pointer-events-none");
            };*/
            const drawer = document.getElementById("sidebarDrawerBranch");

            function openDrawerBranch() {
                drawer.classList.remove("opacity-0", "pointer-events-none");
                drawer.classList.add("opacity-100");
            }

            function closeDrawerBranch() {
                drawer.classList.remove("opacity-100");
                drawer.classList.add("opacity-0", "pointer-events-none");
            }

            const dialog = document.getElementById("seatTableDialog");
            window.openTableDialog = function () {
                dialog.classList.remove("opacity-0", "pointer-events-none");
                dialog.classList.add("opacity-100");
            };
            window.closeTableDialog = function () {
                dialog.classList.remove("opacity-100");
                dialog.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeTableDialog();
                }
            });
            const dialog2 = document.getElementById("searchOrFilterDialog");
            window.openDialog = function () {
                dialog2.classList.remove("opacity-0", "pointer-events-none");
                dialog2.classList.add("opacity-100");
            };
            window.closeDialog = function () {
                dialog2.classList.remove("opacity-100");
                dialog2.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeDialog();
                }
            });
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/css/pagedone.css " rel="stylesheet" />

</head>

<body class="flex">
    <div class="">
        <?php include '../includes/dashboard/branch/sidebar.php'; ?>
    </div>
    <div class="min-h-screen w-full flex justify-center">
        <?php include '../includes/dashboard/branch/tables/body.php'; ?>
    </div>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
</body>

</html>