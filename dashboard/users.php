<?php
session_start();

$user_id = $_SESSION['userId'] ?? $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: sign-in.php");
    exit();
}

include "../database/fnbdb.php";

if (!isset($mysqli) || !$mysqli instanceof mysqli) {
    if (isset($conn) && $conn instanceof mysqli) {
        $mysqli = $conn;
    } elseif (isset($db) && $db instanceof mysqli) {
        $mysqli = $db;
    } elseif (isset($link) && $link instanceof mysqli) {
        $mysqli = $link;
    } else {
        foreach ($GLOBALS as $val) {
            if ($val instanceof mysqli) {
                $mysqli = $val;
                break;
            }
        }
    }
}

$check_admin_sql = "SELECT u.role AS user_role, e.role AS emp_role 
                    FROM user u 
                    LEFT JOIN employee e ON u.userId = e.userId 
                    WHERE u.userId = ?";
$stmt_check = $mysqli->prepare($check_admin_sql);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$current_user_data = $stmt_check->get_result()->fetch_assoc();

if (!$current_user_data || $current_user_data['user_role'] !== 'employee') {
    echo "<script>alert('Access Denied: Only Administrators can access this page.'); window.location.href='home.php';</script>";
    exit();
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['action']) && $_POST['action'] === 'assign_new') {
        $email = trim($_POST['email']);
        $new_role = $_POST['role'];
        $new_branch_id = intval($_POST['branchId']);

        $stmt_find = $mysqli->prepare("SELECT userId, role FROM user WHERE email = ?");
        $stmt_find->bind_param("s", $email);
        $stmt_find->execute();
        $target_user = $stmt_find->get_result()->fetch_assoc();

        if (!$target_user) {
            $error = "User with this email not found. Please ensure they have registered an account first.";
        } elseif ($target_user['role'] === 'employee') {
            $error = "This user is already an employee. Please use the edit function.";
        } else {
            $mysqli->begin_transaction();
            try {
                $target_id = $target_user['userId'];
                $stmt_upd_user = $mysqli->prepare("UPDATE user SET role = 'employee' WHERE userId = ?");
                $stmt_upd_user->bind_param("i", $target_id);
                $stmt_upd_user->execute();

                $stmt_ins_emp = $mysqli->prepare("INSERT INTO employee (branchId, userId, role) VALUES (?, ?, ?)");
                $stmt_ins_emp->bind_param("iis", $new_branch_id, $target_id, $new_role);
                $stmt_ins_emp->execute();

                $mysqli->commit();
                $message = "Successfully assigned user as $new_role.";
            } catch (Exception $e) {
                $mysqli->rollback();
                $error = "Failed to assign employee: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'revoke') {
        $target_user_id = intval($_POST['target_user_id']);
        if ($target_user_id === $user_id) {
            $error = "You cannot revoke your own Admin access!";
        } else {
            $mysqli->begin_transaction();
            try {
                $stmt_user = $mysqli->prepare("UPDATE user SET role = 'customer' WHERE userId = ?");
                $stmt_user->bind_param("i", $target_user_id);
                $stmt_user->execute();
                
                $stmt_emp = $mysqli->prepare("DELETE FROM employee WHERE userId = ?");
                $stmt_emp->bind_param("i", $target_user_id);
                $stmt_emp->execute();

                $mysqli->commit();
                $message = "Employee access revoked. They are now a customer.";
            } catch (Exception $e) {
                $mysqli->rollback();
                $error = "Failed to revoke access.";
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $target_user_id = intval($_POST['target_user_id']);
        $new_name = trim($_POST['name']);
        $new_contact = trim($_POST['contactNumber']);
        $new_branch_id = intval($_POST['branchId']);
        $new_role = $_POST['emp_role']; 
        
        $mysqli->begin_transaction();
        try {
            $stmt_user = $mysqli->prepare("UPDATE user SET name = ?, contactNumber = ? WHERE userId = ?");
            $stmt_user->bind_param("ssi", $new_name, $new_contact, $target_user_id);
            $stmt_user->execute();

            $stmt_emp = $mysqli->prepare("UPDATE employee SET branchId = ?, role = ? WHERE userId = ?");
            $stmt_emp->bind_param("isi", $new_branch_id, $new_role, $target_user_id);
            $stmt_emp->execute();

            $mysqli->commit();
            $message = "Employee details updated successfully.";
        } catch (Exception $e) {
            $mysqli->rollback();
            $error = "Failed to update employee details. Database error.";
        }
    }
}

$branches = [];
$branch_res = $mysqli->query("SELECT branchId, name FROM branch");
if ($branch_res) {
    while ($row = $branch_res->fetch_assoc()) {
        $branches[] = $row;
    }
}

$employees = [];
$sql = "SELECT 
            u.userId, u.name, u.email, u.contactNumber,
            e.role AS emp_role, e.branchId, DATE(e.createdAt) as joined_date,
            b.name AS branch_name
        FROM user u
        JOIN employee e ON u.userId = e.userId
        LEFT JOIN branch b ON e.branchId = b.branchId
        WHERE u.role = 'employee'
        ORDER BY e.createdAt DESC";

$res = $mysqli->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $employees[] = $row;
    }
}

function getRoleBadge($role) {
    if ($role === 'Admin') {
        return ['color' => 'bg-red-500 text-white', 'desc' => 'Full Access'];
    } elseif ($role === 'Branch Manager') {
        return ['color' => 'bg-orange-500 text-white', 'desc' => 'Branch Operations'];
    } else {
        return ['color' => 'bg-green-500 text-white', 'desc' => 'Order Processing'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script>
        tailwind.config = {
            theme:{
                extend:{
                    colors:{
                        primary:'oklch(0.7686 0.1647 70.0804)',
                        secondary:'oklch(0.9670 0.0029 264.5419)',
                        muted:'oklch(0.9846 0.0017 247.8389)',
                    },
                    borderRadius:{ custom:'0.375rem' }
                }
            }
        };
    </script>
</head>
<body class="bg-gray-50 min-h-screen font-sans text-gray-800 flex">

    <div class="w-64 bg-white border-r border-gray-200 hidden lg:flex flex-col min-h-screen shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <a href="#" onclick="confirmNav(event, 'home.php', 'Home (Dashboard)')" class="inline-flex items-center text-sm font-bold text-gray-700 hover:text-primary transition">
                <i class='bx bx-left-arrow-alt text-xl mr-1'></i> Back to Site
            </a>
        </div>
        
        <div class="p-6 flex-1">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 pl-4">Main Menu</p>
            <ul class="space-y-2 mb-8">
                <li>
                    <a href="#" onclick="confirmNav(event, 'home.php', 'Home')" class="flex items-center text-gray-800 hover:text-primary hover:bg-orange-50 px-4 py-2.5 rounded-lg transition font-bold">
                        <i class='bx bxs-home text-xl text-gray-500 hover:text-primary mr-3'></i> Home
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmNav(event, '/web/order-location', 'Menu')" class="flex items-center text-gray-800 hover:text-primary hover:bg-orange-50 px-4 py-2.5 rounded-lg transition font-bold">
                        <i class='bx bxs-food-menu text-xl text-gray-500 hover:text-primary mr-3'></i> Menu
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmNav(event, '/orders', 'Orders')" class="flex items-center text-gray-800 hover:text-primary hover:bg-orange-50 px-4 py-2.5 rounded-lg transition font-bold">
                        <i class='bx bxs-bowl-hot text-xl text-gray-500 hover:text-primary mr-3'></i> Orders
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmNav(event, '/web/branches', 'Branches')" class="flex items-center text-gray-800 hover:text-primary hover:bg-orange-50 px-4 py-2.5 rounded-lg transition font-bold">
                        <i class='bx bxs-building text-xl text-gray-500 hover:text-primary mr-3'></i> Branches
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmNav(event, '/contact', 'Contact')" class="flex items-center text-gray-800 hover:text-primary hover:bg-orange-50 px-4 py-2.5 rounded-lg transition font-bold">
                        <i class='bx bxs-contact text-xl text-gray-500 hover:text-primary mr-3'></i> Contact
                    </a>
                </li>
            </ul>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 pl-4">Admin Console</p>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center text-primary font-bold bg-orange-50 px-4 py-2.5 rounded-lg transition">
                        <i class='bx bx-group text-xl mr-3'></i> Employees
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="p-6 border-t border-gray-200">
            <a href="#" onclick="showLogoutModal(event)" class="flex items-center text-red-500 hover:text-red-700 font-bold transition">
                <i class='bx bx-log-out text-xl mr-3'></i> Logout
            </a>
        </div>
    </div>

    <div class="flex-1 overflow-x-hidden">
        
        <div class="lg:hidden bg-white p-4 border-b border-gray-200 shadow-sm flex items-center">
            <a href="#" onclick="confirmNav(event, 'home.php', 'Home (Dashboard)')" class="inline-flex items-center text-sm font-bold text-gray-700 hover:text-primary transition">
                <i class='bx bx-left-arrow-alt text-xl mr-1'></i> Back to Site
            </a>
        </div>

        <div class="p-6 max-w-7xl mx-auto">
            
            <div class="bg-white rounded-lg border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-start md:items-center shadow-sm mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 flex items-center">
                        User Dashboard <span class="text-gray-300 font-normal mx-2">|</span> <span class="text-lg font-medium text-gray-600">Employee Management</span>
                    </h1>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class='bx bx-info-circle mr-1'></i> This module is strictly for managing employee access. Customers are not displayed here.
                    </p>
                </div>
                <button onclick="showModal('assignModal')" class="mt-4 md:mt-0 bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-md text-sm font-bold shadow-md transition flex items-center">
                    <i class='bx bx-user-plus text-lg mr-2'></i> Assign New Employee
                </button>
            </div>

            <?php if (!empty($message)): ?>
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6 shadow-sm">
                    <p class="font-bold flex items-center"><i class='bx bx-check-circle mr-2 text-xl'></i> Success</p>
                    <p class="text-sm ml-7"><?= htmlspecialchars($message) ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 shadow-sm">
                    <p class="font-bold flex items-center"><i class='bx bx-error-circle mr-2 text-xl'></i> Error</p>
                    <p class="text-sm ml-7"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-end">
                <div class="w-full md:w-1/3">
                    <label class="block text-xs font-bold text-gray-600 mb-1">Search User</label>
                    <div class="relative">
                        <i class='bx bx-search absolute left-3 top-2.5 text-gray-400 text-lg'></i>
                        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search name or email..." class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 text-sm focus:ring-1 focus:ring-primary focus:outline-none">
                    </div>
                </div>
                <div class="flex gap-4 w-full md:w-2/3 md:justify-end">
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-bold text-gray-600 mb-1">Filter by Branch</label>
                        <select id="branchFilter" onchange="filterTable()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-primary focus:outline-none bg-white">
                            <option value="">All Branches</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= htmlspecialchars($b['name']) ?>"><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="block text-xs font-bold text-gray-600 mb-1">Filter by Role</label>
                        <select id="roleFilter" onchange="filterTable()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-1 focus:ring-primary focus:outline-none bg-white">
                            <option value="">All Roles</option>
                            <option value="Admin">Admin</option>
                            <option value="Branch Manager">Branch Manager</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="employeeTable">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                <th class="px-6 py-4 w-1/5">Name</th>
                                <th class="px-6 py-4 w-1/4">Email & Contact</th>
                                <th class="px-6 py-4 w-1/6">Role</th>
                                <th class="px-6 py-4 w-1/5">Branch</th>
                                <th class="px-6 py-4 w-1/6">Joined Date</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($employees)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        No employees found in the system.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($employees as $emp): ?>
                                    <tr class="hover:bg-gray-50/50 transition emp-row">
                                        
                                        <td class="px-6 py-4 font-bold text-gray-900 emp-name text-sm">
                                            <?= htmlspecialchars($emp['name']) ?>
                                        </td>

                                        <td class="px-6 py-4 text-gray-500 emp-email text-sm">
                                            <div class="text-gray-900"><?= htmlspecialchars($emp['email']) ?></div>
                                            <div class="text-xs mt-0.5"><?= htmlspecialchars($emp['contactNumber'] ?: 'N/A') ?></div>
                                        </td>

                                        <td class="px-6 py-4 emp-role">
                                            <?php $badge = getRoleBadge($emp['emp_role']); ?>
                                            <div class="inline-block px-2.5 py-0.5 rounded text-xs font-bold <?= $badge['color'] ?>">
                                                <?= htmlspecialchars($emp['emp_role']) ?>
                                            </div>
                                            <div class="text-[11px] text-gray-500 mt-1"><?= $badge['desc'] ?></div>
                                            <span class="hidden role-text"><?= htmlspecialchars($emp['emp_role']) ?></span>
                                        </td>

                                        <td class="px-6 py-4 text-gray-600 emp-branch text-sm font-medium">
                                            <?= htmlspecialchars($emp['branch_name'] ?: 'All Branches') ?>
                                        </td>

                                        <td class="px-6 py-4 text-gray-500 text-sm">
                                            <?= htmlspecialchars($emp['joined_date']) ?>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <button onclick="openEditModal(<?= $emp['userId'] ?>, '<?= htmlspecialchars(addslashes($emp['name'])) ?>', '<?= htmlspecialchars(addslashes($emp['contactNumber'] ?? '')) ?>', '<?= htmlspecialchars($emp['emp_role']) ?>', <?= $emp['branchId'] ?: 0 ?>)" 
                                                class="text-primary hover:text-orange-600 transition p-2 bg-orange-50 rounded-md border border-orange-100 hover:bg-orange-100 mr-1" title="Edit Data & Permissions">
                                                <i class='bx bx-edit text-lg'></i>
                                            </button>
                                            <?php if ($emp['userId'] !== $user_id): ?>
                                                <button onclick="openRevokeModal(<?= $emp['userId'] ?>, '<?= htmlspecialchars(addslashes($emp['name'])) ?>')" 
                                                    class="text-red-500 hover:text-red-700 transition p-2 bg-red-50 rounded-md border border-red-100 hover:bg-red-100" title="Revoke Access">
                                                    <i class='bx bx-user-minus text-lg'></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="assignModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl w-full max-w-md mx-4 shadow-2xl overflow-hidden" id="assignModalContent">
            <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white flex items-center"><i class='bx bx-user-plus mr-2'></i> Assign New Employee</h3>
                <button type="button" onclick="closeModal('assignModal')" class="text-gray-300 hover:text-white transition">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <form action="" method="POST" class="p-6">
                <input type="hidden" name="action" value="assign_new">
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-100 text-blue-700 p-3 rounded-md text-xs mb-2">
                        <i class='bx bx-info-circle mr-1'></i> Enter the email of a registered customer to upgrade their account.
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">User Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required placeholder="user@floudemo.com" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                            <option value="Staff">Staff</option>
                            <option value="Branch Manager">Branch Manager</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Branch Assigned To <span class="text-red-500">*</span></label>
                        <select name="branchId" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                            <option value="0">All Branches (HQ)</option>
                            <?php foreach ($branches as $b): ?>
                                <option value="<?= $b['branchId'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('assignModal')" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-black font-bold hover:bg-slate-800 hover:text-white transition">Assign Employee</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl w-full max-w-md mx-4 shadow-2xl overflow-hidden" id="editModalContent">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 flex items-center"><i class='bx bx-edit mr-2 text-primary'></i> Edit Details & Permissions</h3>
                <button type="button" onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-800 transition">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <form action="" method="POST" class="p-6">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="target_user_id" id="edit_user_id">
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Contact No.</label>
                            <input type="text" name="contactNumber" id="edit_contact" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                        </div>
                    </div>

                    <hr class="border-gray-200 my-2">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
                            <select name="emp_role" id="edit_role" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                                <option value="Staff">Staff</option>
                                <option value="Branch Manager">Branch Manager</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Branch</label>
                            <select name="branchId" id="edit_branch" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-primary focus:outline-none">
                                <option value="0">All Branches (HQ)</option>
                                <?php foreach ($branches as $b): ?>
                                    <option value="<?= $b['branchId'] ?>"><?= htmlspecialchars($b['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-primary text-black font-bold hover:bg-slate-800 hover:text-white transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="revokeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl text-center" id="revokeModalContent">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4 border border-red-200">
                <i class='bx bx-user-minus text-3xl text-red-600'></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Revoke Access</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to change <span id="revoke_emp_name" class="font-bold text-gray-800"></span> to a regular customer? They will lose all backend dashboard access immediately.</p>
            
            <form action="" method="POST">
                <input type="hidden" name="action" value="revoke">
                <input type="hidden" name="target_user_id" id="revoke_user_id">
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="closeModal('revokeModal')" class="px-5 py-2.5 rounded-md border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition w-1/2">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-md bg-red-600 text-white font-bold hover:bg-red-700 transition w-1/2 shadow-md shadow-red-500/30">Yes, Revoke</button>
                </div>
            </form>
        </div>
    </div>

    <div id="navConfirmModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl text-center" id="navConfirmModalContent">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-blue-50 mb-4 border border-blue-200">
                <i class='bx bx-link-external text-3xl text-blue-500'></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Confirm Navigation</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to leave this dashboard and go to <span id="nav_target_name" class="font-bold text-primary"></span>?</p>
            
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeModal('navConfirmModal')" class="px-5 py-2.5 rounded-md border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition w-1/2">Cancel</button>
                <button type="button" id="confirmNavBtn" class="px-5 py-2.5 rounded-md bg-primary text-black font-bold hover:bg-slate-800 hover:text-white transition w-1/2 shadow-md shadow-primary/30">Yes, Go</button>
            </div>
        </div>
    </div>

    <div id="logoutModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4 shadow-2xl text-center" id="logoutModalContent">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-50 mb-4 border border-red-200">
                <i class='bx bx-log-out-circle text-3xl text-red-600'></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Are you sure you want to sign out?</h3>
            <p class="text-sm text-gray-500 mb-6">You will need to log back in to access the admin console.</p>
            
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeModal('logoutModal')" class="px-5 py-2.5 rounded-md border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition w-1/2">Cancel</button>
                <a href="sign-out.php" class="block px-5 py-2.5 rounded-md bg-red-600 text-white font-bold hover:bg-red-700 transition w-1/2 shadow-md shadow-red-500/30">Sign Out</a>
            </div>
        </div>
    </div>

    <script>
        let pendingNavUrl = '';

        function confirmNav(event, url, pageName) {
            if (event) event.preventDefault();
            pendingNavUrl = url;
            document.getElementById('nav_target_name').textContent = pageName;
            showModal('navConfirmModal');
        }

        document.getElementById('confirmNavBtn').addEventListener('click', function() {
            if (pendingNavUrl) {
                window.location.href = pendingNavUrl;
            }
        });

        function showLogoutModal(event) {
            if (event) event.preventDefault();
            showModal('logoutModal');
        }

        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const branchValue = document.getElementById('branchFilter').value.toLowerCase();
            const roleValue = document.getElementById('roleFilter').value.toLowerCase();
            const rows = document.querySelectorAll('.emp-row');

            rows.forEach(row => {
                const name = row.querySelector('.emp-name').textContent.toLowerCase();
                const email = row.querySelector('.emp-email').textContent.toLowerCase();
                const branch = row.querySelector('.emp-branch').textContent.toLowerCase();
                const role = row.querySelector('.role-text').textContent.toLowerCase();

                const matchSearch = name.includes(searchValue) || email.includes(searchValue);
                const matchBranch = branchValue === "" || branch.includes(branchValue);
                const matchRole = roleValue === "" || role === roleValue;

                if (matchSearch && matchBranch && matchRole) {
                    row.style.display = ""; 
                } else {
                    row.style.display = "none"; 
                }
            });
        }

        function openEditModal(userId, name, contact, role, branchId) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_contact').value = contact;
            document.getElementById('edit_role').value = role ? role : 'Staff';
            document.getElementById('edit_branch').value = branchId ? branchId : 0;
            showModal('editModal');
        }

        function openRevokeModal(userId, name) {
            document.getElementById('revoke_user_id').value = userId;
            document.getElementById('revoke_emp_name').textContent = name;
            showModal('revokeModal');
        }

        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>