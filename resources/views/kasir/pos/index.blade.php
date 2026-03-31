<x-app-layout>
    <x-slot name="header">Mesin Kasir (POS)</x-slot>

    <div class="w-full h-[calc(100vh-140px)] flex flex-col lg:flex-row gap-6" 
         x-data="posSystem({{ json_encode($barang) }}, {{ json_encode($layanan) }})">
        
        <div class="w-full lg:w-2/3 flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden relative transition-colors">
            
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
                    <button @click="activeTab = 'barang'" :class="activeTab === 'barang' ? 'bg-gray-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-slate-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800'" class="px-5 py-2 rounded-lg text-sm font-bold transition-all w-1/2">
                        <i class="fa-solid fa-boxes-stacked mr-1"></i> Stok ATK
                    </button>
                    <button @click="activeTab = 'layanan'" :class="activeTab === 'layanan' ? 'bg-gray-900 dark:bg-white text-white dark:text-slate-900 shadow-md' : 'bg-white dark:bg-slate-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800'" class="px-5 py-2 rounded-lg text-sm font-bold transition-all w-1/2">
                        <i class="fa-solid fa-print mr-1"></i> Jasa & Layanan
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-5 custom-scrollbar bg-gray-50/30 dark:bg-slate-900/50 transition-colors">
                
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

            </div>
        </div>

        <div class="w-full lg:w-1/3 flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            
            <div class="p-5 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50 shrink-0 flex justify-between items-center transition-colors">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-cart-shopping mr-2 text-blue-500 dark:text-blue-400"></i> Rincian Pesanan</h3>
                <button @click="clearCart()" x-show="cart.length > 0" class="text-xs font-bold text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/30 px-3 py-1.5 rounded-lg transition-colors">Kosongkan</button>
            </div>

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
                        <li class="p-3 border border-gray-100 dark:border-slate-800 rounded-xl bg-gray-50/50 dark:bg-slate-800/50 flex flex-col transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="pr-2">
                                    <h6 class="text-sm font-bold text-gray-800 dark:text-white leading-tight" x-text="item.nama"></h6>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400" x-text="formatRupiah(item.harga) + (item.tipe === 'layanan' ? ' / ' + item.satuan : '')"></p>
                                </div>
                                <button @click="removeFromCart(index)" class="text-red-400 dark:text-red-500 hover:text-red-600 dark:hover:text-red-400 bg-white dark:bg-slate-800 rounded shadow-sm w-6 h-6 flex items-center justify-center shrink-0 transition-colors">
                                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                                </button>
                            </div>
                            
                            <div class="flex justify-between items-center mt-auto">
                                <div class="flex items-center bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm transition-colors">
                                    <button @click="decrementQty(index)" class="w-7 h-7 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-l-lg transition-colors"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                    <input type="number" x-model.number="item.qty" @change="validateQty(index)" class="w-10 h-7 text-center text-xs font-bold border-none focus:ring-0 p-0 text-gray-800 dark:text-white bg-transparent">
                                    <button @click="incrementQty(index)" class="w-7 h-7 flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-r-lg transition-colors"><i class="fa-solid fa-plus text-[10px]"></i></button>
                                </div>
                                
                                <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="formatRupiah(item.harga * item.qty)"></p>
                            </div>
                            <p x-show="item.tipe === 'barang' && item.qty >= item.max_stok" class="text-[9px] text-red-500 font-bold mt-1 text-right">Stok maksimal!</p>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="p-5 border-t border-gray-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-800/80 shrink-0 transition-colors">
                <div class="flex justify-between items-center mb-3">
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">Total Tagihan</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="formatRupiah(cartTotal)"></h2>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Uang Diterima (Rp)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 font-bold">Rp</div>
                        <input type="number" x-model.number="uangBayar" placeholder="0" class="w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-700 rounded-xl font-bold text-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-200 dark:border-slate-700 border-dashed transition-colors">
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">Kembalian</p>
                    <h3 class="text-lg font-bold" :class="kembalian < 0 ? 'text-red-500 dark:text-red-400' : 'text-green-600 dark:text-green-400'" x-text="kembalian < 0 ? 'Uang Kurang!' : formatRupiah(kembalian)"></h3>
                </div>

                <form action="{{ route('kasir.pos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
                    <input type="hidden" name="total_bayar" :value="cartTotal">
                    <input type="hidden" name="uang_masuk" :value="uangBayar">
                    
                    <button type="submit" 
                            :disabled="cart.length === 0 || uangBayar < cartTotal" 
                            :class="(cart.length === 0 || uangBayar < cartTotal) ? 'bg-gray-300 dark:bg-slate-700 cursor-not-allowed text-gray-500 dark:text-gray-400' : 'bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-200 dark:shadow-blue-900/20 text-white'"
                            class="w-full font-bold text-lg py-3.5 rounded-xl transition-all flex justify-center items-center">
                        <i class="fa-solid fa-print mr-2"></i> Proses Transaksi
                    </button>
                </form>

                @if(session('error'))
                    <p class="text-red-500 dark:text-red-400 text-xs font-bold text-center mt-3">{{ session('error') }}</p>
                @endif
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posSystem', (dataBarang, dataLayanan) => ({
                barang: dataBarang,
                layanan: dataLayanan,
                activeTab: 'barang',
                searchQuery: '',
                cart: [],
                uangBayar: '',

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

                addToCart(item, tipe) {
                    const existsIndex = this.cart.findIndex(c => c.id === item.id && c.tipe === tipe);
                    
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
                            max_stok: tipe === 'barang' ? item.stok : null
                        });
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                incrementQty(index) {
                    let item = this.cart[index];
                    if (item.tipe === 'barang' && item.qty >= item.max_stok) {
                        return; 
                    }
                    item.qty++;
                },

                decrementQty(index) {
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
                    }
                },

                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                },

                get kembalian() {
                    if (!this.uangBayar) return 0;
                    return this.uangBayar - this.cartTotal;
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);                                                                                                                                                                                                                                                                                                                                              
                }
            }));
        });
    </script>
</x-app-layout>                                                                                                                                                                                                                                                                                                                                         