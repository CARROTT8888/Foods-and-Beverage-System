<?php
session_start();
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
        };
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="app.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Floudemo - Dashboard Branches</title>
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
                    link.classList.add("text-primary", "font-bold", "hover:text-accentForeground", "transition-all", "duration-300");
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
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/css/pagedone.css " rel="stylesheet"/>
    
</head>

<body class="flex">
    <div class="">
        <?php include '../includes/dashboard/sidebar.php'; ?>
    </div>
    <div class="min-h-screen w-full flex justify-center">
        <?php include '../includes/dashboard/branches/body.php'; ?>
    </div>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
</body>

</html>