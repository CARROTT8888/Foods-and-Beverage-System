<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAME Admin - Melaka Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        foreground: 'oklch(0.2686 0 0)',
                        primary: 'oklch(0.7686 0.1647 70.0804)',
                        secondary: 'oklch(0.9670 0.0029 264.5419)',
                        destructive: 'oklch(0.6368 0.2078 25.3313)',
                    },
                    borderRadius: { custom: '0.375rem' }
                }
            }
        };
    </script>
</head>
<body class="bg-muted text-foreground flex min-h-screen font-sans">

    <aside class="w-64 bg-foreground text-white p-6 shadow-2xl flex flex-col">
        <div class="mb-12 text-center">
            <h1 class="text-primary font-black italic text-xl uppercase tracking-tighter">NAME Admin</h1>
            <p class="text-[9px] text-gray-500 uppercase mt-1 tracking-widest">Melaka Command Hub</p>
        </div>
        <nav class="flex-1 space-y-4">
            <a href="#" class="block p-3 bg-primary text-black font-black rounded-custom text-[11px] uppercase tracking-widest text-center shadow-lg">Ratings Feed</a>
            <a href="#" class="block p-3 text-gray-500 hover:text-white transition text-xs font-bold text-center">Outlet Performance</a>
        </nav>
        <div class="pt-6 border-t border-white/10 text-center">
            <p class="text-[9px] text-gray-600 font-bold uppercase tracking-tighter italic">Secured Protocol</p>
        </div>
    </aside>

    <main class="flex-1 p-10 overflow-auto">
        <header class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-black italic uppercase tracking-tighter">Verified Satisfaction Logs</h2>
                <p class="text-xs text-mutedForeground">Direct customer insights from Melaka branches</p>
            </div>
            <div class="bg-white px-5 py-3 rounded-custom border border-secondary shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase">Region Average</p>
                <p class="text-2xl font-black text-foreground">4.5 / 5.0</p>
            </div>
        </header>

        <div class="bg-white rounded-custom border border-secondary shadow-2xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-secondary/30 text-[10px] font-black uppercase text-gray-400 border-b border-secondary">
                    <tr>
                        <th class="p-6">Verified Gmail</th>
                        <th class="p-6">Outlet & Mode</th>
                        <th class="p-6">Customer Score</th>
                        <th class="p-6">Feedback Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary/20">
                    <tr class="bg-destructive/[0.03] hover:bg-destructive/[0.06] transition">
                        <td class="p-6 text-xs font-bold text-destructive">bad_experience@gmail.com</td>
                        <td class="p-6">
                            <span class="bg-foreground text-white text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-tighter">Klebang DT</span>
                            <p class="text-[8px] text-gray-400 mt-1 uppercase font-bold">QR Scan Order</p>
                        </td>
                        <td class="p-6 text-destructive font-black text-xl tracking-widest">★☆☆☆☆</td>
                        <td class="p-6 text-xs italic text-foreground leading-relaxed max-w-sm">"Extremely slow service at the drive-thru. The fried chicken was not as crispy as usual."</td>
                    </tr>

                    <tr class="hover:bg-accent/10 transition">
                        <td class="p-6 text-xs font-bold text-gray-700">happy_tourist@gmail.com</td>
                        <td class="p-6">
                            <span class="bg-gray-200 text-gray-600 text-[9px] px-2 py-0.5 rounded font-black uppercase tracking-tighter">AEON Bandaraya</span>
                            <p class="text-[8px] text-gray-400 mt-1 uppercase font-bold">Web Application</p>
                        </td>
                        <td class="p-6 text-primary font-black text-xl tracking-widest">★★★★★</td>
                        <td class="p-6 text-xs text-gray-400 italic">-- No additional reason provided --</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>