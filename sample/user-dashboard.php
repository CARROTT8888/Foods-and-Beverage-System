<?php
// 1. 关掉登录检查 (只看设计)
// session_start();
// if (!isset($_SESSION['userId'])) {
//     header("Location: sign-in.php");
//     exit();
// }

// 2. 关掉数据库连接 (避免找不到文件报错)
// include '../database/fnbdb.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Floudemo - User Dashboard (Design Preview)</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://unpkg.com/@material-tailwind/html@3.0.0-beta.7/dist/material-tailwind.umd.min.js" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
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
                    }
                }
            }
        };

        document.addEventListener("DOMContentLoaded", function () {
            // 控制分配员工 Dialog 的 JS
            const employeeDialog = document.getElementById("assignEmployeeDialog");
            window.openEmployeeDialog = function () {
                employeeDialog.classList.remove("opacity-0", "pointer-events-none");
                employeeDialog.classList.add("opacity-100");
            };
            window.closeEmployeeDialog = function () {
                employeeDialog.classList.remove("opacity-100");
                employeeDialog.classList.add("opacity-0", "pointer-events-none");
            };
        });
    </script>
</head>

<body class="flex bg-muted/30 min-h-screen">
    
    <div class="w-64 bg-white border-r border-gray-200 flex-shrink-0 flex items-center justify-center text-center">
        <span class="text-gray-400 font-bold border border-dashed border-gray-300 p-4 rounded">
            Sidebar Area <br><small class="text-xs font-normal">(Real menu shown after merge)</small>
        </span>
    </div>

    <div class="w-full flex justify-center p-8 overflow-y-auto">
        <div class="w-full max-w-7xl flex flex-col gap-6">
            
            <div class="flex justify-between items-center bg-white p-6 rounded-custom shadow-sm border border-gray-100">
                <div>
                    <h1 class="text-2xl font-bold text-foreground flex items-center gap-2">
                        User Dashboard <span class="text-mutedForeground text-lg font-normal">| Staff & Branch Assignment</span>
                    </h1>
                    <p class="text-sm text-mutedForeground mt-1">
                        <i class='bx bx-info-circle'></i> Core Module: For Employees only. Customer data is handled separately.
                    </p>
                </div>
                <button onclick="openEmployeeDialog()" class="bg-foreground text-white px-4 py-2 rounded-custom hover:bg-gray-800 transition-colors flex items-center gap-2">
                    <i class='bx bx-user-plus'></i> Assign New Employee
                </button>
            </div>

            <div class="bg-white p-6 rounded-custom shadow-sm border border-gray-100">
                <div class="flex flex-wrap gap-6">
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-sm font-semibold text-foreground mb-2">Filter by Branch</label>
                        <select class="w-full border border-gray-300 rounded-custom px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="all">All Branches</option>
                            <option value="b1">Kuala Lumpur Main Branch</option>
                            <option value="b2">Subang Jaya Branch</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-sm font-semibold text-foreground mb-2">Filter by Role</label>
                        <select class="w-full border border-gray-300 rounded-custom px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="all">All Roles</option>
                            <option value="admin">Admin (Full access to all modules)</option>
                            <option value="manager">Branch Manager (Manages branch operations)</option>
                            <option value="staff">Staff (Processes orders)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-custom shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-muted text-mutedForeground text-sm border-b border-gray-200">
                            <tr>
                                <th class="p-4 font-semibold whitespace-nowrap">Employee Name</th>
                                <th class="p-4 font-semibold whitespace-nowrap">Email</th>
                                <th class="p-4 font-semibold whitespace-nowrap">Role</th>
                                <th class="p-4 font-semibold whitespace-nowrap">Assigned Branch</th>
                                <th class="p-4 font-semibold whitespace-nowrap">Date Joined</th>
                                <th class="p-4 font-semibold whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-foreground divide-y divide-gray-100">
                            
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 font-bold">Kieran Ong</td>
                                <td class="p-4">kieran@floudemo.com</td>
                                <td class="p-4">
                                    <span class="bg-destructive text-white px-2 py-1 rounded text-xs font-semibold">Admin</span>
                                    <div class="text-xs text-mutedForeground mt-1">Super User</div>
                                </td>
                                <td class="p-4 font-semibold text-foreground">All Branches</td>
                                <td class="p-4">2026-01-15</td>
                                <td class="p-4">
                                    <button class="text-primary hover:text-accentForeground transition-colors"><i class='bx bx-edit text-lg'></i></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 font-bold">Teng Xin Ning</td>
                                <td class="p-4">xinning@floudemo.com</td>
                                <td class="p-4">
                                    <span class="bg-primary text-primaryForeground px-2 py-1 rounded text-xs font-semibold">Manager</span>
                                    <div class="text-xs text-mutedForeground mt-1">Branch Management</div>
                                </td>
                                <td class="p-4">Kuala Lumpur Main Branch</td>
                                <td class="p-4">2026-02-01</td>
                                <td class="p-4">
                                    <button class="text-primary hover:text-accentForeground transition-colors"><i class='bx bx-edit text-lg'></i></button>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 font-bold">Law Jie Po</td>
                                <td class="p-4">jiepo.staff@floudemo.com</td>
                                <td class="p-4">
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold">Staff</span>
                                    <div class="text-xs text-mutedForeground mt-1">Order Fulfillment</div>
                                </td>
                                <td class="p-4">Subang Jaya Branch</td>
                                <td class="p-4">2026-03-10</td>
                                <td class="p-4">
                                    <button class="text-primary hover:text-accentForeground transition-colors"><i class='bx bx-edit text-lg'></i></button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <div id="assignEmployeeDialog" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/50" onclick="closeEmployeeDialog()"></div>
        <div class="bg-white rounded-custom shadow-xl w-full max-w-md relative z-10 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-foreground">Assign Employee</h2>
                <button onclick="closeEmployeeDialog()" class="text-gray-400 hover:text-foreground text-2xl">&times;</button>
            </div>
            
            <form class="flex flex-col gap-4" onsubmit="event.preventDefault(); alert('This is a design preview; database logic is disabled.'); closeEmployeeDialog();">
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1">Employee Name</label>
                    <input type="text" class="w-full border border-gray-300 rounded-custom px-3 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1">Employee Email (For Login)</label>
                    <input type="email" class="w-full border border-gray-300 rounded-custom px-3 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1">Assign Role</label>
                    <select class="w-full border border-gray-300 rounded-custom px-3 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="admin">Admin (Full system access)</option>
                        <option value="manager">Branch Manager (Branch management)</option>
                        <option value="staff">Staff (Order processing)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1">Assign Branch</label>
                    <select class="w-full border border-gray-300 rounded-custom px-3 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="all">All Branches (Admin Only)</option>
                        <option value="kl">Kuala Lumpur Main Branch</option>
                        <option value="sj">Subang Jaya Branch</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeEmployeeDialog()" class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-foreground transition-colors">Cancel</button>
                    <button type="submit" class="bg-foreground text-white px-6 py-2 rounded-custom hover:bg-gray-800 transition-colors">Confirm Assignment</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>