<?php
$branch = null;

// load branch first
if (!empty($_GET['branchId'])) {
    $branchId = intval($_GET['branchId']);

    $stmt = $conn->prepare("SELECT * FROM branch WHERE branchId=?");
    $stmt->bind_param("i", $branchId);
    $stmt->execute();
    $result = $stmt->get_result();
    $branch = $result->fetch_assoc();
    //header("Location: /web/dashboard/branches");
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branchId = intval($_POST['branchId']);
    $fields = [];
    $params = [];
    $types = "";

    /*$name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $address = trim($_POST['address']);*/

    if (!empty(trim($_POST['name']))) {
        $fields[] = "name=?";
        $params[] = trim($_POST['name']);
        $types .= "s";
    }

    if (!empty(trim($_POST['slug']))) {
        $fields[] = "slug=?";
        $params[] = trim($_POST['slug']);
        $types .= "s";
    }

    if (isset($_POST['address'])) {
        $fields[] = "address=?";
        $params[] = trim($_POST['address']);
        $types .= "s";
    }

    // Only run if at least one field is being updated
    if (!empty($fields)) {
        $sql = "UPDATE branch SET " . implode(', ', $fields) . " WHERE branchId=?";
        $params[] = $branchId;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $stmt->close();
            /*header("Location: /web/dashboard/branches.php");*/
            echo "<script>window.location.href='/web/dashboard/branches.php';</script>";
            exit();
        };
        /*$stmt->close();*/
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999" id="updateBranchDialog" aria-hidden="true">
    <div class="bg-white rounded-xl p-3 w-106">
        <form method="POST">
            <input type="hidden" name="branchId" id="branchId">
            
            <label>Name</label>
            <input type="text" name="name" id="name" required class="border p-2 w-full">

            <label>Slug</label>
            <input type="text" name="slug" id="slug" required class="border p-2 w-full">

            <label>Address</label>
            <input type="text" name="address" id="address" class="border p-2 w-full">

            <button type="submit" class="bg-blue-500 text-white p-2 mt-2">Update</button>
            <button type="button" onclick="document.getElementById('updateBranchDialog').classList.add('opacity-0', 'pointer-events-none')">Cancel</button>
        </form>
    </div>
</div>