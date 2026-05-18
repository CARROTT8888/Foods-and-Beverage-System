<?php
session_start();

$user_id = $_SESSION['userId'] ?? $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: sign-in.php");
    exit();
}

include "./database/fnbdb.php";

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

if (!isset($mysqli) || !$mysqli instanceof mysqli) {
    die("Database connection failed: Cannot find a valid MySQLi object. Please check the variable name in fnbdb.php");
}

$message = "";
$profile_error = "";
$password_error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
    $new_name = trim($_POST['name'] ?? "");
    $new_address = trim($_POST['address'] ?? "");
    $image_path = $_POST['current_image'] ?? null;
    $redirect_url = $_POST['redirect_url'] ?? "";

    $current_password = $_POST['current_password'] ?? "";
    $new_password = $_POST['new_password'] ?? "";
    $confirm_password = $_POST['confirm_password'] ?? "";

    if (empty($new_name) || strlen($new_name) < 2) {
        $profile_error = "Please enter a valid name.";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $file_size = $_FILES['image']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($file_ext, $allowed_exts)) {
                $profile_error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            } elseif ($file_size > 2097152) {
                $profile_error = "File size must be less than 2MB.";
            } else {
                $new_file_name = uniqid('profile_', true) . '.' . $file_ext;
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir))
                    mkdir($upload_dir, 0777, true);

                $destination = $upload_dir . $new_file_name;
                if (move_uploaded_file($file_tmp, $destination)) {
                    $image_path = $destination;
                } else {
                    $profile_error = "Failed to upload image.";
                }
            }
        }

        if (empty($profile_error) && (!empty($new_password) || !empty($confirm_password) || !empty($current_password))) {
            if (empty($current_password)) {
                $password_error = "Please enter your current password to make changes.";
            } elseif (empty($new_password)) {
                $password_error = "Please enter a new password.";
            } elseif ($new_password !== $confirm_password) {
                $password_error = "New passwords do not match.";
            } elseif (strlen($new_password) < 8 || !preg_match("/[a-z]/i", $new_password) || !preg_match("/[0-9]/", $new_password)) {
                $password_error = "New password must be at least 8 characters, contain letters and numbers.";
            } else {
                $stmt_pw = $mysqli->prepare("SELECT password FROM user WHERE userId = ?");
                $stmt_pw->bind_param("i", $user_id);
                $stmt_pw->execute();
                $pw_result = $stmt_pw->get_result()->fetch_assoc();

                if (!password_verify($current_password, $pw_result['password'])) {
                    $password_error = "Current password is incorrect.";
                } else {
                    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_pw_stmt = $mysqli->prepare("UPDATE user SET password = ? WHERE userId = ?");
                    $update_pw_stmt->bind_param("si", $new_hash, $user_id);
                    $update_pw_stmt->execute();
                }
            }
        }

        if (empty($profile_error) && empty($password_error)) {
            $sql = "UPDATE user SET name = ?, address = ?, image = ? WHERE userId = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssi", $new_name, $new_address, $image_path, $user_id);
                if ($stmt->execute()) {
                    if (!empty($redirect_url)) {
                        header("Location: " . $redirect_url);
                        exit();
                    }
                    $_SESSION['success_message'] = (!empty($new_password)) ? "Profile and password updated successfully!" : "Profile updated successfully!";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $profile_error = "Failed to update profile. Database error.";
                }
            }
        }
    }
}

$sql = "SELECT name, email, contactNumber, address, image FROM user WHERE userId = ?";
$stmt = $mysqli->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if (!$user)
        die("User data not found.");
}

$order_history = [];
$order_sql = "
    SELECT o.orderId, o.createdAt as date, om.methodName as type, o.totalPrice as total, o.orderStatus as status
    FROM `order` o
    JOIN order_method om ON o.methodId = om.methodId
    WHERE o.userId = ?
    ORDER BY o.createdAt DESC
";
$stmt_order = $mysqli->prepare($order_sql);
if ($stmt_order) {
    $stmt_order->bind_param("i", $user_id);
    $stmt_order->execute();
    $order_result = $stmt_order->get_result();
    while ($row = $order_result->fetch_assoc()) {
        $row['id'] = '#ORD-' . $row['orderId'];
        $row['date'] = date("Y-m-d h:i A", strtotime($row['date']));
        $row['total'] = 'RM ' . number_format($row['total'], 2);
        $order_history[] = $row;
    }
}

function getOrderTypeBadge($type)
{
    switch (strtolower($type)) {
        case 'dine in':
            return 'text-green-600 bg-green-50 border-green-200';
        case 'take away':
            return 'text-orange-600 bg-orange-50 border-orange-200';
        case 'delivery':
            return 'text-blue-600 bg-blue-50 border-blue-200';
        default:
            return 'text-gray-600 bg-gray-50 border-gray-200';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        foreground: 'oklch(0.2686 0 0)',
                        primary: 'oklch(0.7686 0.1647 70.0804)',
                        secondary: 'oklch(0.9670 0.0029 264.5419)',
                        muted: 'oklch(0.9846 0.0017 247.8389)',
                        destructive: 'oklch(0.6368 0.2078 25.3313)',
                    },
                    borderRadius: { custom: '0.375rem' }
                }
            }
        };
    </script>
</head>

<body class="bg-white min-h-screen font-sans text-gray-800 relative">

    <div class="border-b border-gray-200 py-4 px-6 flex justify-between items-center shadow-sm">
        <h1 class="text-2xl font-extrabold text-primary flex items-center">
            <img src="./assets/logo.png" class="w-[40px] mr-2" alt="Logo" /> Floudemo
        </h1>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-10 flex flex-col md:flex-row gap-10">

        <div class="w-full md:w-1/4">

            <a href="home.php" onclick="handleNavigation(event, 'home.php', null, false)"
                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-primary mb-8 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>

            <ul class="flex flex-col space-y-4">
                <li>
                    <a href="#" onclick="handleNavigation(event, null, 'profile', false)" id="tab-profile"
                        class="flex justify-between items-center text-primary font-bold text-lg cursor-pointer transition">
                        My Profile
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="border-b border-gray-200 pb-2"></li>

                <li>
                    <a href="#" onclick="handleNavigation(event, null, 'history', false)" id="tab-history"
                        class="flex justify-between items-center text-gray-600 hover:text-primary font-medium text-lg cursor-pointer transition">
                        Order History
                        <svg class="w-5 h-5 opacity-0 transition" id="arrow-history" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </li>

                <li class="pt-6">
                    <a href="#" onclick="handleNavigation(event, null, null, true)"
                        class="text-gray-800 font-medium text-base underline decoration-1 underline-offset-4 hover:text-primary transition">
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <div class="w-full md:w-3/4">
            <div id="section-profile" class="block">
                <h2 class="text-2xl font-bold mb-6">Profile Details</h2>

                <?php if (!empty($profile_error)): ?>
                    <div id="profile-alert"
                        class="bg-red-50 text-destructive p-3 rounded-md mb-6 text-sm border border-red-200 scroll-mt-24">
                        <?= htmlspecialchars($profile_error) ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="bg-green-50 text-green-600 p-3 rounded-md mb-6 text-sm border border-green-200">
                        <?= htmlspecialchars($_SESSION['success_message']) ?>
                    </div>
                    <?php unset($_SESSION['success_message']); // ⚠️ 记得用完立刻删掉，不然下次进页面还会显示 ?>
                <?php endif; ?>

                <form id="profileForm" action="" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-lg"
                    novalidate>
                    <input type="hidden" name="current_image" value="<?= htmlspecialchars($user['image'] ?? '') ?>">
                    <input type="hidden" name="redirect_url" id="redirectUrl" value="">

                    <div class="flex items-center gap-6 mb-4">
                        <div class="relative w-20 h-20">
                            <?php if (!empty($user['image']) && file_exists($user['image'])): ?>
                                <img id="avatarPreview" src="<?= htmlspecialchars($user['image']) ?>" alt="Avatar"
                                    class="w-20 h-20 rounded-full object-cover border border-gray-300 transition-all duration-300">
                            <?php else: ?>
                                <div id="defaultAvatar"
                                    class="w-20 h-20 bg-primary rounded-full flex items-center justify-center text-black text-2xl font-bold uppercase border border-gray-300">
                                    <?= mb_substr(htmlspecialchars($user['name']), 0, 1) ?>
                                </div>
                                <img id="avatarPreview" src="" alt="Avatar"
                                    class="hidden w-20 h-20 rounded-full object-cover border border-gray-300 transition-all duration-300">
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="cursor-pointer text-sm font-medium text-primary hover:underline transition">
                                Change Avatar
                                <input type="file" name="image" accept="image/*" class="hidden" id="imageInput"
                                    onchange="previewImage(this)">
                            </label>
                            <p id="fileNameDisplay" class="text-xs text-gray-400 mt-1"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>"
                            class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                            class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-md px-3 py-2.5 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                        <input type="text" value="<?= htmlspecialchars($user['contactNumber'] ?? '') ?>" disabled
                            class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-md px-3 py-2.5 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                        <textarea name="address" rows="3" placeholder="Enter your full delivery address..."
                            class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition resize-none"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-200">
                        <h3 class="text-xl font-bold mb-2">Change Password</h3>
                        <p class="text-sm text-gray-500 mb-4">Leave fields blank if you do not want to change your
                            password.</p>

                        <?php if (!empty($password_error)): ?>
                            <div id="password-alert"
                                class="bg-red-50 text-destructive p-3 rounded-md mb-6 text-sm border border-red-200 scroll-mt-24">
                                <?= htmlspecialchars($password_error) ?>
                            </div>
                        <?php endif; ?>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <div class="relative">
                                    <input id="current_password" name="current_password" type="password"
                                        placeholder="********"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition pr-10" />
                                    <button type="button" onclick="togglePassword('current_password', 'eyeCurr')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-black">
                                        <svg id="eyeCurr" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <div class="relative">
                                    <input id="new_password" name="new_password" type="password" placeholder="********"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition pr-10" />
                                    <button type="button" onclick="togglePassword('new_password', 'eyeNew')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-black">
                                        <svg id="eyeNew" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                                <p id="newPasswordError" class="text-destructive text-xs mt-1 hidden"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <div class="relative">
                                    <input id="confirm_password" name="confirm_password" type="password"
                                        placeholder="********"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2.5 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition pr-10" />
                                    <button type="button" onclick="togglePassword('confirm_password', 'eyeConf')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-black">
                                        <svg id="eyeConf" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                                <p id="confirmPasswordError" class="text-destructive text-xs mt-1 hidden"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" name="update_profile"
                        class="w-full md:w-auto rounded-md bg-primary px-8 py-3 text-sm font-bold text-black transition hover:bg-slate-800 hover:text-white mt-8 disabled:opacity-50 disabled:cursor-not-allowed">
                        Save Changes
                    </button>
                </form>
            </div>

            <div id="section-history" class="hidden">
                <h2 class="text-2xl font-bold mb-6">Order History</h2>

                <?php if (empty($order_history)): ?>
                    <div class="text-center py-20">
                        <div class="mx-auto w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-1">History is Empty</h3>
                        <p class="text-sm text-gray-500">You don't have any orders just yet. Satisfy your cravings now!</p>
                        <a href="home.php"
                            class="inline-block mt-6 px-6 py-2 bg-primary text-black font-medium rounded-md hover:bg-slate-800 hover:text-white transition">Order
                            Now</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-4 max-w-3xl">
                        <?php foreach ($order_history as $order): ?>
                            <div
                                class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center hover:shadow-md transition">
                                <div class="mb-2 sm:mb-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="font-bold text-gray-800 text-lg"><?= $order['id'] ?></span>
                                        <span
                                            class="text-xs px-2.5 py-1 rounded-md border font-medium <?= getOrderTypeBadge($order['type']) ?>">
                                            <?= $order['type'] ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500"><?= $order['date'] ?></p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="font-bold text-xl text-gray-800"><?= $order['total'] ?></p>
                                    <p class="text-sm font-medium text-gray-600 mt-1"><?= $order['status'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <div id="logoutModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl transform scale-95 opacity-0 transition-all duration-200"
            id="logoutModalContent">
            <div class="flex justify-end mb-2">
                <button type="button" onclick="hideLogoutModal()" class="text-gray-400 hover:text-gray-800 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="text-center mb-8">
                <h3 class="text-xl font-bold text-gray-800">Are you sure you want to sign out?</h3>
            </div>
            <div class="flex justify-center gap-4">
                <button type="button" onclick="hideLogoutModal()"
                    class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition w-1/2">
                    Cancel
                </button>
                <a href="sign-out.php"
                    class="px-6 py-2.5 rounded-full bg-red-600 text-white font-bold hover:bg-red-700 transition w-1/2 text-center">
                    Yes
                </a>
            </div>
        </div>
    </div>

    <div id="unsavedModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl transform scale-95 opacity-0 transition-all duration-200"
            id="unsavedModalContent">
            <div class="text-center mb-6">
                <div
                    class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-yellow-100 mb-4 border border-yellow-200">
                    <svg class="h-7 w-7 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Unsaved Changes</h3>
                <p class="text-sm text-gray-500">You have made changes to your profile. Do you want to save them before
                    leaving?</p>
            </div>

            <div class="flex flex-col gap-3">
                <button type="button" onclick="saveAndProceed()"
                    class="w-full py-2.5 rounded-full bg-primary text-black font-bold hover:bg-slate-800 hover:text-white transition">
                    Save Changes
                </button>
                <button type="button" onclick="proceedWithoutSaving()"
                    class="w-full py-2.5 rounded-full border border-red-200 bg-red-50 text-red-600 font-bold hover:bg-red-100 transition">
                    Discard Changes
                </button>
                <button type="button" onclick="hideUnsavedModal()"
                    class="w-full py-2 text-gray-500 font-medium hover:text-gray-800 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // ==========================================
        // 🚀 全新逻辑：监听表单变化 (Form Dirty Tracker)
        // ==========================================
        let formChanged = false;
        let pendingNavigation = { url: null, tab: null, isLogout: false };

        const profileForm = document.getElementById('profileForm');
        // 只要在表格里打字，或者修改了文件，立刻标记为“已更改”
        profileForm.addEventListener('input', () => { formChanged = true; });
        document.getElementById('imageInput').addEventListener('change', () => { formChanged = true; });

        // 如果用户正常点击底部的 "Save Changes" 按钮提交表单，重置标记
        profileForm.addEventListener('submit', () => { formChanged = false; });


        // ==========================================
        // 🚀 核心逻辑：智能导航拦截
        // ==========================================
        function handleNavigation(event, targetUrl, targetTab, isLogout) {
            if (event) event.preventDefault();

            if (formChanged) {
                // 如果有未保存的更改，拦下动作，记录他想去哪里，然后弹出警告！
                pendingNavigation = { url: targetUrl, tab: targetTab, isLogout: isLogout };
                showUnsavedModal();
            } else {
                // 如果没有更改，直接放行
                executeNavigation({ url: targetUrl, tab: targetTab, isLogout: isLogout });
            }
        }

        // 实际执行跳转或动作的函数
        function executeNavigation(navObj) {
            if (navObj.isLogout) {
                showLogoutModal();
            } else if (navObj.tab) {
                switchTab(navObj.tab);
            } else if (navObj.url) {
                window.location.href = navObj.url;
            }
        }

        // ==========================================
        // 弹窗控制函数 (Unsaved Modal)
        // ==========================================
        function showUnsavedModal() {
            const modal = document.getElementById('unsavedModal');
            const content = document.getElementById('unsavedModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideUnsavedModal() {
            const modal = document.getElementById('unsavedModal');
            const content = document.getElementById('unsavedModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        // 选择：放弃更改，强制离开
        function proceedWithoutSaving() {
            formChanged = false; // 解除锁定
            hideUnsavedModal();
            executeNavigation(pendingNavigation); // 强行执行刚才被拦截的跳转
        }

        // 选择：保存并离开
        function saveAndProceed() {
            // 如果用户原本想跳去某个 URL (比如 home.php)，我们就把这个 URL 偷偷塞进隐藏的 input 里
            if (pendingNavigation.url) {
                document.getElementById('redirectUrl').value = pendingNavigation.url;
            } else if (pendingNavigation.tab) {
                // 如果只是切 tab，在同一个页面，加上 #锚点
                document.getElementById('redirectUrl').value = "user_profile.php#" + pendingNavigation.tab;
            }

            // 点击真实的表单提交按钮
            document.getElementById('submitBtn').click();
        }

        // ==========================================
        // 原有功能 (头像预览、Tab切换、报错滚动等)
        // ==========================================
        function previewImage(input) {
            const display = document.getElementById('fileNameDisplay');
            const previewImg = document.getElementById('avatarPreview');
            const defaultAvatar = document.getElementById('defaultAvatar');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                display.textContent = "Selected: " + file.name;
                display.classList.add('text-primary');

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewImg) {
                        previewImg.src = e.target.result;
                        previewImg.classList.remove('hidden');
                    }
                    if (defaultAvatar) {
                        defaultAvatar.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
            } else {
                display.textContent = "";
            }
        }

        window.addEventListener('load', function () {
            const pwAlert = document.getElementById('password-alert');
            const profileAlert = document.getElementById('profile-alert');
            const successAlert = document.getElementById('success-alert');

            if (pwAlert) pwAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            else if (profileAlert) profileAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            else if (successAlert) successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        function switchTab(tabId, event) {
            if (event) event.preventDefault();
            document.getElementById('section-profile').classList.add('hidden');
            document.getElementById('section-history').classList.add('hidden');
            document.getElementById('tab-profile').classList.remove('text-primary', 'font-bold');
            document.getElementById('tab-profile').classList.add('text-gray-600', 'font-medium');
            document.getElementById('tab-profile').querySelector('svg').classList.add('opacity-0');
            document.getElementById('tab-history').classList.remove('text-primary', 'font-bold');
            document.getElementById('tab-history').classList.add('text-gray-600', 'font-medium');
            document.getElementById('arrow-history').classList.add('opacity-0');
            document.getElementById('section-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).classList.remove('text-gray-600', 'font-medium');
            document.getElementById('tab-' + tabId).classList.add('text-primary', 'font-bold');
            if (tabId === 'profile') document.getElementById('tab-profile').querySelector('svg').classList.remove('opacity-0');
            else document.getElementById('arrow-history').classList.remove('opacity-0');
        }
        if (window.location.hash === '#history') switchTab('history');

        // Logout 弹窗逻辑
        function showLogoutModal(event) {
            if (event) event.preventDefault();
            const modal = document.getElementById('logoutModal');
            const content = document.getElementById('logoutModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            const content = document.getElementById('logoutModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
            }
        }

        const newPassEl = document.getElementById('new_password');
        const confirmPassEl = document.getElementById('confirm_password');
        const currentPassEl = document.getElementById('current_password');
        const submitBtn = document.getElementById('submitBtn');

        function checkPasswordMatch() {
            let isValid = true;
            if (newPassEl.value.length > 0 || confirmPassEl.value.length > 0 || currentPassEl.value.length > 0) {
                if (newPassEl.value.length < 8 || !/[a-zA-Z]/.test(newPassEl.value) || !/[0-9]/.test(newPassEl.value)) {
                    isValid = false;
                }
                if (newPassEl.value !== confirmPassEl.value && confirmPassEl.value.length > 0) {
                    isValid = false;
                    document.getElementById('confirmPasswordError').textContent = "New passwords do not match.";
                    document.getElementById('confirmPasswordError').classList.remove('hidden');
                } else {
                    document.getElementById('confirmPasswordError').classList.add('hidden');
                }
                if (currentPassEl.value.length === 0) isValid = false;
            } else {
                document.getElementById('confirmPasswordError').classList.add('hidden');
                document.getElementById('newPasswordError').classList.add('hidden');
            }
            submitBtn.disabled = !isValid;
        }

        newPassEl.addEventListener('input', checkPasswordMatch);
        confirmPassEl.addEventListener('input', checkPasswordMatch);
        currentPassEl.addEventListener('input', checkPasswordMatch);

        newPassEl.addEventListener('blur', function () {
            const errorEl = document.getElementById('newPasswordError');
            if (this.value.length > 0) {
                if (this.value.length < 8) {
                    errorEl.textContent = "Password must be at least 8 characters.";
                    errorEl.classList.remove('hidden');
                } else if (!/[a-zA-Z]/.test(this.value) || !/[0-9]/.test(this.value)) {
                    errorEl.textContent = "Password must contain at least one letter and one number.";
                    errorEl.classList.remove('hidden');
                } else { errorEl.classList.add('hidden'); }
            } else { errorEl.classList.add('hidden'); }
        });
    </script>
</body>

</html>