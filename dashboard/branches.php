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
$countstateJohorsql = "SELECT COUNT(*) FROM branch WHERE state = 'Johor'";
$stmtcount = $conn->prepare($countstateJohorsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateJohor);
$stmtcount->fetch();
$stmtcount->close();
$countstateKedahsql = "SELECT COUNT(*) FROM branch WHERE state = 'Kedah'";
$stmtcount = $conn->prepare($countstateKedahsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateKedah);
$stmtcount->fetch();
$stmtcount->close();
$countstateKelantansql = "SELECT COUNT(*) FROM branch WHERE state = 'Kelantan'";
$stmtcount = $conn->prepare($countstateKelantansql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateKelantan);
$stmtcount->fetch();
$stmtcount->close();
$countstateMelakasql = "SELECT COUNT(*) FROM branch WHERE state = 'Melaka'";
$stmtcount = $conn->prepare($countstateMelakasql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateMelaka);
$stmtcount->fetch();
$stmtcount->close();
$countstateNegeriSembilansql = "SELECT COUNT(*) FROM branch WHERE state = 'Negeri Sembilan'";
$stmtcount = $conn->prepare($countstateNegeriSembilansql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateNegeriSembilan);
$stmtcount->fetch();
$stmtcount->close();
$countstatePahangsql = "SELECT COUNT(*) FROM branch WHERE state = 'Pahang'";
$stmtcount = $conn->prepare($countstatePahangsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatePahang);
$stmtcount->fetch();
$stmtcount->close();
$countstatePeraksql = "SELECT COUNT(*) FROM branch WHERE state = 'Perak'";
$stmtcount = $conn->prepare($countstatePeraksql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatePerak);
$stmtcount->fetch();
$stmtcount->close();
$countstatePerlissql = "SELECT COUNT(*) FROM branch WHERE state = 'Perlis'";
$stmtcount = $conn->prepare($countstatePerlissql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatePerlis);
$stmtcount->fetch();
$stmtcount->close();
$countstatePulauPinangsql = "SELECT COUNT(*) FROM branch WHERE state = 'Pulau Pinang'";
$stmtcount = $conn->prepare($countstatePulauPinangsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStatePulauPinang);
$stmtcount->fetch();
$stmtcount->close();
$countstateSabahsql = "SELECT COUNT(*) FROM branch WHERE state = 'Sabah'";
$stmtcount = $conn->prepare($countstateSabahsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateSabah);
$stmtcount->fetch();
$stmtcount->close();
$countstateSarawaksql = "SELECT COUNT(*) FROM branch WHERE state = 'Sarawak'";
$stmtcount = $conn->prepare($countstateSarawaksql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateSarawak);
$stmtcount->fetch();
$stmtcount->close();
$countstateSelangorsql = "SELECT COUNT(*) FROM branch WHERE state = 'Selangor'";
$stmtcount = $conn->prepare($countstateSelangorsql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateSelangor);
$stmtcount->fetch();
$stmtcount->close();
$countstateTerengganusql = "SELECT COUNT(*) FROM branch WHERE state = 'Terengganu'";
$stmtcount = $conn->prepare($countstateTerengganusql);
$stmtcount->execute();
$stmtcount->bind_result($totalStateTerengganu);
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
            const dialog = document.getElementById("branchDialog");
            window.openBranchDialog = function () {
                dialog.classList.remove("opacity-0", "pointer-events-none");
                dialog.classList.add("opacity-100");
            };
            window.closeBranchDialog = function () {
                dialog.classList.remove("opacity-100");
                dialog.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeBranchDialog();
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
            const dialog4 = document.getElementById("updateBranchVisibleStatusDialog");
            window.openUpdateVisibleStatusDialog = function () {
                dialog4.classList.remove("opacity-0", "pointer-events-none");
                dialog4.classList.add("opacity-100");
            };
            window.closeUpdateVisibleStatusDialog = function () {
                dialog4.classList.remove("opacity-100");
                dialog4.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeUpdateVisibleStatusDialog();
                }
            });
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/pagedone@1.2.2/src/css/pagedone.css " rel="stylesheet" />

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