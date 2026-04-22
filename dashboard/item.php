<?php
session_start();
include '../database/fnbdb.php';
$food = null;
if (isset($_GET['name']) && is_string($_GET['name'])) {
    $name = $_GET['name'];
    $stmt = $conn->prepare("SELECT food.*,
    food_category.name AS categoryName,
    branch.name AS branchName,
    branch.slug AS branchSlug FROM food
    JOIN food_category ON food.categoryId = food_category.categoryId
    JOIN branch ON food.branchId = branch.branchId WHERE food.name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $food = $result->fetch_assoc();
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
        die("<div class='alert alert-danger'>Error: Invalid Request. <a href='Lab 09 Q1.php'>Go Back</a></div>");
    }
}
$groupStmt = $conn->prepare("
    SELECT
        fog.optionGroupId,
        fog.groupName,
        foi.optionItemId,
        foi.itemName,
        foi.extraPrice
    FROM food_option_group fog
    LEFT JOIN food_option_item foi ON fog.optionGroupId = foi.optionGroupId
    WHERE fog.foodId = ?
    ORDER BY fog.optionGroupId, foi.optionItemId
");
$groupStmt->bind_param("i", $food['foodId']);
$groupStmt->execute();
$groupResult = $groupStmt->get_result();

$optionGroups = [];
while ($row = $groupResult->fetch_assoc()) {
    $gId = $row['optionGroupId'];
    if (!isset($optionGroups[$gId])) {
        $optionGroups[$gId] = [
            'groupName' => $row['groupName'],
            'items' => []
        ];
    }
    if ($row['optionItemId']) {
        $optionGroups[$gId]['items'][] = [
            'itemName' => $row['itemName'],
            'extraPrice' => $row['extraPrice']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details - <?php echo htmlspecialchars($food['name']); ?> - Floudemo</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="Icon" href="../assets/logo.png" sizes="64x64">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            const dialog3 = document.getElementById("updateMenuDialog");
            window.openUpdateMenuDialog = function () {
                dialog3.classList.remove("opacity-0", "pointer-events-none");
                dialog3.classList.add("opacity-100");
            };
            window.closeUpdateMenuDialog = function () {
                dialog3.classList.remove("opacity-100");
                dialog3.classList.add("opacity-0", "pointer-events-none");
            };
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeUpdateMenuDialog();
                }
            });
            const dialog4 = document.getElementById("updateFoodVisibleStatusDialog");
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
</head>

<body class="bg-secondary min-h-screen">
    <?php include '../includes/dashboard/menu-item/navbar.php'; ?>
    <?php include '../includes/dashboard/menu-item/body.php'; ?>
</body>

</html>