<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createBranch'])) {
    $name = $_POST['name'];
    $slug = $_POST['slug'];

    $checkNameQuery = $conn->prepare("SELECT branchId FROM branch WHERE name = ?");
    $checkNameQuery->bind_param("s", $name);
    $checkNameQuery->execute();
    $checkNameQuery->store_result();
    if ($checkNameQuery->num_rows > 0) {
        $nameAlreadyExists = true;
        $errorMessage = "The branch's name has already exist.";
        $checkNameQuery->close();
    } else {
        $checkNameQuery->close();
        $branchQuery = "INSERT INTO branch (name, slug) VALUES (?, ?)";
        $checkNameQuery = $conn->prepare($branchQuery);
        $checkNameQuery->bind_param("ss", $name, $slug);
        if ($checkNameQuery->execute()) {
            echo "<script>window.location.href='/web/dashboard/branches';</script>";
            exit();
        }
        ;
    }
}
;
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="branchDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <div class="flex justify-between">
                <h1 class="text-lg text-slate-800 font-semibold">Let's Create a Branch</h1>
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
            <div>
                <label for="name" class="block text-sm font-medium text-foreground mb-1">Name</label>
                <input type="text" name="name" placeholder="name"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-foreground mb-1">Slug</label>
                <input type="text" name="slug" placeholder="slug"
                    class="w-full border border-secondary rounded-custom px-4 py-2 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition"
                    required />
            </div>
            <button type="submit" name="createBranch"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Create
            </button>
            <div class="mt-4 text-center">
                <?php if (!empty($errorMessage)): ?>
                    <div class="text-destructive text-sm">
                        <?php echo htmlspecialchars($errorMessage) ?>
                    </div>
                <?php endif; ?>
                <span class="text-center text-sm mt-4 w-full flex justify-center text-secondaryForeground">Click 'X' or
                    tab 'ESC' key to close the dialog.</span>
            </div>
        </form>
    </div>
</div>