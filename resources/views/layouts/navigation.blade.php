<aside
    :class="[
        sidebarCollapsed ? 'w-[88px]' : 'w-[260px]',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    class="sidebar-transition bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col h-screen fixed lg:static z-40 lg:m-4 lg:rounded-2xl lg:h-[calc(100vh-32px)] shrink-0 shadow-2xl lg:shadow-sm overflow-hidden transition-[width,transform] duration-300 ease-in-out">

    {{-- Logo & Tombol Tutup --}}
    <div class="h-20 flex items-center px-3 lg:px-4 mt-2 shrink-0 relative">
        <div class="flex items-center w-full group cursor-pointer transition-all duration-300"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'">
            <div class="w-8 h-8 rounded-lg bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 flex items-center justify-center shadow-md dark:shadow-none shrink-0 transform transition-transform duration-300 group-hover:scale-105"
                :class="!sidebarCollapsed && 'mr-3'">
                <i class="fa-solid fa-store text-sm"></i>
            </div>
            <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms
                class="font-black text-slate-900 dark:text-white text-[15px] tracking-tight whitespace-nowrap">
                SUSI STATIONARY
            </span>
        </div>
        <button x-show="!sidebarCollapsed" @click="sidebarOpen = false"
            class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white hover:bg-slate-200 absolute right-4 transition-colors z-50">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <hr class="border-slate-100 dark:border-slate-800/60 mx-5 shrink-0 transition-colors">

    {{-- User Profile --}}
    <div class="px-3 lg:px-4 mt-3 shrink-0">
        <div @click="$dispatch('open-profile-modal')"
            class="py-3 flex items-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-all duration-300 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 group"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'">
            @php
                $nameParts = explode(' ', Auth::user()->nama_lengkap);
                $initials =
                    count($nameParts) > 1
                        ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                        : strtoupper(substr(Auth::user()->nama_lengkap, 0, 2));
            @endphp

            <div class="w-8 h-8 rounded-lg shadow-sm flex items-center justify-center text-white dark:text-slate-900 font-bold text-xs bg-slate-900 dark:bg-slate-100 border border-slate-700 dark:border-slate-300 shrink-0 group-hover:ring-2 group-hover:ring-slate-300 dark:group-hover:ring-slate-600 transition-all duration-300"
                :class="!sidebarCollapsed && 'mr-3'">
                {{ $initials }}
            </div>

            <div x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="flex-1 overflow-hidden whitespace-nowrap">
                <p class="text-sm font-bold text-slate-800 dark:text-white truncate group-hover:text-black dark:group-hover:text-slate-200 transition-colors">
                    {{ $nameParts[0] }}
                </p>
                <p class="text-[10px] font-black uppercase tracking-wider mt-0.5 text-slate-500 dark:text-slate-400">
                    {{ Auth::user()->role }}
                </p>
            </div>
        </div>
    </div>

    <hr class="border-slate-100 dark:border-slate-800/60 mx-5 mt-3 mb-4 shrink-0 transition-colors">

    {{-- Menu Navigasi --}}
    <nav class="flex-1 px-3 lg:px-4 space-y-1.5 overflow-y-auto custom-scrollbar overflow-x-hidden pt-1 pb-4">
        <p x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms
            class="px-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 whitespace-nowrap">
            Menu Utama</p>

        <div x-show="sidebarCollapsed" class="w-4 h-1 bg-slate-200 dark:bg-slate-700 mx-auto rounded-full mb-4 transition-all"></div>

        @php
            $role = Auth::user()->role;
            $adminMenus = [
                ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'fa-chart-pie', 'label' => 'Analytics'],
                ['route' => 'admin.barang.index', 'pattern' => 'admin.barang.*', 'icon' => 'fa-boxes-stacked', 'label' => 'Stok Barang'],
                ['route' => 'admin.layanan.index', 'pattern' => 'admin.layanan.*', 'icon' => 'fa-tags', 'label' => 'Harga Layanan'],
                ['route' => 'admin.pengeluaran.index', 'pattern' => 'admin.pengeluaran.*', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Pengeluaran'],
                ['route' => 'admin.laporan.index', 'pattern' => 'admin.laporan.*', 'icon' => 'fa-chart-line', 'label' => 'Laporan'],
            ];
            $kasirMenus = [
                ['route' => 'kasir.dashboard', 'pattern' => 'kasir.dashboard', 'icon' => 'fa-desktop', 'label' => 'Dashboard Kasir'],
                ['route' => 'kasir.pos.index', 'pattern' => 'kasir.pos.*', 'icon' => 'fa-cash-register', 'label' => 'Input Transaksi'],
                ['route' => 'kasir.pesanan.masuk', 'pattern' => 'kasir.pesanan.*', 'icon' => 'fa-globe', 'label' => 'Pesanan Online'],
                ['route' => 'kasir.riwayat', 'pattern' => 'kasir.riwayat', 'icon' => 'fa-receipt', 'label' => 'Riwayat Transaksi'],
                ['route' => 'kasir.pengaturan.index', 'pattern' => 'kasir.pengaturan.*', 'icon' => 'fa-gear', 'label' => 'Pengaturan Struk'],
            ];
            $pelangganMenus = [
                ['route' => 'pelanggan.dashboard', 'pattern' => 'pelanggan.dashboard', 'icon' => 'fa-house', 'label' => 'Dashboard Saya'],
                ['route' => 'pelanggan.pesanan.create', 'pattern' => 'pelanggan.pesanan.*', 'icon' => 'fa-cloud-arrow-up', 'label' => 'Pesan Cetak Baru'],
                ['route' => 'pelanggan.riwayat', 'pattern' => 'pelanggan.riwayat.*', 'icon' => 'fa-clock-rotate-left', 'label' => 'Riwayat & Struk'],
            ];
            $activeMenus = $role === 'admin' ? $adminMenus : ($role === 'kasir' ? $kasirMenus : $pelangganMenus);
        @endphp

        @foreach ($activeMenus as $menu)
            @php $isActive = request()->routeIs($menu['pattern']); @endphp
            <a href="{{ route($menu['route']) }}"
                class="flex items-center py-2.5 rounded-xl transition-colors duration-200 group overflow-hidden relative 
                       {{ $isActive ? 'bg-slate-100 dark:bg-slate-800/80 text-slate-900 dark:text-white font-bold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white font-medium' }}"
                :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'" title="{{ $menu['label'] }}">

                @if ($isActive)
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-900 dark:bg-white rounded-r-full shadow-sm z-20 transition-all"></div>
                @endif

                <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200 shrink-0 relative z-10 
                            {{ $isActive ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-md dark:shadow-none' : 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 text-slate-700 dark:text-slate-300 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 group-hover:text-slate-900 dark:group-hover:text-white group-hover:shadow-none' }}"
                    :class="!sidebarCollapsed && 'mr-3'">
                    <i class="fa-solid {{ $menu['icon'] }} text-sm"></i>
                </div>

                <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms
                    class="text-sm whitespace-nowrap">{{ $menu['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Tombol Toggle Shrink (Hitam) --}}
    <div class="p-2 shrink-0 border-t border-slate-100 dark:border-slate-800/60 hidden lg:flex justify-center mt-auto transition-colors">
        <button @click="sidebarCollapsed = !sidebarCollapsed"
            class="w-full h-10 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:text-white dark:hover:text-slate-900 hover:bg-slate-900 dark:hover:bg-white rounded-xl transition-colors duration-200 focus:outline-none group"
            :title="sidebarCollapsed ? 'Perluas Sidebar' : 'Ciutkan Sidebar'">
            <i class="fa-solid transition-transform duration-200 group-hover:scale-110"
                :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
        </button>
    </div>

    {{-- Logout Trigger --}}
    <div class="p-3 shrink-0 border-t border-slate-100 dark:border-slate-800/60 transition-colors">
        <button type="button" @click="showLogoutModal = true"
            class="flex items-center w-full py-2.5 text-slate-700 dark:text-slate-300 hover:text-white dark:hover:text-slate-900 hover:bg-slate-900 dark:hover:bg-white rounded-xl font-bold transition-colors duration-200 group"
            :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'" title="Logout dari Aplikasi">
            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 shadow-sm transition-colors duration-200 group-hover:bg-slate-900 dark:group-hover:bg-white group-hover:border-transparent"
                :class="!sidebarCollapsed && 'mr-3'">
                <i class="fa-solid fa-power-off text-sm text-slate-700 dark:text-slate-300 group-hover:text-white dark:group-hover:text-slate-900 transition-colors"></i>
            </div>
            <span x-show="!sidebarCollapsed" x-transition.opacity.duration.300ms class="text-sm whitespace-nowrap">Logout</span>
        </button>
    </div>
</aside>
