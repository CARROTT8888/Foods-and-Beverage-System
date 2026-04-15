<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAME Melaka - Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
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
                    borderRadius: { custom: '0.375rem' }
                }
            }
        };
    </script>
    <style>
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; }
        .star-rating input:checked ~ label svg { fill: oklch(0.7686 0.1647 70.0804); }
        .star-rating label:hover svg, .star-rating label:hover ~ label svg { fill: oklch(0.9869 0.0214 95.2774); }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
        .error-shake { animation: shake 0.2s ease-in-out 0s 2; border: 2px solid oklch(0.6368 0.2078 25.3313) !important; }
    </style>
</head>
<body class="bg-muted text-foreground p-4 md:p-10 font-sans">

    <div class="max-w-md mx-auto bg-white rounded-custom shadow-2xl border border-secondary overflow-hidden">
        <div class="bg-primary p-6 text-center border-b border-accent">
            <h1 class="font-black italic text-2xl tracking-tighter">NAME MELAKA</h1>
            <p class="text-[10px] font-bold opacity-75 uppercase tracking-widest italic text-primaryForeground">Customer Voice System</p>
        </div>

        <form id="feedbackForm" class="p-8 space-y-6" novalidate>
            
            <div>
                <label class="block text-[10px] font-black uppercase mb-1">Customer Gmail <span class="text-destructive">*</span></label>
                <input type="email" id="email" required placeholder="yourname@gmail.com" 
                       class="w-full p-3 bg-secondary border border-transparent rounded-custom outline-none focus:ring-2 focus:ring-primary text-sm transition-all">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black uppercase mb-1">Outlet <span class="text-destructive">*</span></label>
                    <select id="branch" required class="w-full p-2 bg-secondary rounded-custom text-xs outline-none border border-transparent">
                        <option value="">-- Select --</option>
                        <option value="aeon">AEON Bandaraya</option>
                        <option value="mahkota">Mahkota Parade</option>
                        <option value="klebang">Klebang DT</option>
                        <option value="ayer_keroh">Ayer Keroh</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase mb-1">Order Via <span class="text-destructive">*</span></label>
                    <select id="method" required class="w-full p-2 bg-secondary rounded-custom text-xs outline-none border border-transparent">
                        <option value="">-- Select --</option>
                        <option value="qr">QR Scan</option>
                        <option value="web">Web App</option>
                    </select>
                </div>
            </div>

            <div id="starBox" class="bg-accent/20 p-5 rounded-custom border border-transparent transition-all">
                <p class="text-center text-[10px] font-black uppercase mb-3 text-accentForeground">Overall Satisfaction <span class="text-destructive">*</span></p>
                <div class="star-rating gap-1">
                    <input type="radio" id="s5" name="rating" value="5" class="hidden" required /><label for="s5" class="cursor-pointer transition-transform hover:scale-110"><svg class="w-10 h-10 fill-gray-200" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
                    <input type="radio" id="s4" name="rating" value="4" class="hidden" /><label for="s4" class="cursor-pointer transition-transform hover:scale-110"><svg class="w-10 h-10 fill-gray-200" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
                    <input type="radio" id="s3" name="rating" value="3" class="hidden" /><label for="s3" class="cursor-pointer transition-transform hover:scale-110"><svg class="w-10 h-10 fill-gray-200" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
                    <input type="radio" id="s2" name="rating" value="2" class="hidden" /><label for="s2" class="cursor-pointer transition-transform hover:scale-110"><svg class="w-10 h-10 fill-gray-200" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
                    <input type="radio" id="s1" name="rating" value="1" class="hidden" /><label for="s1" class="cursor-pointer transition-transform hover:scale-110"><svg class="w-10 h-10 fill-gray-200" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg></label>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase mb-1">Feedback Reason <span id="reasonMark" class="hidden text-destructive">*</span></label>
                <textarea id="comment" rows="3" class="w-full p-3 bg-secondary rounded-custom outline-none focus:ring-2 focus:ring-primary text-sm transition-all" placeholder="Tell us more about your experience..."></textarea>
            </div>

            <button type="submit" class="w-full bg-primary text-primaryForeground font-black py-4 rounded-custom shadow-lg active:scale-95 transition-all uppercase tracking-widest text-xs">Confirm Submission</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('feedbackForm');
        const stars = document.querySelectorAll('input[name="rating"]');
        const comment = document.getElementById('comment');
        const reasonMark = document.getElementById('reasonMark');

        stars.forEach(s => s.addEventListener('change', (e) => {
            const val = parseInt(e.target.value);
            if(val < 3) { comment.required = true; reasonMark.classList.remove('hidden'); comment.placeholder = "Please explain the issue (Required)"; }
            else { comment.required = false; reasonMark.classList.add('hidden'); comment.placeholder = "Tell us more (Optional)"; }
        }));

        form.onsubmit = (e) => {
            let hasErr = false;
            const email = document.getElementById('email');
            const rating = document.querySelector('input[name="rating"]:checked');
            
            [email, comment].forEach(el => el.classList.remove('error-shake'));
            document.getElementById('starBox').classList.remove('error-shake');

            if(!email.value.includes('@')) { email.classList.add('error-shake'); hasErr = true; }
            if(!rating) { document.getElementById('starBox').classList.add('error-shake'); hasErr = true; }
            if(rating && parseInt(rating.value) < 3 && comment.value.trim() === "") { comment.classList.add('error-shake'); hasErr = true; }

            if(hasErr) { e.preventDefault(); }
            else { alert("Success: Your feedback has been recorded."); }
        };
    </script>
</body>
</html>