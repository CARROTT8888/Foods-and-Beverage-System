<?php
$branch = null;

if (isset($_SESSION['branchId'])) {
    $branchId = $_SESSION['branchId'];

    $stmtBranch = $conn->prepare("SELECT * FROM branch WHERE branchId=?");
    $stmtBranch->bind_param("i", $branchId);
    $stmtBranch->execute();
    $branchResult = $stmtBranch->get_result();
    $branch = $branchResult->fetch_assoc();
    $stmtBranch->close();
}

if (!$branch) {
    return;
}

$branchId = $branch['branchId'];
?>

<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="seatTableDialog" onclick="event.target === this && null">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 w-auto scale-95">
        <div class="p-4 pb-2 flex justify-between items-center">
            <h1 class="text-lg text-slate-800 font-semibold">Please select a seat table</h1>
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
        <div class="p-4 pt-2 text-slate-600 grid grid-cols-3 gap-2">
            <?php
            $branchId = $branch['branchId']; // current branch
            
            $filter = " AND seat_table.branchId = ?";
            $params = [$branchId];
            $types = "i";

            $tableQuery = "SELECT * FROM seat_table WHERE 1" . $filter . " ORDER BY tableId ASC";
            $stmt = $conn->prepare($tableQuery);
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $tableResult = $stmt->get_result();
            if ($tableResult->num_rows > 0):
                while ($data = $tableResult->fetch_assoc()):
                    ?>
                    <div>test</div>
                <?php endwhile; ?>
            <?php endif; ?>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A13</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A10</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-slate-500 bg-slate-500/10 p-2 font-medium text-slate-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A11</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-slate-500 border-slate-500 text-slate-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Blocked</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A12</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-amber-500 bg-amber-500/10 p-2 font-medium text-amber-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M3 9h18M3 15h18M3 21h18" />
                        <circle cx="8" cy="7" r="1" fill="currentColor" />
                        <circle cx="12" cy="13" r="1" fill="currentColor" />
                        <circle cx="16" cy="7" r="1" fill="currentColor" />
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A13</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-amber-500 border-amber-500 text-amber-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Dirty</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A14</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-red-500 bg-red-500/10 p-2 font-medium text-red-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53033 7.46967C7.23744 7.17678 6.76256 7.17678 6.46967 7.46967C6.17678 7.76256 6.17678 8.23744 6.46967 8.53033L10.9393 13L6.46967 17.4697C6.17678 17.7626 6.17678 18.2374 6.46967 18.5303C6.76256 18.8232 7.23744 18.8232 7.53033 18.5303L12 14.0607L16.4697 18.5303C16.7626 18.8232 17.2374 18.8232 17.5303 18.5303C17.8232 18.2374 17.8232 17.7626 17.5303 17.4697L13.0607 13L17.5303 8.53033C17.8232 8.23744 17.8232 7.76256 17.5303 7.46967C17.2374 7.17678 16.7626 7.17678 16.4697 7.46967L12 11.9393L7.53033 7.46967Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A15</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-red-500 border-red-500 text-red-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Occupied</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A16</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A17</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-green-500 bg-green-500/10 p-2 font-medium text-green-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A18</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-green-500 border-green-500 text-green-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Available</span>
                    </div>
                </div>
            </div>
            <div role="alert"
                class="relative flex w-full items-start rounded-none border border-b-0 border-l-4 border-r-0 border-t-0 border-orange-500 bg-orange-500/10 p-2 font-medium text-orange-500">
                <span class="grid shrink-0 place-items-center p-1">
                    <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        color="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25ZM12.5675 17.5008C12.8446 17.1929 12.8196 16.7187 12.5117 16.4416C12.2038 16.1645 11.7296 16.1894 11.4525 16.4973L11.4425 16.5084C11.1654 16.8163 11.1904 17.2905 11.4983 17.5676C11.8062 17.8447 12.2804 17.8197 12.5575 17.5119L12.5675 17.5008Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
                <div class="flex flex-col space-y-2">
                    <div class="m-1.5 w-full font-sans text-base leading-none">A19</div>
                    <div data-shape="pill"
                        class="relative inline-flex w-max items-center border select-none font-sans font-medium rounded-md data-[shape=pill]:rounded-full text-sm p-0.5 shadow-sm bg-orange-500 border-orange-500 text-orange-50">
                        <span class="font-sans text-current leading-none my-1 mx-2.5">Reserved</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 flex justify-end gap-2">
            <button type="button" onclick="closeDialog()"
                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 bg-transparent border-transparent text-red-500 hover:bg-red-500/10 hover:border-red-500/10 shadow-none hover:shadow-none outline-none">Cancel</button>
            <button type="button"
                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-primary text-foreground hover:bg-amber-300 hover:text-secondaryForeground">Get
                Started</button>
        </div>
    </div>
</div>