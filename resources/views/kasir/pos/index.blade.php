<x-app-layout>
    <x-slot name="header">Mesin Kasir (POS)</x-slot>

    <div class="w-full h-[calc(100vh-140px)] flex flex-col lg:flex-row gap-6" 
         x-data="posSystem({{ json_encode($barang) }}, {{ json_encode($layanan) }}, {{ json_encode($pesananSiap) }}, {{ json_encode($pengaturan) }})">
        
        {{-- PANEL KIRI: Katalog Item + Pesanan Online --}}
        <div class="w-full lg:w-2/3 flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden relative transition-colors">
            
            {{-- Search & Tab Header --}}
            <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50 shrink-0 transition-colors">
                <div class="relative mb-4">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fa-solid fa-search text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="text" x-model="searchQuery" placeholder="Cari barang atau layanan..." 
                           class="bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 text-gray-700 dark:text-gray-300 text-xs sm:text-sm rounded-xl focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 block w-full pl-10 p-2.5 sm:p-3 shadow-sm transition-all">
                    
                    <button x-show="searchQuery !== ''" @click="searchQuery = ''" class="absolute inset-y-0 right-0 flex items-center pr-4 text-red-500 hover:text-red-700 dark:hover:text-red-400">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <div class="flex space-x-2">
                    <button @click="activeTab = 'barang'" :class="activeTab === 'barang' ? 'bg-gray-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-slate-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all flex-1">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i> Stok ATK
                    </button>
                    <button @click="activeTab = 'layanan'" :class="activeTab === 'layanan' ? 'bg-gray-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-slate-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all flex-1">
                        <i class="fa-solid fa-print mr-1"></i> Layanan
                    </button>
                    <button @click="activeTab = 'online'" :class="activeTab === 'online' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800'" class="px-4 py-2 rounded-lg text-sm font-bold transition-all flex-1 relative">
                        <i class="fa-solid fa-globe mr-1"></i> Online
                        <span x-show="pesananSiap.length > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white dark:border-slate-900" x-text="pesananSiap.length"></span>
                    </button>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="flex-1 overflow-y-auto p-5 custom-scrollbar bg-gray-50/30 dark:bg-slate-900/50 transition-colors">
                
                {{-- Tab Barang --}}
                <div x-show="activeTab === 'barang'" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
                    <template x-for="item in filteredBarang" :key="item.id">
                        <div @click="addToCart(item, 'barang')" class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-3 sm:p-4 cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 hover:shadow-md transition-all group flex flex-col justify-between h-32 sm:h-36 relative overflow-hidden">
                            <div class="absolute top-0 right-0 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400 text-[10px] font-bold px-2 py-1 rounded-bl-lg transition-colors">
                                Stok: <span x-text="item.stok"></span>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase mb-1" x-text="item.kode_barang"></p>
                                <h6 class="text-sm font-bold text-gray-800 dark:text-white leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" x-text="item.nama_barang"></h6>
                            </div>
                            <p class="text-blue-600 dark:text-blue-400 font-bold text-sm mt-2 transition-colors" x-text="formatRupiah(item.harga_jual)"></p>
                        </div>
                    </template>
                    <div x-show="filteredBarang.length === 0" class="col-span-full py-10 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-box-open text-3xl mb-2 text-gray-300 dark:text-slate-600"></i>
                        <p class="text-sm">Barang kosong. Tambahkan dulu di menu Admin.</p>
                    </div>
                </div>

                {{-- Tab Layanan --}}
                <div x-show="activeTab === 'layanan'" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4" style="display: none;">
                    <template x-for="item in filteredLayanan" :key="item.id">
                        <div @click="addToCart(item, 'layanan')" class="bg-gradient-to-br from-blue-50 to-white dark:from-slate-800 dark:to-slate-800 border border-blue-100 dark:border-slate-700 rounded-xl p-3 sm:p-4 cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 hover:shadow-md transition-all group flex flex-col justify-between h-32 sm:h-36">
                            <div>
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 flex items-center justify-center mb-2 transition-colors"><i class="fa-solid fa-tags text-xs"></i></div>
                                <h6 class="text-sm font-bold text-gray-800 dark:text-white leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" x-text="item.nama_layanan"></h6>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Per <span x-text="item.satuan"></span></p>
                                <p class="text-blue-600 dark:text-blue-400 font-bold text-sm transition-colors" x-text="formatRupiah(item.harga_satuan)"></p>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredLayanan.length === 0" class="col-span-full py-10 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-tags text-3xl mb-2 text-gray-300 dark:text-slate-600"></i>
                        <p class="text-sm">Layanan kosong. Tambahkan dulu di menu Admin.</p>
                    </div>
                </div>

                {{-- Tab Pesanan Online --}}
                <div x-show="activeTab === 'online'" class="space-y-3" style="display: none;">
                    <template x-if="pesananSiap.length === 0">
                        <div class="py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-gray-300 dark:text-slate-600"></i>
                            <p class="text-sm">Tidak ada pesanan online yang siap diambil.</p>
                        </div>
                    </template>
                    <template x-for="order in pesananSiap" :key="order.id">
                        <div @click="selectOnlineOrder(order)" 
                             :class="selectedOrderId === order.id ? 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-900/20 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-300 dark:hover:border-blue-600'"
                             class="border rounded-xl p-4 cursor-pointer transition-all">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Pesanan #<span x-text="order.id"></span></p>
                                    <h6 class="text-sm font-bold text-gray-800 dark:text-white mt-1" x-text="order.pelanggan?.nama_lengkap ?? 'Pelanggan'"></h6>
                                </div>
                                <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 px-3 py-1 rounded-md text-[10px] font-bold uppercase">Siap Diambil</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span><i class="fa-solid fa-tags mr-1"></i> <span x-text="order.layanan?.nama_layanan ?? 'Layanan'"></span></span>
                                <span class="font-bold text-blue-600 dark:text-blue-400" x-text="formatRupiah(order.layanan?.harga_satuan ?? 0)"></span>
                            </div>
                            <p x-show="order.catatan" class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 italic" x-text="'Catatan: ' + (order.catatan ?? '')"></p>
                        </div>
                    </template>
                </div>

            </div>
        </div>

        {{-- PANEL KANAN: Keranjang & Checkout --}}
        <div class="w-full lg:w-1/3 flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            
            {{-- Header Keranjang --}}
            <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50 shrink-0 flex justify-between items-center transition-colors">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-cart-shopping mr-2 text-blue-500 dark:text-blue-400"></i> Rincian Pesanan</h3>
                <button @click="clearCart()" x-show="cart.length > 0" class="text-xs font-bold text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors">Kosongkan</button>
            </div>

            {{-- Isi Keranjang --}}
            <div class="flex-1 overflow-y-auto p-4 custom-scrollbar bg-white dark:bg-slate-900 transition-colors">
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-500">
                        <i class="fa-solid fa-receipt text-5xl mb-3 opacity-30"></i>
                        <p class="text-sm font-medium">Keranjang masih kosong</p>
                        <p class="text-xs text-center mt-1">Klik item di sebelah kiri untuk menambah pesanan.</p>
                    </div>
                </template>

                <ul class="space-y-3">
                    <template x-for="(item, index) in cart" :key="index">
                        <li class="p-3 border rounded-xl flex flex-col transition-colors" :class="item.locked ? 'bg-indigo-50/50 dark:bg-indigo-900/10 border-indigo-100 dark:border-indigo-800/50' : 'bg-white dark:bg-slate-800/50 border-gray-100 dark:border-slate-700/50'">
                            <div class="flex justify-between items-start">
                                <div class="pr-2 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h6 class="text-sm font-bold text-gray-800 dark:text-white leading-tight break-all" x-text="item.nama"></h6>
                                        <span x-show="item.locked" class="text-[8px] bg-indigo-500 text-white font-bold px-1.5 py-0.5 rounded shadow-sm whitespace-nowrap"><i class="fa-solid fa-cloud-arrow-down mr-1"></i>ONLINE</span>
                                    </div>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium" x-text="formatRupiah(item.harga) + (item.tipe === 'layanan' ? ' / ' + item.satuan : '')"></p>
                                </div>
                                <button x-show="!item.locked" @click="removeFromCart(index)" class="text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 p-1 w-6 h-6 flex items-center justify-center shrink-0 transition-colors bg-gray-50 dark:bg-slate-800 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            
                            <div class="flex justify-between items-end mt-3 border-t border-dashed border-gray-100 dark:border-slate-700 pt-2">
                                <template x-if="!item.locked">
                                    <div class="flex items-center bg-gray-50 dark:bg-slate-900 rounded-lg p-0.5 border border-gray-200 dark:border-slate-600 transition-colors group">
                                        <button @click="decrementQty(index)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-white dark:hover:bg-slate-800 rounded-md transition-all shadow-sm group-hover:shadow-none"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                        <input type="number" x-model.number="item.qty" @change="validateQty(index)" class="w-8 flex-1 text-center text-xs font-bold border-none focus:ring-0 p-0 text-gray-900 dark:text-white bg-transparent h-6">
                                        <button @click="incrementQty(index)" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:bg-white dark:hover:bg-slate-800 rounded-md transition-all shadow-sm group-hover:shadow-none"><i class="fa-solid fa-plus text-[10px]"></i></button>
                                    </div>
                                </template>
                                <template x-if="item.locked">
                                    <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-100/50 dark:bg-indigo-900/30 px-2 py-1 rounded">Qty: <span x-text="item.qty"></span></span>
                                </template>
                                
                                <div class="text-right">
                                    <p class="font-black text-gray-900 dark:text-white text-[15px]" x-text="formatRupiah(item.harga * item.qty)"></p>
                                </div>
                            </div>
                            <p x-show="item.tipe === 'barang' && item.qty >= item.max_stok" class="text-[9px] text-red-500 font-bold mt-1.5 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded w-max">Stok maksimal tercapai!</p>
                        </li>
                    </template>
                </ul>
            </div>

            {{-- Checkout Area --}}
            <div class="p-4 border-t border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 shrink-0 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] dark:shadow-none transition-colors z-10 w-[360px] lg:w-full max-h-[50vh] overflow-y-auto">
                
                {{-- Detail Pelanggan & Member Grid --}}
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Nama Pembeli</label>
                        <input type="text" x-model="namaPelanggan" placeholder="Isi nama..." class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-lg p-2 text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                
                    <div x-show="pengaturan && pengaturan.membership_aktif">
                        <label class="flex justify-between items-center text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                            <span>ID Member</span>
                            <button x-show="memberInfo" @click="clearMember()" class="text-red-500 hover:text-red-600 focus:outline-none">Hapus</button>
                        </label>
                        <div x-show="!memberInfo" class="flex">
                            <input type="text" x-model="memberQuery" @keydown.enter.prevent="searchMember()" placeholder="No/Nama..." class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white rounded-l-lg p-2 text-xs font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button @click="searchMember()" class="bg-slate-800 dark:bg-slate-600 text-white px-2.5 rounded-r-lg text-xs font-bold hover:bg-slate-900 transition-colors"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        <div x-show="memberInfo" class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 rounded-lg p-2">
                             <div class="overflow-hidden">
                                 <p class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 truncate" x-text="memberInfo?.pelanggan?.nama_lengkap ?? ''"></p>
                             </div>
                             <span class="text-[10px] font-black text-white bg-emerald-500 px-1.5 py-0.5 rounded shadow-sm shrink-0 ml-1">-<span x-text="pengaturan?.diskon_member ?? 0"></span>%</span>
                        </div>
                        <p x-show="memberError" class="text-[9px] text-red-500 font-bold mt-1 truncate" x-text="memberError"></p>
                    </div>
                </div>

                {{-- Kalkulasi Total --}}
                <div class="bg-gray-50 dark:bg-slate-800/50 border border-gray-100 dark:border-slate-700/50 rounded-xl p-3 mb-4 space-y-1.5">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-500 dark:text-gray-400 text-xs font-medium">Subtotal</p>
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300" x-text="formatRupiah(cartTotal)"></span>
                    </div>
                    <div x-show="memberInfo" class="flex justify-between items-center">
                        <p class="text-emerald-600 dark:text-emerald-400 text-xs font-bold">Diskon Member</p>
                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-text="'-' + formatRupiah(discountAmount)"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-dashed border-gray-200 dark:border-slate-700 mt-2">
                        <p class="text-gray-800 dark:text-gray-200 text-sm font-black uppercase tracking-wider">Total Tagihan</p>
                        <h2 class="text-2xl font-black text-blue-600 dark:text-blue-400" x-text="formatRupiah(grandTotal)"></h2>
                    </div>
                </div>

                {{-- Input Uang & Proses --}}
                <div class="flex gap-3 items-end">
                    <div class="flex-1 relative">
                        <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">
                            Uang Diterima <span x-show="uangBayar > 0 && kembalian >= 0" class="text-green-500 ml-1 normal-case">(Kembali: <span x-text="formatRupiah(kembalian)"></span>)</span>
                            <span x-show="uangBayar > 0 && kembalian < 0" class="text-red-500 ml-1 normal-case">Uang Kurang!</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 font-bold text-sm">Rp</div>
                            <input type="number" x-model.number="uangBayar" placeholder="0" class="w-full pl-9 pr-3 py-2.5 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-xl font-bold text-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors shadow-inner" style="appearance: textfield;">
                        </div>
                    </div>
                    
                    <form action="{{ route('kasir.pos.store') }}" method="POST" class="shrink-0 w-[140px]">
                        @csrf
                        <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                        <input type="hidden" name="total_bayar" :value="cartTotal">
                        <input type="hidden" name="uang_masuk" :value="uangBayar">
                        <input type="hidden" name="nama_pelanggan" :value="namaPelanggan">
                        <input type="hidden" name="id_pesanan_online" :value="selectedOrderId">
                        <input type="hidden" name="id_membership" :value="memberInfo?.id ?? ''">
                        
                        <button type="submit" 
                                :disabled="cart.length === 0 || uangBayar < grandTotal" 
                                :class="(cart.length === 0 || !uangBayar || uangBayar < grandTotal) ? 'bg-gray-200 dark:bg-slate-800 text-gray-400 dark:text-gray-600 cursor-not-allowed border outline-none' : 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 dark:shadow-blue-900/20 text-white transform hover:-translate-y-0.5'"
                                class="w-full font-black text-sm py-3 px-2 rounded-xl transition-all flex justify-center items-center h-[52px]">
                            <i class="fa-solid fa-check-double mr-2"></i> BAYAR
                        </button>
                    </form>
                </div>

                @if(session('error'))
                    <div class="mt-3 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold p-2 text-center rounded-lg border border-red-200 dark:border-red-800/50 flex items-center justify-center">
                        <i class="fa-solid fa-circle-exclamation mr-1.5"></i> {{ session('error') }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posSystem', (dataBarang, dataLayanan, dataPesananSiap, dataPengaturan) => ({
                barang: dataBarang,
                layanan: dataLayanan,
                pesananSiap: dataPesananSiap,
                pengaturan: dataPengaturan,
                activeTab: 'barang',
                searchQuery: '',
                cart: [],
                uangBayar: '',
                namaPelanggan: '',
                selectedOrderId: null,
                memberQuery: '',
                memberInfo: null,
                memberError: '',

                get filteredBarang() {
                    if (this.searchQuery === '') return this.barang;
                    return this.barang.filter(item => 
                        item.nama_barang.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                        item.kode_barang.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                get filteredLayanan() {
                    if (this.searchQuery === '') return this.layanan;
                    return this.layanan.filter(item => 
                        item.nama_layanan.toLowerCase().includes(this.searchQuery.toLowerCase())
                    );
                },

                selectOnlineOrder(order) {
                    // Toggle selection
                    if (this.selectedOrderId === order.id) {
                        this.selectedOrderId = null;
                        this.cart = this.cart.filter(c => !c.locked);
                        this.namaPelanggan = '';
                        return;
                    }

                    // Remove previous locked items
                    this.cart = this.cart.filter(c => !c.locked);
                    this.selectedOrderId = order.id;
                    this.namaPelanggan = order.pelanggan?.nama_lengkap ?? '';

                    // Add locked item from online order
                    if (order.layanan) {
                        this.cart.push({
                            id: order.layanan.id,
                            tipe: 'layanan',
                            nama: order.layanan.nama_layanan,
                            harga: order.layanan.harga_satuan,
                            satuan: order.layanan.satuan ?? 'unit',
                            qty: 1,
                            max_stok: null,
                            locked: true
                        });
                    }
                },

                async searchMember() {
                    if (!this.memberQuery.trim()) return;
                    this.memberError = '';
                    
                    try {
                        const res = await fetch(`/kasir/pos/cari-member?q=${encodeURIComponent(this.memberQuery)}`);
                        const data = await res.json();
                        
                        if (data.found) {
                            this.memberInfo = data.membership;
                            this.memberError = '';
                            if (!this.namaPelanggan) {
                                this.namaPelanggan = data.membership.pelanggan?.nama_lengkap ?? '';
                            }
                        } else {
                            this.memberInfo = null;
                            this.memberError = data.message ?? 'Member tidak ditemukan.';
                        }
                    } catch (e) {
                        this.memberError = 'Gagal mencari member. Coba lagi.';
                    }
                },

                clearMember() {
                    this.memberInfo = null;
                    this.memberQuery = '';
                    this.memberError = '';
                },

                addToCart(item, tipe) {
                    const existsIndex = this.cart.findIndex(c => c.id === item.id && c.tipe === tipe && !c.locked);
                    
                    if (existsIndex !== -1) {
                        this.incrementQty(existsIndex);
                    } else {
                        this.cart.push({
                            id: item.id,
                            tipe: tipe,
                            nama: tipe === 'barang' ? item.nama_barang : item.nama_layanan,
                            harga: tipe === 'barang' ? item.harga_jual : item.harga_satuan,
                            satuan: tipe === 'layanan' ? item.satuan : 'Pcs',
                            qty: 1,
                            max_stok: tipe === 'barang' ? item.stok : null,
                            locked: false
                        });
                    }
                },

                removeFromCart(index) {
                    if (this.cart[index]?.locked) return;
                    this.cart.splice(index, 1);
                },

                incrementQty(index) {
                    let item = this.cart[index];
                    if (item.locked) return;
                    if (item.tipe === 'barang' && item.qty >= item.max_stok) return;
                    item.qty++;
                },

                decrementQty(index) {
                    if (this.cart[index].locked) return;
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    } else {
                        this.removeFromCart(index);
                    }
                },

                validateQty(index) {
                    let item = this.cart[index];
                    if (item.qty < 1 || isNaN(item.qty)) item.qty = 1;
                    if (item.tipe === 'barang' && item.qty > item.max_stok) item.qty = item.max_stok;
                },

                clearCart() {
                    if (confirm('Yakin ingin membatalkan semua pesanan di keranjang?')) {
                        this.cart = [];
                        this.uangBayar = '';
                        this.selectedOrderId = null;
                        this.namaPelanggan = '';
                        this.clearMember();
                    }
                },

                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                },

                get discountAmount() {
                    if (!this.memberInfo || !this.pengaturan?.membership_aktif) return 0;
                    return Math.round(this.cartTotal * (this.pengaturan.diskon_member / 100));
                },

                get grandTotal() {
                    return this.cartTotal - this.discountAmount;
                },

                get kembalian() {
                    if (!this.uangBayar) return 0;
                    return this.uangBayar - this.grandTotal;
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                }
            }));
        });
    </script>
</x-app-layout>