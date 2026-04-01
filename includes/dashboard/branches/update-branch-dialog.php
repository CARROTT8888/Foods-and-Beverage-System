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

    if (isset($_POST['status'])) {
        $fields[] = "status=?";
        $params[] = $_POST['status'];
        $types .= "s";
    }

    if (isset($_POST['state'])) {
        $fields[] = "state=?";
        $params[] = $_POST['state'];
        $types .= "s";
    }

    if (isset($_POST['startTime'])) {
        $fields[] = "startTime=?";
        $params[] = trim($_POST['startTime']);
        $types .= "s";
    }

    if (isset($_POST['endTime'])) {
        $fields[] = "endTime=?";
        $params[] = trim($_POST['endTime']);
        $types .= "s";
    }

    if (!empty(trim($_POST['contactNumber']))) {
        $fields[] = "contactNumber=?";
        $params[] = trim($_POST['contactNumber']);
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
        }
        ;
        /*$stmt->close();*/
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateBranchDialog" aria-hidden="true">
    <div class="bg-white rounded-xl p-3 w-106">
        <form method="POST">
            <input type="hidden" name="branchId" id="branchId">

            <label>Name</label>
            <input type="text" name="name" id="updateName" required class="border p-2 w-full">

            <label>Slug</label>
            <input type="text" name="slug" id="updateSlug" readonly class="border p-2 w-full">

            <label>Address</label>
            <input type="text" name="address" id="updateAddress" class="border p-2 w-full">

            <label>Status</label>
            <div class="w-full max-w-sm min-w-[200px]">
                <div class="relative">
                    <select name="status" id="updateStatus"
                        class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                        <option selected disabled value="">Choose a Status</option>
                        <option value="Opening">Opening</option>
                        <option value="Closed">Closed</option>
                        <option value="Setup">Setup</option>
                        <option value="Deprecated">Deprecated</option>
                    </select>

                </div>
            </div>

            <label>State</label>
            <div class="w-full max-w-sm min-w-[200px]">
                <div class="relative">
                    <select name="state" id="updateState"
                        class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer">
                        <option selected disabled value="">Choose a State</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Pulau Pinang">Pulau Pinang</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                    </select>
                </div>
            </div>
            <label>Start Time</label>
            <input type="time" name="startTime" id="updateStartTime" class="border p-2 w-full" />
            <label>End Time</label>
            <input type="time" name="endTime" id="updateEndTime" class="border p-2 w-full" />
            <label>Contact Number</label>
            <input type="text" name="contactNumber" id="updateContactNumber" class="border p-2 w-full">
            <button type="submit" class="bg-blue-500 text-white p-2 mt-2">Update</button>
            <button type="button"
                onclick="document.getElementById('updateBranchDialog').classList.add('opacity-0', 'pointer-events-none')">Cancel</button>
        </form>
    </div>
</div>