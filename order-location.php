<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $branchId = $_POST['branchId'] ?? null;

    if (!$branchId) {
        header("Location: order-location.php");
        exit();
    }

    $_SESSION['branchId'] = $branchId;

    header("Location: order-method.php");
    exit();
}

if (isset($_GET['updateLocation'])) {
    unset($_SESSION['branchId']);
    //unset($_SESSION['orderId']);
}

if (isset($_SESSION['branchId']) && !isset($_GET['updateLocation'])) {
    header("Location: order-method.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="app.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Floudemo - Order Location</title>
    <link rel="Icon" href="./assets/logo.png" sizes="64x64">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
            document.querySelectorAll(".nav-link").forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath === linkPath) {
                    link.classList.add("text-primary", "font-semibold");
                    link.setAttribute("aria-current", "page");
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
</head>

<body class="flex justify-center h-screen flex-col">
    <div class="h-screen w-full overflow-y-scroll">
        <?php include_once './includes/navbar.php' ?>
        <?php include './includes/menu/order-location/body.php' ?>
    </div>
</body>

</html>