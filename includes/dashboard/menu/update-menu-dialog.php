<?php
$food = null;

// 1. Load existing data for the form
if (!empty($_GET['foodId'])) {
    $foodId = intval($_GET['foodId']);
    $stmt = $conn->prepare("SELECT * FROM food WHERE foodId = ?");
    $stmt->bind_param("i", $foodId);
    $stmt->execute();
    $result = $stmt->get_result();
    $food = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateMenu'])) {
    $foodId = intval($_POST['foodId']);
    $newFoodName = trim($_POST['name'] ?? '');
    $newBranchId = intval($_POST['branchId'] ?? '');

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

    if (!empty(trim($_POST['description']))) {
        $fields[] = "description=?";
        $params[] = trim($_POST['description']);
        $types .= "s";
    }

    if (!empty($_POST['basePrice'])) {
        $fields[] = "basePrice=?";
        $params[] = trim($_POST['basePrice']);
        $types .= "d";
    }

    if (isset($_POST['status'])) {
        $fields[] = "status=?";
        $params[] = $_POST['status'];
        $types .= "s";
    }

    if (!empty($_POST['branchId'])) {
        $fields[] = "branchId=?";
        $params[] = intval($_POST['branchId']);
        $types .= "i";
    }

    if (!empty($_POST['categoryId'])) {
        $fields[] = "categoryId=?";
        $params[] = intval($_POST['categoryId']);
        $types .= "i";
    }

    // IMAGE UPLOAD (optional)
    $imageName = $_POST['old_image'] ?? null;

    // remove image checkbox
    if (isset($_POST['remove_image'])) {
        $imageName = null;
    }

    // upload new image if user selected
    if (!empty($_FILES['image']['name'])) {

        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/Foods-and-Beverage-System/uploads/menus/";

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
        $newFileName = "menu_" . time() . "_" . rand(1000, 9999) . "." . $fileExt;

        $targetFile = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imageName = $newFileName;
        } else {
            die("Image upload failed.");
        }
    }

    if (isset($_POST['remove_image'])) {
        $fields[] = "image=?";
        $params[] = null;
        $types .= "s";
    } else if (!empty($_FILES['image']['name'])) {
        // upload new image code here
        $fields[] = "image=?";
        $params[] = $imageName;
        $types .= "s";
    }

    // Only run if at least one field is being updated
    if (!empty($fields)) {
        $sql = "UPDATE food SET " . implode(', ', $fields) . " WHERE foodId=?";
        $params[] = $foodId;
        $types .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $stmt->close();
            echo "<script>window.location.href='/web/dashboard/menu';</script>";
            exit();
        }

        $stmt->close();
    }
}
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="updateMenuDialog">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-115 p-5 ">
        <form method="POST" enctype="multipart/form-data">
            <div class="flex justify-between mb-4">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Update a Menu</h1>
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
            <input type="hidden" name="foodId" id="updateFoodId">
            <input type="hidden" name="updateMenu" value="1">
            <div class="overflow-y-scroll h-[500px]">
                <div class="">
                    <label for="name" class="block text-sm font-medium text-foreground mb-1 text-start">Name</label>
                    <input type="text" name="name" id="updateName" required
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="description"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Description</label>
                    <input type="text" name="description" id="updateDescription"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                </div>

                <div class="mt-6">
                    <label for="basePrice" class="block text-sm font-medium text-foreground mb-1 text-start">Base Price
                        (RM)</label>
                    <input type="number" step="0.01" name="basePrice" id="updateBasePrice" class="border p-2 w-full">
                </div>

                <div class="mt-6">
                    <label for="status" class="block text-sm font-medium text-foreground mb-1 text-start">Status</label>
                    <select name="status" id="updateStatus"
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                        <option selected disabled value="">Choose a Status</option>
                        <option value="Available">Available</option>
                        <option value="Sold Out">Sold Out</option>
                        <option value="Discontinued">Discontinued</option>
                    </select>
                </div>

                <div class="mt-6">
                    <label for="branchId"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Status</label>
                    <select type="text" id="updateBranch" name="branchId" placeholder="Enter a table code."
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                        <option value="" selected disabled>Select Branch</option>
                        <?php
                        $branchQuery = "SELECT branchId, name FROM branch";
                        $result = $conn->query($branchQuery);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['branchId']}'>{$row['name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mt-6">
                    <label for="categoryId"
                        class="block text-sm font-medium text-foreground mb-1 text-start">Category</label>
                    <select type="text" id="updateCategory" name="categoryId" placeholder="Enter a table code."
                        class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition">
                        <option value="" selected disabled>Please Select a Branch First.</option>
                    </select>
                </div>

                <div class="mt-6">
                    <label for="image" class="block text-sm font-medium text-foreground mb-1 text-start">Upload
                        Image</label>
                    <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($food ? $food['image'] : ''); ?>">

                    <input type="file" class="w-full text-center flex justify-center" name="image"
                        id="updateImageFile" accept="image/png, image/jpeg, image/gif">

                    <div class="text-sm text-secondaryForeground mt-5">
                        Max file size: 2MB (JPG, PNG, GIF)
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">Update</button>
                <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or
                    tab 'ESC' key to close the dialog.</span>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById("updateBranch").addEventListener("change", function () {
        const branchId = this.value;
        const categoryDropdown = document.getElementById("updateCategory");

        // reset category dropdown
        categoryDropdown.innerHTML = `<option value="" selected disabled>Loading...</option>`;

        fetch("/web/includes/dashboard/menu/get_categories_from_branch.php?branchId=" + branchId)
            .then(response => response.json())
            .then(data => {
                categoryDropdown.innerHTML = `<option value="" selected disabled>Select Category</option>`;

                if (data.length === 0) {
                    categoryDropdown.innerHTML = `<option value="" disabled>No categories available</option>`;
                    return;
                }

                data.forEach(category => {
                    const option = document.createElement("option");
                    option.value = category.categoryId;
                    option.textContent = category.name;
                    categoryDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error loading categories:", error);
                categoryDropdown.innerHTML = `<option value="" disabled>Error loading categories</option>`;
            });
    });
</script>
<script>
    document.getElementById("updateImageFile").addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById("previewImage").src = URL.createObjectURL(file);
        }
    });
</script>