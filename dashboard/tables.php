<?php
session_start();
// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}

// Initialize variables to prevent "Undefined variable" notices
$errorMessage = '';
include '../database/fnbdb.php';
$branch = null;

$filter = "";
$countstatusAvailablesql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Available'";
$stmtcount = $conn->prepare($countstatusAvailablesql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusAvailable);
$stmtcount->fetch();
$stmtcount->close();
$countstatusOccupiedsql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Occupied'";
$stmtcount = $conn->prepare($countstatusOccupiedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusOccupied);
$stmtcount->fetch();
$stmtcount->close();
$countstatusReservedsql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Reserved'";
$stmtcount = $conn->prepare($countstatusReservedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusReserved);
$stmtcount->fetch();
$stmtcount->close();
$countstatusDirtysql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Dirty'";
$stmtcount = $conn->prepare($countstatusDirtysql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusDirty);
$stmtcount->fetch();
$stmtcount->close();
$countstatusBlockedsql = "SELECT COUNT(*) FROM seat_table WHERE status = 'Blocked'";
$stmtcount = $conn->prepare($countstatusBlockedsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatusBlocked);
$stmtcount->fetch();
$stmtcount->close();
$countstatussql = "SELECT COUNT(*) FROM seat_table";
$stmtcount = $conn->prepare($countstatussql);
$stmtcount->execute();
$stmtcount->bind_result($totalSeatNumber);
$stmtcount->fetch();
$stmtcount->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableName = $_POST['tableName'];
    $branchId = $_POST['branchId'];
    $totalSeat = $_POST['totalSeat'];
    $status = "Available";

    // auto set available seat same as total seat
    $availableSeat = $totalSeat;

    // check for duplocate table code
    $checkQuery = "SELECT tableId FROM seat_table WHERE tableName = ? AND branchId = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('si', $tableName, $branchId);
    $checkStmt->execute();
    $checkStmt->store_result();

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
        if($addseatstmt->execute()) {
            header("Location: /web/dashboard/tables");
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
    <title>Floudemo - Dashboard Tables</title>
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
            const dialog = document.getElementById("seatTableDialog");
            window.openDialog = function () {
                dialog.classList.remove("opacity-0", "pointer-events-none");
                dialog.classList.add("opacity-100");
            };
            window.closeDialog = function () {
                dialog.classList.remove("opacity-100");
                dialog.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeDialog();
                }
            });
            const drawer = document.getElementById("sidebarDrawerBranch");
            function openDrawerBranch() {
                drawer.classList.remove("opacity-0", "pointer-events-none");
                drawer.classList.add("opacity-100");
            }
            function closeDrawerBranch() {
                drawer.classList.remove("opacity-100");
                drawer.classList.add("opacity-0", "pointer-events-none");
            }
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/css/pagedone.css " rel="stylesheet" />

</head>

<body class="flex">
    <div class="">
        <?php include '../includes/dashboard/sidebar.php'; ?>
    </div>
    <div class="min-h-screen w-full flex justify-center">
        <?php include '../includes/dashboard/tables/body.php'; ?>
    </div>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
</body>

</html>