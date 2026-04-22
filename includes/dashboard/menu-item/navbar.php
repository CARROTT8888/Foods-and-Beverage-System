<div class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b border-stone-200 px-6 py-3 flex items-center gap-3">
    <div class="">
        <button onclick="window.history.back()"
            class="inline-flex font-sans font-medium text-center transition-all duration-300 ease-in items-center disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed data-[shape=pill]:rounded-full data-[width=full]:w-full focus:shadow-none text-sm rounded-md py- px-2 hover:shadow bg-transparent text-primaryForeground hover:bg-accent hover:text-accentForeground  ">
            <i class='bx bx-chevron-left mr-2a text-3xl'></i>Back
        </button>
    </div>
    <span class="text-sm text-slate-400 ml-2 font-bold">
        Menu > <span class="text-secondaryForeground font-extrabold ml-2"><?php echo htmlspecialchars($food['name']); ?></span>
    </span>
</div>