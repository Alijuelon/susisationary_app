<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Susi Stationary') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        // Setel 'dark' class secepat mungkin sebelum DOM render (mencegah white flash)
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Konfigurasi Tailwind untuk Dark Mode berbasis class
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Dukungan scrollbar untuk dark mode */
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }

        .sidebar-transition {
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-image: url('/images/susi_bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-color: rgba(248, 249, 250, 0.85); /* Light mode overlay */
            z-index: -1;
        }
        .dark body::before {
            background-color: rgba(2, 6, 23, 0.85); /* Dark mode overlay */
        }
    </style>
</head>

<body
    class="font-sans antialiased text-slate-600 dark:text-slate-300 flex h-screen overflow-hidden relative"
    x-data="{
        sidebarOpen: false,
        sidebarCollapsed: false,
        showLogoutModal: false,
        showProfileModal: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        init() {
            if (this.darkMode) document.documentElement.classList.add('dark');
            this.$watch('darkMode', val => {
                localStorage.setItem('darkMode', val);
                if (val) document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
            });
        }
    }">

    @php
        $notifications = [];

        if (Auth::check()) {
            $userRole = Auth::user()->role;

            if ($userRole === 'admin') {
                // Stok kritis
                $stokKritis = \App\Models\Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
                if ($stokKritis > 0) {
                    $notifications[] = [
                        'icon' => 'fa-triangle-exclamation',
                        'iconColor' => 'text-red-500',
                        'title' => 'Peringatan Stok!',
                        'message' => $stokKritis . ' item barang stoknya menipis/habis.',
                        'link' => route('admin.barang.index'),
                    ];
                }
                // Membership menunggu
                $memberMenunggu = \App\Models\Membership::where('status', 'menunggu')->count();
                if ($memberMenunggu > 0) {
                    $notifications[] = [
                        'icon' => 'fa-id-card',
                        'iconColor' => 'text-yellow-500',
                        'title' => 'Permohonan Member Baru',
                        'message' => $memberMenunggu . ' permohonan membership menunggu persetujuan.',
                        'link' => route('admin.membership.index'),
                    ];
                }
                // Pesanan online masuk
                $pesananMenunggu = \App\Models\Pesanan::where('status', 'Menunggu')->count();
                if ($pesananMenunggu > 0) {
                    $notifications[] = [
                        'icon' => 'fa-cart-shopping',
                        'iconColor' => 'text-blue-500',
                        'title' => 'Pesanan Online Masuk!',
                        'message' => 'Ada ' . $pesananMenunggu . ' pesanan online baru menunggu diproses.',
                        'link' => route('admin.dashboard'),
                    ];
                }
            } elseif ($userRole === 'kasir') {
                // Pesanan siap diambil
                $pesananSiap = \App\Models\Pesanan::where('status', 'Siap Diambil')->count();
                if ($pesananSiap > 0) {
                    $notifications[] = [
                        'icon' => 'fa-box-open',
                        'iconColor' => 'text-blue-500',
                        'title' => 'Pesanan Siap Diambil',
                        'message' => $pesananSiap . ' pesanan online menunggu pengambilan.',
                        'link' => route('kasir.pos.index'),
                    ];
                }
                // Membership menunggu
                $memberMenunggu = \App\Models\Membership::where('status', 'menunggu')->count();
                if ($memberMenunggu > 0) {
                    $notifications[] = [
                        'icon' => 'fa-id-card',
                        'iconColor' => 'text-yellow-500',
                        'title' => 'Permohonan Member',
                        'message' => $memberMenunggu . ' permohonan membership baru.',
                        'link' => route('kasir.membership.index'),
                    ];
                }
            } elseif ($userRole === 'pelanggan') {
                // Pesanan yang baru diupdate (Siap Diambil)
                $pesananReady = \App\Models\Pesanan::where('id_pelanggan', Auth::id())
                    ->where('status', 'Siap Diambil')->count();
                if ($pesananReady > 0) {
                    $notifications[] = [
                        'icon' => 'fa-bell',
                        'iconColor' => 'text-green-500',
                        'title' => 'Pesanan Siap!',
                        'message' => $pesananReady . ' pesanan Anda sudah siap diambil di toko.',
                        'link' => route('pelanggan.dashboard'),
                    ];
                }
            }
        }

        $notifCount = count($notifications);
    @endphp

    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden cursor-pointer" style="display: none;">
    </div>

    @include('layouts.navigation')

    <main class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">

        <nav
            class="flex items-center justify-between px-6 py-4 mx-4 mt-2 rounded-2xl transition-all duration-300 border border-transparent">

            <div class="flex items-center">
                <button @click="sidebarOpen = true"
                    class="lg:hidden text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 focus:outline-none mr-4 transition-colors">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div>
                    <nav class="flex text-[13px] text-slate-500 dark:text-slate-400 font-medium mb-0.5">
                        <span class="opacity-50">Pages</span>
                        <span class="mx-2">/</span>
                        <span
                            class="text-slate-800 dark:text-slate-200 capitalize">{{ Auth::user()->role ?? 'Aplikasi' }}</span>
                    </nav>
                    <h6 class="font-bold text-slate-800 dark:text-white text-lg capitalize tracking-tight">
                        {{ $header ?? 'Dashboard' }}</h6>
                </div>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-4">

                <button @click="darkMode = !darkMode"
                    class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-500 hover:text-amber-500 dark:text-slate-400 dark:hover:text-amber-300 hover:bg-white dark:hover:bg-slate-800 shadow-sm border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all duration-300"
                    title="Toggle Dark Mode">
                    <i class="fa-solid text-lg" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                <div class="relative hidden sm:block" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                        class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-800 shadow-sm border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition-all duration-300 relative">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if ($notifCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-[#f8f9fa] dark:border-slate-950">{{ $notifCount }}</span>
                        @endif
                    </button>

                    <div x-show="notifOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2" style="display: none;"
                        class="absolute right-0 mt-3 w-72 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 py-2 z-50">
                        <div class="px-4 py-3 border-b border-slate-50 dark:border-slate-800/50">
                            <p
                                class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                Notifikasi Sistem</p>
                        </div>
                        @if ($notifCount > 0)
                            @foreach($notifications as $notif)
                                <a href="{{ $notif['link'] }}"
                                    class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-50 dark:border-slate-800/30 last:border-0">
                                    <p class="text-sm font-bold text-slate-800 dark:text-white"><i
                                            class="fa-solid {{ $notif['icon'] }} {{ $notif['iconColor'] }} mr-2"></i>{{ $notif['title'] }}
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5">{{ $notif['message'] }}</p>
                                    <p
                                        class="text-[10px] text-blue-600 dark:text-blue-400 mt-2 font-bold uppercase tracking-wide">
                                        Cek Sekarang &rarr;</p>
                                </a>
                            @endforeach
                        @else
                            <div class="px-4 py-8 text-center">
                                <i
                                    class="fa-regular fa-bell-slash text-3xl text-slate-300 dark:text-slate-600 mb-3"></i>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Belum ada notifikasi
                                    baru.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center border-l border-slate-200 dark:border-slate-800 pl-4 ml-2">
                    <div @click="showProfileModal = true" class="text-right mr-3 hidden sm:block cursor-pointer group"
                        title="Pengaturan Akun">
                        <p
                            class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate transition-colors">
                            {{ Auth::user()->nama_lengkap }}</p>
                    </div>
                    <button type="button" @click="showLogoutModal = true"
                        class="w-9 h-9 flex items-center justify-center rounded-xl text-red-500 hover:text-white hover:bg-red-500 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white shadow-sm border border-transparent transition-all duration-300"
                        title="Keluar">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </div>
            </div>
        </nav>

        <div class="flex-1 overflow-y-auto px-4 sm:px-6 pb-6 pt-4 relative">

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition.opacity
                    class="mb-6 bg-emerald-500 dark:bg-emerald-600 text-white px-5 py-4 rounded-xl shadow-lg shadow-emerald-200 dark:shadow-none flex items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 w-1/2 transform -skew-x-12 -translate-x-full"></div>
                    <div class="flex items-center space-x-3 relative z-10"><i
                            class="fa-solid fa-circle-check text-xl"></i>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-white hover:text-emerald-200 relative z-10"><i
                            class="fa-solid fa-times text-lg"></i></button>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-transition.opacity
                    class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 px-5 py-4 rounded-xl shadow-sm relative">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-bold text-sm"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Terdapat
                            Kesalahan:</p>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 dark:hover:text-red-300"><i
                                class="fa-solid fa-times"></i></button>
                    </div>
                    <ul class="list-disc list-inside text-xs font-medium space-y-1 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>

    </main>

    {{-- MODAL LOGOUT --}}
    <div x-show="showLogoutModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showLogoutModal" x-transition.opacity
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                @click="showLogoutModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showLogoutModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm w-full border border-slate-100 dark:border-slate-800">
                <div class="px-6 py-8 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1/2 bg-red-50 dark:bg-red-900/10"></div>
                    <div
                        class="w-20 h-20 bg-white dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-100 dark:border-red-900/30 shadow-lg relative z-10">
                        <i class="fa-solid fa-power-off text-3xl text-red-500"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 relative z-10">Konfirmasi Keluar
                    </h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium relative z-10">Apakah Anda yakin
                        ingin mengakhiri sesi ini?</p>
                </div>
                <div
                    class="px-6 py-5 bg-slate-50 dark:bg-slate-800/50 flex justify-center gap-3 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showLogoutModal = false"
                        class="w-1/2 px-5 py-3 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors text-sm">Batal</button>
                    <form method="POST" action="{{ route('logout') }}" class="w-1/2 m-0">
                        @csrf
                        <button type="submit"
                            class="w-full px-5 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 dark:shadow-none transition-transform hover:-translate-y-0.5 text-sm">Ya,
                            Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Toast Notification -->
    <div x-data="{ show: false, message: '' }" 
         @notify.window="message = $event.detail; show = true; setTimeout(() => show = false, 5000)"
         x-show="show" x-transition.opacity style="display: none;"
         class="fixed bottom-5 right-5 z-50 bg-blue-600 text-white px-6 py-4 rounded-xl shadow-xl flex items-center space-x-3 cursor-pointer"
         @click="window.location.reload()">
         <i class="fa-solid fa-bell text-xl animate-bounce"></i>
         <p class="font-bold text-sm" x-text="message"></p>
         <button @click.stop="show = false" class="text-white hover:text-blue-200 ml-4 focus:outline-none">
             <i class="fa-solid fa-times text-lg"></i>
         </button>
    </div>

    <!-- Real-time Order Notification Script untuk Admin -->
    @if(Auth::check() && Auth::user()->role === 'admin')
    <div x-data="orderNotifier()" x-init="initNotifier()"></div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderNotifier', () => ({
                lastCount: {{ \App\Models\Pesanan::where('status', 'Menunggu')->count() }},
                playBeep() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gainNode = ctx.createGain();
                        osc.connect(gainNode);
                        gainNode.connect(ctx.destination);
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, ctx.currentTime);
                        gainNode.gain.setValueAtTime(0.1, ctx.currentTime);
                        osc.start();
                        gainNode.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.5);
                        osc.stop(ctx.currentTime + 0.5);
                    } catch(e) {
                        console.log('Audio error:', e);
                    }
                },
                initNotifier() {
                    setInterval(() => {
                        fetch('{{ route("admin.api.cek-pesanan") }}')
                            .then(res => res.json())
                            .then(data => {
                                if (data.count > this.lastCount) {
                                    this.lastCount = data.count;
                                    this.playBeep();
                                    window.dispatchEvent(new CustomEvent('notify', {
                                        detail: 'Ada ' + data.count + ' pesanan online baru masuk! Klik untuk muat ulang.'
                                    }));
                                } else if (data.count < this.lastCount) {
                                    this.lastCount = data.count;
                                }
                            })
                            .catch(e => console.error('Error fetching notification:', e));
                    }, 5000); // Polling tiap 5 detik
                }
            }));
        });
    </script>
    @endif

    @include('layouts.profil')
</body>

</html>
