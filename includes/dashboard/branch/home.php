<section
  class="relative h-screen bg-linear-to-b flex flex-col from-blue-50 via-transparent to-transparent pb-12 pt-8 max-w-7xl w-full">
  <div
    class="rounded-lg border shadow-sm overflow-hidden bg-white border-slate-200 shadow-slate-950/5 h-full w-full max-w-7xl grid grid-cols-2">
    <img class="w-2/5 object-cover" src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.sopandai.com%2Fwp-content%2Fuploads%2F2023%2F01%2FMMU.png.webp&f=1&nofb=1&ipt=ed618de2de637fb9769308656bab69713f756476ef4ce6bd2ae83063b07b3f18"
      alt="card-image" />
    <div class="p-4 h-max w-full">
      <?php if ($branch['status'] === 'Opening'): ?>
        <div
          class="flex items-center gap-2 text-green-500 border border-green-500 bg-green-100 rounded-full text-xs w-auto mx-auto p-1 px-2 absolute">
          <div class="relative flex size-3.5 items-center justify-center">
            <span
              class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping duration-300"></span>
            <span class="relative inline-flex size-2 rounded-full bg-green-600"></span>
          </div>
          <span>Opening</span>
        </div>
      <?php elseif ($branch['status'] === 'Closed'): ?>
        <div
          class="flex items-center gap-2 text-red-500 border border-red-500 bg-red-100 rounded-full text-xs w-auto mx-auto p-1 px-2 absolute">
          <i class='bx bxs-no-entry'></i>
          <span>Closed</span>
        </div>
      <?php elseif ($branch['status'] === 'Setup'): ?>
        <div
          class="flex items-center gap-2 text-amber-500 border border-amber-500 bg-amber-100 rounded-full text-xs w-auto mx-auto p-1 px-2 absolute">
          <i class='bx bxs-time'></i>
          <span>Setup</span>
        </div>
        <?php elseif ($branch['status'] === 'Deprecated'): ?>
        <div
          class="flex items-center gap-2 text-slate-500 border border-slate-500 bg-slate-100 rounded-full text-xs w-auto mx-auto p-1 px-2 absolute">
          <i class='bx bxs-x-circle'></i>
          <span>Deprecated</span>
        </div>
      <?php endif; ?>
      <h1 class="font-sans antialiased font-extrabold text-lg md:text-xl lg:text-2xl text-current mt-7 items-center ">
        <?php echo htmlspecialchars($branch['name']); ?> <i class='bx bx-edit text-lg md:text-xl lg:text-2xl ml-2'></i>
      </h1>
      <div class="mb-5 items-center">
        <p class="font-sans antialiased text-base flex items-center gap-2 ">
          <?php if (!empty($branch['address'])): ?>
            <i class='bx bxs-map text-xl text-primary'></i> <span class="font-medium"><?php echo htmlspecialchars($branch['address']); ?></span>
          <?php else: ?>
            <i class='bx bxs-map text-xl text-primary'></i> <?php echo "<span class='italic text-secondaryForeground'>The address is not released.</span>" ?>
          <?php endif ?>
        </p>
        <div class="flex justify-between">
          <p class="font-sans antialiased text-base flex items-center gap-2 ">
            <?php if (!empty($branch['contactNumber'])): ?>
              <i class='bx bxs-phone text-xl text-primary'></i> <span class="font-medium"><?php echo htmlspecialchars($branch['contactNumber']); ?></span>
            <?php else: ?>
              <i class='bx bxs-phone text-xl text-primary'></i> <?php echo "<span class='italic text-secondaryForeground'>The contact number is not released.</span>" ?>
            <?php endif ?>
          </p>
          <p class="font-sans antialiased text-base flex items-center gap-2 ">
            <?php if (!empty($branch['endTime'])): ?>
              <i class='bx bxs-hourglass text-xl text-primary'></i> <span class="text-green-500 font-medium"><?php echo htmlspecialchars($branch['startTime']); ?></span> - <span class="text-red-500 font-medium"><?php echo htmlspecialchars($branch['endTime']); ?></span>
            <?php else: ?>
              <i class='bx bxs-hourglass text-xl text-primary'></i> <?php echo "<span class='italic text-secondaryForeground'>The opening hour is not scheduled.</span>" ?>
            <?php endif ?>
          </p>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4.5">
        <!-- Card 1 -->
        <div
          class="flex items-center p-2 border border-green-500 bg-green-500/10 hover:border-green/20 transition-colors rounded-xl ">
          <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
          <div class="ml-4">
            <div class="flex items-center gap-2.5">
              <div class="relative flex size-3.5 items-center justify-center">
                <span
                  class="absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75 animate-ping duration-300"></span>
                <span class="relative inline-flex size-2 rounded-full bg-green-600"></span>
              </div>
              <h1 class="text-lg font-bold text-green-900">Opening</h1>
            </div>
            <p class="text-3xl text-green-950 mt-3 font-extrabold">
              -
            </p>
          </div>
        </div>
        <!-- Card 2 -->
        <div
          class="flex items-center p-2 border border-red-500 bg-red-500/10 hover:border-red/20 transition-colors rounded-xl ">
          <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
          <div class="ml-4">
            <div class="flex items-center gap-2.5">
              <div class="relative flex size-3.5 items-center justify-center">
                <i class='bx bxs-no-entry text-red-500'></i>
              </div>
              <h1 class="text-lg font-bold text-red-900">Closed</h1>
            </div>
            <p class="text-3xl text-green-red mt-3 font-extrabold">
              -
            </p>
          </div>
        </div>
        <!-- Card 3 -->
        <div
          class="flex items-center p-2 border border-amber-500 bg-amber-500/10 hover:border-amber/20 transition-colors rounded-xl ">
          <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
          <div class="ml-4">
            <div class="flex items-center gap-2.5">
              <div class="relative flex size-3.5 items-center justify-center">
                <i class='bx bxs-time text-amber-500'></i>
              </div>
              <h1 class="text-lg font-bold text-amber-900">Setup</h1>
            </div>
            <p class="text-3xl text-amber-950 mt-3 font-extrabold">
              -
            </p>
          </div>
        </div>
        <!-- Card 4 -->
        <div
          class="flex items-center p-2 border border-slate-500 bg-slate-500/10 hover:border-slate/20 transition-colors rounded-xl ">
          <!---<div class="rounded-lg object-cover">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" color="currentColor" class="h-25 w-25 text-green-500">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M12 1.25C6.06294 1.25 1.25 6.06294 1.25 12C1.25 17.9371 6.06294 22.75 12 22.75C17.9371 22.75 22.75 17.9371 22.75 12C22.75 6.06294 17.9371 1.25 12 1.25ZM7.53044 11.9697C7.23755 11.6768 6.76268 11.6768 6.46978 11.9697C6.17689 12.2626 6.17689 12.7374 6.46978 13.0303L9.46978 16.0303C9.76268 16.3232 10.2376 16.3232 10.5304 16.0303L17.5304 9.03033C17.8233 8.73744 17.8233 8.26256 17.5304 7.96967C17.2375 7.67678 16.7627 7.67678 16.4698 7.96967L10.0001 14.4393L7.53044 11.9697Z"
                        fill="currentColor">
                    </path>
                </svg>
            </div>--->
          <div class="ml-4">
            <div class="flex items-center gap-2.5">
              <div class="relative flex size-3.5 items-center justify-center">
                <i class='bx bxs-x-circle text-slate-500'></i>
              </div>
              <h1 class="text-lg font-bold text-slate-900">Deprecated</h1>
            </div>
            <p class="text-3xl text-slate-950 mt-3 font-extrabold">
              -
            </p>
          </div>
        </div>
      </div>
      

    </div>
  </div>
</section>