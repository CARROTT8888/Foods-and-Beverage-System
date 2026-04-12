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

    // IMAGE UPLOAD (optional)
    $imageName = $_POST['old_image'] ?? null;

    // remove image checkbox
    if (isset($_POST['remove_image'])) {
        $imageName = null;
    }

    // upload new image if user selected
    if (!empty($_FILES['image']['name'])) {

        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/Foods-and-Beverage-System/uploads/branches/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['image']['type'];
        $fileSize = $_FILES['image']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            die("Only JPG, PNG, GIF files are allowed.");
        }

        if ($fileSize > 2 * 1024 * 1024) {
            die("File size must be less than 2MB.");
        }

        $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $newFileName = "branch_" . time() . "_" . rand(1000, 9999) . "." . $fileExt;

        $targetFile = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imageName = $newFileName;
        } else {
            die("Image upload failed.");
        }
    }

    // IMPORTANT: Add image into update
    $fields[] = "image=?";
    $params[] = $imageName;
    $types .= "s";

    // Only run if at least one field is being updated
    if (!empty($fields)) {
        $sql = "UPDATE branch SET " . implode(', ', $fields) . " WHERE branchId=?";
        $params[] = $branchId;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>window.location.href='/web/dashboard/branches';</script>";
            exit();
        }

        $stmt->close();
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateBranchDialog">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
        <form method="POST" enctype="multipart/form-data">
            <div class="flex justify-between mb-4">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Update a Branch</h1>
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none data-[shape=circular]:rounded-full text-sm min-w-[34px] min-h-[34px] rounded-md bg-transparent border-transparent text-red-500 hover:bg-red-200/10 hover:border-red-200/10 shadow-none hover:shadow-none outline-none">
                    <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-5 w-5">
                        <path
                            d="M6.75827 17.2426L12.0009 12M17.2435 6.75736L12.0009 12M12.0009 12L6.75827 6.75736M12.0009 12L17.2435 17.2426"
                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>
            <input type="hidden" name="branchId" id="branchId">

            <div class="overflow-y-scroll h-[500px]">
                <div class="">
                    <label for="name" class="block text-sm font-medium text-foreground mb-1 text-start">Name</label>
                    <input type="text" name="name" id="updateName" required
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="slug" class="block text-sm font-medium text-foreground mb-1 text-start">Slug</label>
                    <input type="text" name="slug" id="updateSlug" readonly
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="address"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Address</label>
                    <input type="text" name="address" id="updateAddress" class="border p-2 w-full">
                </div>

                <div class="mt-6">
                    <label for="status" class="block text-sm font-medium text-foreground mb-1 text-start">Status</label>

                    <select name="status" id="updateStatus"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                        <option selected disabled value="">Choose a Status</option>
                        <option value="Opening">Opening</option>
                        <option value="Closed">Closed</option>
                        <option value="Setup">Setup</option>
                        <option value="Deprecated">Deprecated</option>
                    </select>
                </div>

                <div class="mt-6">
                    <label for="state" class="block text-sm font-medium text-foreground mb-1 text-start">State</label>

                    <select name="state" id="updateState"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
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

                <div class="mt-6">
                    <label for="startTime" class="block text-sm font-medium text-foreground mb-1 text-start">Start
                        Time</label>
                    <input type="time" name="startTime" id="updateStartTime"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
                </div>

                <div class="mt-6">
                    <label for="endTime" class="block text-sm font-medium text-foreground mb-1 text-start">End
                        Time</label>
                    <input type="time" name="endTime" id="updateEndTime"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition" />
                </div>

                <div class="mt-6">
                    <label for="contactNumber" class="block text-sm font-medium text-foreground mb-1 text-start">Contact
                        Number</label>
                    <input type="text" name="contactNumber" id="updateContactNumber"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="image" class="block text-sm font-medium text-foreground mb-1 text-start">Upload
                        Image (Optional)</label>
                    <input type="hidden" name="old_image"
                        value="<?php echo htmlspecialchars($branch['image'] ?? ''); ?>">

                    <input type="file" name="image" id="updateImageFile" accept="image/png, image/jpeg, image/gif">

                    <div style="font-size: 12px; color: #666; margin-top: 5px;">
                        Max file size: 2MB (JPG, PNG, GIF)
                    </div>

                    <div style="margin-top: 10px;">
                        <p style="margin: 0; font-weight: bold;">Current Image:</p>
                        <img id="previewImage" src="" class="w-full h-48 object-cover">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">Update</button>
            </div>
        </form>
    </div>
</div>