<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $toko->nama_toko ?? 'Susi Stationary' }} - Solusi Kebutuhan Cetak & ATK</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,600,800,900&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        // Setel 'dark' class secepat mungkin sebelum DOM render (mencegah white flash)
        if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Konfigurasi Tailwind untuk Dark Mode berbasis class
        var tailwind = {
            config: {
                darkMode: 'class',
                theme: { extend: {} }
            }
        };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animasi Scroll Reveal */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
        
        /* Animasi Float */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased selection:bg-blue-600 selection:text-white transition-colors" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <nav :class="scrolled ? 'bg-white/90 dark:bg-slate-900/90 backdrop-blur-md shadow-sm dark:shadow-slate-800 py-4' : 'bg-transparent py-6'" class="fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex justify-between items-center">
            <a href="#" class="flex items-center gap-3 group">
                <img src="{{ asset('images/susi_logo.jpg') }}" alt="Logo" class="w-10 h-10 rounded-xl object-cover shadow-lg transform group-hover:rotate-12 transition-transform">
                <span class="font-black text-xl tracking-tight text-slate-900 dark:text-white transition-colors">{{ $toko->nama_toko ?? 'SUSI STATIONARY' }}</span>
            </a>

            <div class="hidden md:flex items-center space-x-8 font-semibold text-sm">
                <a href="#beranda" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Beranda</a>
                <a href="#layanan" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Layanan Kami</a>
                <a href="#katalog" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Katalog ATK</a>
                
                <div class="flex items-center pl-6 border-l border-slate-200 dark:border-slate-700 space-x-4 transition-colors">
                    @auth
                        <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="bg-slate-900 dark:bg-slate-800 text-white px-5 py-2.5 rounded-xl hover:bg-slate-800 dark:hover:bg-slate-700 transition-all shadow-md shadow-slate-200 dark:shadow-none">Dashboard Saya</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 dark:bg-blue-700 text-white px-5 py-2.5 rounded-xl hover:bg-blue-700 dark:hover:bg-blue-600 transition-all shadow-md shadow-blue-200 dark:shadow-none">Daftar Sekarang</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="relative pt-24 pb-16 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-blue-50 dark:from-slate-900 to-slate-50 dark:to-slate-900 -z-10 transition-colors"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-blue-100 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50 pointer-events-none transition-colors"></div>
        <div class="absolute bottom-10 left-10 w-72 h-72 bg-purple-100 dark:bg-purple-900/20 rounded-full blur-3xl opacity-50 pointer-events-none transition-colors"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="reveal">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold text-xs uppercase tracking-wider mb-6 transition-colors">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 dark:bg-blue-500 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500 dark:bg-blue-600"></span>
                        </span>
                        Buka & Siap Melayani
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white leading-tight mb-6 transition-colors">
                        Kebutuhan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 dark:from-blue-400 to-purple-600 dark:to-purple-400">Alat Tulis & Cetak</span> dalam Satu Tempat.
                    </h1>
                    <p class="text-lg text-slate-600 dark:text-slate-400 mb-8 leading-relaxed max-w-xl transition-colors">
                        Selamat datang di {{ $toko->nama_toko ?? 'Susi Stationary' }}. Kami menyediakan layanan fotokopi, penjilidan, cetak dokumen berkualitas, hingga persediaan alat tulis kantor terlengkap dengan harga bersahabat.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}" class="bg-blue-600 dark:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-xl shadow-blue-200 dark:shadow-none transition-all flex items-center hover:-translate-y-1">
                            <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Pesan Cetak Online
                        </a>
                        <a href="#katalog" class="bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 px-8 py-4 rounded-2xl font-bold hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm transition-all flex items-center hover:-translate-y-1">
                            Lihat Katalog Produk
                        </a>
                    </div>
                </div>
                
                <div class="relative reveal animate-float hidden lg:block">
                    <img src="{{ asset('images/susi_bg.jpg') }}" alt="Susi Stationary Banner" class="rounded-3xl shadow-2xl w-full h-auto object-cover object-center border-4 border-white dark:border-slate-800 transition-colors">
                    
                    <div class="absolute top-1/2 left-0 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 flex items-center gap-4 transition-colors">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-xl transition-colors">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <div>
                            <p class="font-black text-slate-900 dark:text-white transition-colors">Kualitas</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium transition-colors">Cetak Terbaik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="py-16 lg:py-24 bg-white dark:bg-slate-900 relative transition-colors">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-4 transition-colors">Layanan Jasa Utama Kami</h2>
                <p class="text-slate-500 dark:text-slate-400 transition-colors">Pesan layanan jasa cetak atau jilid langsung dari rumah. Unggah dokumen Anda, kami kerjakan, dan tinggal ambil saat selesai!</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($layanan as $item)
                    <div class="reveal bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-3xl p-6 hover:shadow-xl hover:shadow-blue-100/50 dark:hover:shadow-blue-900/20 hover:border-blue-200 dark:hover:border-blue-900/50 transition-all duration-300 group">
                        <div class="w-14 h-14 bg-white dark:bg-slate-800 shadow-sm rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-500 text-2xl mb-6 group-hover:bg-blue-600 dark:group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $item->nama_layanan }}</h3>
                        <div class="flex items-end gap-1 mt-4">
                            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400 transition-colors">Mulai dari</span>
                            <span class="text-2xl font-black text-slate-900 dark:text-white transition-colors">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1 transition-colors">/ {{ $item->satuan }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-slate-500 dark:text-slate-400 reveal transition-colors">
                        <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300 dark:text-slate-600 transition-colors"></i>
                        <p>Layanan belum ditambahkan oleh Admin.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="katalog" class="py-20 bg-slate-50 dark:bg-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 reveal">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-4 transition-colors">Katalog Alat Tulis (ATK)</h2>
                    <p class="text-slate-500 dark:text-slate-400 transition-colors">Temukan perlengkapan kantor dan sekolah dengan harga terjangkau. (Pembelian langsung di toko).</p>
                </div>
                <a href="{{ route('register') }}" class="hidden md:inline-flex items-center text-blue-600 dark:text-blue-400 font-bold hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                    Daftar Pelanggan Sekarang <i class="fa-solid fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @forelse($barang as $item)
                    <div class="reveal bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                        <div class="absolute top-3 right-3 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-md transition-colors">
                            Stok: {{ $item->stok }}
                        </div>
                        <div class="w-full h-32 bg-slate-50 dark:bg-slate-900/50 rounded-xl mb-4 flex items-center justify-center text-slate-300 dark:text-slate-600 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 transition-colors">
                            <i class="fa-solid fa-box-open text-4xl group-hover:text-blue-300 dark:group-hover:text-blue-500 transition-colors"></i>
                        </div>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mb-1 transition-colors">{{ $item->kode_barang }}</p>
                        <h4 class="font-bold text-slate-800 dark:text-white leading-tight mb-2 line-clamp-2 transition-colors">{{ $item->nama_barang }}</h4>
                        <p class="text-blue-600 dark:text-blue-400 font-black text-lg mt-auto transition-colors">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-slate-500 dark:text-slate-400 reveal transition-colors">
                        <i class="fa-solid fa-box-open text-4xl mb-3 text-slate-300 dark:text-slate-600 transition-colors"></i>
                        <p>Katalog barang sedang kosong.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 dark:bg-slate-950 pt-20 pb-10 border-t border-slate-800 dark:border-slate-900 transition-colors">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 reveal">
            <div class="bg-gradient-to-r from-blue-600 dark:from-blue-700 to-blue-800 dark:to-blue-900 rounded-3xl p-10 lg:p-14 text-center text-white mb-20 relative overflow-hidden shadow-2xl transition-colors">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full opacity-5 blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-black rounded-full opacity-20 blur-3xl transform -translate-x-1/2 translate-y-1/2"></div>
                
                <h2 class="text-3xl md:text-4xl font-black mb-6 relative z-10 text-white">Siap Untuk Mencetak Dokumen Anda?</h2>
                <p class="text-blue-100 mb-8 max-w-2xl mx-auto text-lg relative z-10">Daftar sekarang sebagai pelanggan untuk mengunggah file Anda dari mana saja. Tanpa antri, tanpa ribet.</p>
                <a href="{{ route('register') }}" class="inline-block bg-white dark:bg-slate-800 text-blue-700 dark:text-blue-400 px-8 py-4 rounded-2xl font-black hover:bg-slate-50 dark:hover:bg-slate-700 shadow-lg transition-transform hover:-translate-y-1 relative z-10">
                    Mulai Buat Pesanan
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-center border-t border-slate-800 dark:border-slate-800/50 pt-8 transition-colors">
                <div>
                    <h3 class="text-2xl font-black text-white mb-2 flex items-center"><img src="{{ asset('images/susi_logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover mr-3"> {{ $toko->nama_toko ?? 'SUSI STATIONARY' }}</h3>
                    <p class="text-slate-400 dark:text-slate-500 text-sm mb-1 transition-colors"><i class="fa-solid fa-location-dot w-5"></i> {{ $toko->alamat ?? 'Jl. Pramuka, Bengkalis' }}</p>
                    <p class="text-slate-400 dark:text-slate-500 text-sm transition-colors"><i class="fa-brands fa-whatsapp w-5"></i> {{ $toko->no_telp ?? '0812-3456-7890' }}</p>
                </div>
                <div class="md:text-right text-slate-500 dark:text-slate-600 text-sm transition-colors">
                    <p>&copy; {{ date('Y') }} {{ $toko->nama_toko ?? 'Susi Stationary' }}. All rights reserved.</p>
                    <p class="mt-1">Dibuat dengan <i class="fa-solid fa-heart text-red-500 mx-1"></i> untuk efisiensi toko Anda.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const reveals = document.querySelectorAll('.reveal');
            
            const revealOnScroll = () => {
                const windowHeight = window.innerHeight;
                const elementVisible = 100;
                
                reveals.forEach((reveal) => {
                    const elementTop = reveal.getBoundingClientRect().top;
                    if (elementTop < windowHeight - elementVisible) {
                        reveal.classList.add('active');
                    }
                });
            };

            window.addEventListener('scroll', revealOnScroll);
            revealOnScroll(); // Trigger pertama kali load
        });
    </script>
</body>
</html>