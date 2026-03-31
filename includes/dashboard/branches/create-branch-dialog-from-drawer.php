<div class="fixed inset-0 bg-slate-950/50 flex justify-center items-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out z-9999"
    id="createBranchDialog2" aria-hidden="true">
    <div class="bg-white rounded-xl shadow-2xl shadow-slate-950/5 border border-slate-200 scale-95 w-106 p-3 ">
        <form method="POST" action="" class="p-2 space-y-5">
            <h1 class="text-lg text-slate-800 font-semibold">Let's Create a Branch</h1>
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
            <button type="submit"
                class="w-full rounded-md border bg-primary px-4 py-2 text-center text-sm font-medium text-black transition hover:bg-amber-300">
                Create
            </button>
        </form>
    </div>
</div>