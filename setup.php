<?php
session_start();

// check if the session variable is exist
if (!isset($_SESSION['userId'])) {
    header("Location: sign-in.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="app.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Floudemo - Setup</title>
    <link rel="Icon" href="../assets/logo.png" sizes="64x64">
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
        }
    </script>
</head>

<body>
    <div data-stepper-container data-initial-step="1" class="w-full">
        <div class="flex w-full items-center justify-between">
            <div aria-disabled="false" data-step class="group w-full flex items-center">
                <div class="relative">
                    <span
                        class="relative grid h-10 w-10 place-items-center rounded-full bg-slate-200 group-data-[active=true]:bg-slate-800 group-data-[active=true]:text-white group-data-[completed=true]:bg-slate-800 group-data-[completed=true]:text-white">
                        <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-6 w-6">
                            <path
                                d="M17 21H7C4.79086 21 3 19.2091 3 17V10.7076C3 9.30887 3.73061 8.01175 4.92679 7.28679L9.92679 4.25649C11.2011 3.48421 12.7989 3.48421 14.0732 4.25649L19.0732 7.28679C20.2694 8.01175 21 9.30887 21 10.7076V17C21 19.2091 19.2091 21 17 21Z"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9 17H15" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </span>
                </div>
                <div class="flex-1 h-1 bg-slate-200 group-data-[completed=true]:bg-slate-800"></div>
            </div>
            <div aria-disabled="true" data-step class="group w-full flex items-center">
                <div class="relative">
                    <span
                        class="relative grid h-10 w-10 place-items-center rounded-full bg-slate-200 group-data-[active=true]:bg-slate-800 group-data-[active=true]:text-white group-data-[completed=true]:bg-slate-800 group-data-[completed=true]:text-white">
                        <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-6 w-6">
                            <path d="M7 18V17C7 14.2386 9.23858 12 12 12V12C14.7614 12 17 14.2386 17 17V18"
                                stroke="currentColor" stroke-linecap="round"></path>
                            <path
                                d="M12 12C13.6569 12 15 10.6569 15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12Z"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5">
                            </circle>
                        </svg>
                    </span>
                </div>
                <div class="flex-1 h-1 bg-slate-200 group-data-[completed=true]:bg-slate-800"></div>
            </div>
            <div aria-disabled="true" data-step class="group flex items-center">
                <div class="relative">
                    <span
                        class="relative grid h-10 w-10 place-items-center rounded-full bg-slate-200 group-data-[active=true]:bg-slate-800 group-data-[active=true]:text-white group-data-[completed=true]:bg-slate-800 group-data-[completed=true]:text-white">
                        <svg width="1.5em" height="1.5em" stroke-width="1.5" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-6 w-6">
                            <path
                                d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path
                                d="M19.6224 10.3954L18.5247 7.7448L20 6L18 4L16.2647 5.48295L13.5578 4.36974L12.9353 2H10.981L10.3491 4.40113L7.70441 5.51596L6 4L4 6L5.45337 7.78885L4.3725 10.4463L2 11V13L4.40111 13.6555L5.51575 16.2997L4 18L6 20L7.79116 18.5403L10.397 19.6123L11 22H13L13.6045 19.6132L16.2551 18.5155C16.6969 18.8313 18 20 18 20L20 18L18.5159 16.2494L19.6139 13.598L21.9999 12.9772L22 11L19.6224 10.3954Z"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <div data-step-content="1" class="step-content hidden">
                <p class="text-xl font-semibold mb-4">Step 1 Content</p>
                <p class="text-slate-500">This is the content for step 1. Add whatever content you need here.</p>
            </div>
            <div data-step-content="2" class="step-content">
                <p class="text-xl font-semibold mb-4">Step 2 Content</p>
                <p class="text-slate-500">This is the content for step 2. Add whatever content you need here.</p>
            </div>
            <div data-step-content="3" class="step-content hidden">
                <p class="text-xl font-semibold mb-4">Step 3 Content</p>
                <p class="text-slate-500">This is the content for step 3. Add whatever content you need here.</p>
            </div>
        </div>

        <div class="mt-6 flex w-full justify-between gap-4">
            <button data-stepper-prev
                class="inline-flex items-center justify-center border align-middle font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-slate-800 border-slate-800 text-slate-50 hover:bg-slate-700 hover:border-slate-700">
                Previous
            </button>
            <button data-stepper-next
                class="inline-flex items-center justify-center border align-middle font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py-2 px-4 shadow-sm hover:shadow-md bg-slate-800 border-slate-800 text-slate-50 hover:bg-slate-700 hover:border-slate-700">
                Next
            </button>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://unpkg.com/@material-tailwind/html@3.0.0-beta.7/dist/material-tailwind.umd.min.js" defer></script>
</body>

</html>