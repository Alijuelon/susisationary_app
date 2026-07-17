<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Susi Stationary' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/susi_logo.jpg') }}">

    <script>
        // Setel 'dark' class secepat mungkin sebelum DOM render (mencegah white flash)
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Konfigurasi Tailwind untuk Dark Mode berbasis class
        // (Diinisialisasi sebelum file CDN dimuat jika memungkinkan, atau sesudahnya di guest page agar seragam)
        var tailwind = {
            config: {
                darkMode: 'class',
                theme: { extend: {} }
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .bg-showcase {
            background-image: url('/images/susi_bg.jpg');
            background-size: cover;
            background-position: center;
        }
        .input-transition {
            transition: all 0.3s ease;
        }
        .input-transition:focus {
            transform: translateY(-2px);
        }
        /* Custom Scrollbar for sidebar form if needed */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased h-screen overflow-hidden selection:bg-blue-600 selection:text-white transition-colors">

    <div class="flex min-h-screen">
        {{-- Kolom Kiri: Area Form --}}
        <div class="w-full lg:w-[45%] xl:w-[40%] flex flex-col h-screen overflow-y-auto px-6 py-8 md:px-12 lg:px-16 animate__animated animate__fadeInLeft bg-white dark:bg-slate-900 transition-colors">
            
            <div class="mb-auto">
                <a href="/" class="inline-flex items-center gap-2 group">
                    <img src="{{ asset('images/susi_logo.jpg') }}" alt="Logo" class="w-8 h-8 md:w-10 md:h-10 rounded-xl object-cover shadow-md transform group-hover:-rotate-12 transition-transform">
                    <span class="font-black text-lg md:text-xl tracking-tight text-slate-900 dark:text-white transition-colors">{{ $toko->nama_toko ?? 'SUSI STATIONARY' }}</span>
                </a>
            </div>

            <div class="w-full max-w-sm mx-auto my-auto py-10">
                {{ $slot }}
            </div>

            <div class="mt-auto pt-8 text-center lg:text-left text-sm text-slate-400 dark:text-slate-500 font-medium transition-colors">
                &copy; {{ date('Y') }} Susi Stationary. All rights reserved.
            </div>
            
        </div>

        {{-- Kolom Kanan: Showcase Branding (Hidden on Mobile) --}}
        <div class="hidden lg:flex lg:w-[55%] xl:w-[60%] relative bg-showcase animate__animated animate__fadeInRight">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 dark:from-slate-900/60 to-purple-900/20 dark:to-slate-800/60 transition-colors"></div>
            
            <div class="relative z-10 flex flex-col justify-center items-center h-full w-full text-white p-16">
                <!-- Decorative Elements -->
                <div class="absolute top-20 right-20 w-32 h-32 bg-blue-500 dark:bg-blue-600 rounded-full blur-[80px] opacity-60 transition-colors"></div>
                <div class="absolute bottom-20 left-20 w-40 h-40 bg-purple-500 dark:bg-purple-600 rounded-full blur-[100px] opacity-60 transition-colors"></div>
                
                <div class="max-w-xl text-center backdrop-blur-sm bg-white/10 dark:bg-slate-900/40 p-10 rounded-3xl border border-white/20 dark:border-slate-700/50 shadow-2xl relative overflow-hidden group transition-colors">
                    <div class="absolute inset-0 bg-gradient-to-tr from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <i class="fa-solid fa-quote-left text-4xl text-blue-300 dark:text-blue-400 md:mb-6 opacity-80 transition-colors"></i>
                    <h2 class="text-3xl lg:text-4xl font-black mb-4 leading-tight text-white">Solusi Terbaik Untuk Kebutuhan Cetak Anda.</h2>
                    <p class="text-blue-100 dark:text-blue-200 text-lg transition-colors">Cepat. Berkualitas. Tanpa Antrian.</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>