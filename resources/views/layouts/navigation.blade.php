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
            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="font-black text-slate-900 dark:text-white text-[15px] tracking-tight whitespace-nowrap">
                SUSI STATIONARY
            </span>
        </div>
        <button x-show="!sidebarCollapsed" @click="sidebarOpen = false"
            class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white hover:bg-slate-200 absolute right-4 transition-colors z-50">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    <hr class="border-slate-100 dark:border-slate-800/60 mx-5 mt-3 mb-4 shrink-0 transition-colors">

    {{-- Menu Navigasi --}}
    <nav class="flex-1 px-3 lg:px-4 space-y-1.5 overflow-y-auto custom-scrollbar overflow-x-hidden pt-1 pb-4">
        <p x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="px-3 text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 whitespace-nowrap">
            Menu Utama</p>

        <div x-show="sidebarCollapsed" class="w-4 h-1 bg-slate-200 dark:bg-slate-700 mx-auto rounded-full mb-4 transition-all"></div>

        @php
            $role = Auth::user()->role;

            // Badge counts
            $membershipMenunggu = \App\Models\Membership::where('status', 'menunggu')->count();
            $pesananSiapDiambil = \App\Models\Pesanan::where('status', 'Siap Diambil')->count();

            $adminMenus = [
                ['type' => 'link', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'fa-chart-pie', 'label' => 'Dasbor Analytics', 'badge' => 0],
                ['type' => 'group', 'pattern' => 'admin.barang.*|admin.layanan.*|admin.pengeluaran.*', 'icon' => 'fa-database', 'label' => 'Keuangan & Master', 'badge' => 0, 'children' => [
                    ['route' => 'admin.barang.index', 'pattern' => 'admin.barang.*', 'label' => 'Stok Barang ATK'],
                    ['route' => 'admin.layanan.index', 'pattern' => 'admin.layanan.*', 'label' => 'Tarif Layanan'],
                    ['route' => 'admin.pengeluaran.index', 'pattern' => 'admin.pengeluaran.*', 'label' => 'Ops & Pengeluaran'],
                ]],
                ['type' => 'link', 'route' => 'admin.laporan.index', 'pattern' => 'admin.laporan.*', 'icon' => 'fa-chart-line', 'label' => 'Laporan Rekap', 'badge' => 0],
                ['type' => 'group', 'pattern' => 'admin.users.*|admin.membership.*', 'icon' => 'fa-users-gear', 'label' => 'User Manajemen', 'badge' => $membershipMenunggu, 'children' => [
                    ['route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'label' => 'Semua Pengguna'],
                    ['route' => 'admin.membership.index', 'pattern' => 'admin.membership.*', 'label' => 'Permintaan Member', 'badge' => $membershipMenunggu],
                ]],
            ];

            $kasirMenus = [
                ['type' => 'link', 'route' => 'kasir.dashboard', 'pattern' => 'kasir.dashboard', 'icon' => 'fa-desktop', 'label' => 'Dasbor & Live Antrian', 'badge' => 0],
                ['type' => 'group', 'pattern' => 'kasir.pos.*|kasir.pesanan.*|kasir.riwayat', 'icon' => 'fa-cash-register', 'label' => 'Transaksi POS', 'badge' => $pesananSiapDiambil, 'children' => [
                    ['route' => 'kasir.pos.index', 'pattern' => 'kasir.pos.*', 'label' => 'Menu Kasir'],
                    ['route' => 'kasir.pesanan.masuk', 'pattern' => 'kasir.pesanan.*', 'label' => 'Pesanan Online', 'badge' => $pesananSiapDiambil],
                    ['route' => 'kasir.riwayat', 'pattern' => 'kasir.riwayat', 'label' => 'Riwayat Invoices'],
                ]],
                ['type' => 'group', 'pattern' => 'kasir.membership.*|kasir.pengaturan.*', 'icon' => 'fa-gears', 'label' => 'Manajemen Toko', 'badge' => $membershipMenunggu, 'children' => [
                    ['route' => 'kasir.membership.index', 'pattern' => 'kasir.membership.*', 'label' => 'Setuju Member', 'badge' => $membershipMenunggu],
                    ['route' => 'kasir.pengaturan.index', 'pattern' => 'kasir.pengaturan.*', 'label' => 'Seting Toko & Antrian'],
                ]],
            ];

            $pelangganMenus = [
                ['type' => 'link', 'route' => 'pelanggan.dashboard', 'pattern' => 'pelanggan.dashboard', 'icon' => 'fa-house', 'label' => 'Dasbor & Antrian', 'badge' => 0],
                ['type' => 'group', 'pattern' => 'pelanggan.pesanan.*|pelanggan.riwayat', 'icon' => 'fa-cloud-arrow-up', 'label' => 'Order & Histori', 'badge' => 0, 'children' => [
                    ['route' => 'pelanggan.pesanan.create', 'pattern' => 'pelanggan.pesanan.*', 'label' => 'Pesan Cetak Baru'],
                    ['route' => 'pelanggan.riwayat', 'pattern' => 'pelanggan.riwayat', 'label' => 'Riwayat Saya'],
                ]],
                ['type' => 'link', 'route' => 'pelanggan.membership.index', 'pattern' => 'pelanggan.membership.*', 'icon' => 'fa-id-card', 'label' => 'Menu Keanggotaan', 'badge' => 0],
            ];
            $activeMenus = $role === 'admin' ? $adminMenus : ($role === 'kasir' ? $kasirMenus : $pelangganMenus);

            // Helper for matching pipe-separated patterns
            $isGroupActive = function($patterns) {
                $patterns = explode('|', $patterns);
                foreach($patterns as $pattern) {
                    if (request()->routeIs($pattern)) return true;
                }
                return false;
            }
        @endphp

        @foreach ($activeMenus as $menu)
            @if(isset($menu['type']) && $menu['type'] === 'group')
                @php $isActive = $isGroupActive($menu['pattern']); @endphp
                <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="mb-1">
                    <button @click="if(!sidebarCollapsed) open = !open; else { sidebarCollapsed = false; open = true }"
                        class="w-full flex items-center py-2.5 rounded-xl transition-colors duration-200 group relative
                               {{ $isActive ? 'text-slate-900 dark:text-white font-bold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white font-medium' }}"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'" title="{{ $menu['label'] }}">

                        @if ($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-900 dark:bg-white rounded-r-full shadow-sm z-20 transition-all"></div>
                        @endif

                        <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200 shrink-0 relative z-10
                                    {{ $isActive ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-md dark:shadow-none' : 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 text-slate-700 dark:text-slate-300 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 group-hover:text-slate-900 dark:group-hover:text-white group-hover:shadow-none' }}"
                            :class="!sidebarCollapsed && 'mr-3'">
                            <i class="fa-solid {{ $menu['icon'] }} text-sm"></i>
                            @if($menu['badge'] > 0)
                                <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[8px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-white dark:border-slate-900 z-30 tracking-tighter">{{ $menu['badge'] > 9 ? '9+' : $menu['badge'] }}</span>
                            @endif
                        </div>

                        <span x-show="!sidebarCollapsed"
                            class="text-sm whitespace-nowrap flex-1 text-left transition-opacity duration-300 delay-100">{{ $menu['label'] }}</span>

                        <i x-show="!sidebarCollapsed" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 opacity-50 ml-1" :class="open && 'rotate-180'"></i>
                    </button>

                    <div x-show="open && !sidebarCollapsed" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         class="pl-11 pr-3 mt-1 space-y-1 relative">
                        <!-- Connecting line from icon to items -->
                        <div class="absolute left-[22px] top-0 bottom-4 w-px bg-slate-200 dark:bg-slate-700"></div>
                        
                        @foreach($menu['children'] as $child)
                            @php $isChildActive = request()->routeIs($child['pattern']); @endphp
                            <a href="{{ route($child['route']) }}" class="flex items-center justify-between py-2 px-3 rounded-lg text-xs {{ $isChildActive ? 'bg-slate-100 dark:bg-slate-800/80 text-slate-900 dark:text-white font-bold shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/50 font-medium' }} transition-colors relative">
                                <!-- Dot connector -->
                                <div class="absolute -left-[13px] top-1/2 w-3 h-px bg-slate-200 dark:bg-slate-700"></div>
                                <div class="absolute -left-[14px] top-1/2 -translate-y-1/2 w-1.5 h-1.5 rounded-full {{ $isChildActive ? 'bg-slate-900 dark:bg-white' : 'bg-slate-200 dark:bg-slate-700' }}"></div>
                                
                                <span>{{ $child['label'] }}</span>
                                @if(isset($child['badge']) && $child['badge'] > 0)
                                    <span class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $child['badge'] }}</span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                @php $isActive = request()->routeIs($menu['pattern']); @endphp
                <a href="{{ route($menu['route']) }}"
                    class="flex items-center py-2.5 rounded-xl transition-colors duration-200 group overflow-hidden relative mb-1
                           {{ $isActive ? 'bg-slate-100 dark:bg-slate-800/80 text-slate-900 dark:text-white font-bold' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white font-medium' }}"
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'" title="{{ $menu['label'] }}">

                    @if ($isActive)
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-slate-900 dark:bg-white rounded-r-full shadow-sm z-20 transition-all"></div>
                    @endif

                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-200 shrink-0 relative z-10 
                                {{ $isActive ? 'bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 shadow-md dark:shadow-none' : 'bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 text-slate-700 dark:text-slate-300 group-hover:bg-slate-200 dark:group-hover:bg-slate-700 group-hover:text-slate-900 dark:group-hover:text-white group-hover:shadow-none' }}"
                        :class="!sidebarCollapsed && 'mr-3'">
                        <i class="fa-solid {{ $menu['icon'] }} text-sm"></i>
                        @if(isset($menu['badge']) && $menu['badge'] > 0)
                            <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[8px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-white dark:border-slate-900 z-30 tracking-tighter">{{ $menu['badge'] > 9 ? '9+' : $menu['badge'] }}</span>
                        @endif
                    </div>

                    <span x-show="!sidebarCollapsed"
                        class="text-sm whitespace-nowrap flex-1 transition-opacity duration-300 delay-100">{{ $menu['label'] }}</span>

                    @if(isset($menu['badge']) && $menu['badge'] > 0)
                        <span x-show="!sidebarCollapsed"
                            class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full ml-auto transition-opacity duration-300 delay-150">{{ $menu['badge'] }}</span>
                    @endif
                </a>
            @endif
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

    {{-- Footer Actions: User Profile & Logout --}}
    <div class="p-3 shrink-0 border-t border-slate-100 dark:border-slate-800/60 transition-all flex"
        :class="sidebarCollapsed ? 'flex-col items-center gap-3' : 'items-center justify-between'">
        
        {{-- User Profile Area --}}
        <div @click="$dispatch('open-profile-modal')"
            class="flex items-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-all duration-300 group flex-1 overflow-hidden border border-transparent hover:border-slate-200 dark:hover:border-slate-700"
            :class="sidebarCollapsed ? 'justify-center p-0 w-full' : 'p-2'">
            
            @php
                $nameParts = explode(' ', Auth::user()->nama_lengkap);
                $initials = count($nameParts) > 1 
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1)) 
                            : strtoupper(substr(Auth::user()->nama_lengkap, 0, 2));
            @endphp

            <div class="w-9 h-9 rounded-lg shadow-sm flex items-center justify-center text-white dark:text-slate-900 font-bold text-[11px] bg-slate-900 dark:bg-slate-100 border border-slate-700 dark:border-slate-300 shrink-0 group-hover:ring-2 group-hover:ring-slate-300 dark:group-hover:ring-slate-600 transition-all duration-300"
                :class="!sidebarCollapsed && 'mr-3'">
                {{ $initials }}
            </div>

            <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex-1 overflow-hidden whitespace-nowrap">
                <p class="text-[13px] font-bold text-slate-800 dark:text-white truncate group-hover:text-black dark:group-hover:text-slate-200 transition-colors">
                    {{ $nameParts[0] }}
                </p>
                <p class="text-[9px] font-black uppercase tracking-widest mt-0.5 text-slate-500 dark:text-slate-400">
                    {{ Auth::user()->role }}
                </p>
            </div>
        </div>

        {{-- Logout Button --}}
        <button type="button" @click="showLogoutModal = true"
            class="flex items-center justify-center shrink-0 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-200 group border border-transparent hover:shadow-md"
            title="Logout dari Aplikasi"
            :class="sidebarCollapsed ? 'w-9 h-9' : 'w-9 h-9 ml-2'">
            <i class="fa-solid fa-power-off text-[13px]"></i>
        </button>
    </div>
</aside>
