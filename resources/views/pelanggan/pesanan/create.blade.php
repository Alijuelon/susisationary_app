<x-app-layout>
    <x-slot name="header">Pesan Cetak Online</x-slot>

    <div class="max-w-4xl mx-auto" x-data="{
        layanans: {{ Js::from($layanan) }},
        items: [
            { id: Date.now(), id_layanan: '', jumlah: 1, opsi: {}, catatan: '' }
        ],
        
        get totalHarga() {
            let total = 0;
            this.items.forEach(item => {
                if(!item.id_layanan) return;
                let lay = this.layanans.find(l => l.id == item.id_layanan);
                if(!lay) return;
                
                let harga = lay.harga_satuan;
                Object.values(item.opsi).forEach(opsiId => {
                    if (opsiId) {
                        let opsi = lay.opsi_layanan.find(o => o.id == opsiId);
                        if (opsi) harga += opsi.harga;
                    }
                });
                total += (harga * item.jumlah);
            });
            return total;
        },

        addItem() {
            this.items.push({ id: Date.now(), id_layanan: '', jumlah: 1, opsi: {}, catatan: '' });
        },

        removeItem(index) {
            if(this.items.length > 1) this.items.splice(index, 1);
        },

        getOpsiGrouped(layananId) {
            let lay = this.layanans.find(l => l.id == layananId);
            if (!lay || !lay.opsi_layanan) return {};
            let groups = {};
            lay.opsi_layanan.forEach(opsi => {
                if(!groups[opsi.kategori]) groups[opsi.kategori] = [];
                groups[opsi.kategori].push(opsi);
            });
            return groups;
        }
    }">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('pelanggan.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-gray-300 mr-3 transition-colors"><i class="fa-solid fa-arrow-left"></i></a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Keranjang Cetak Dokumen</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih satu atau beberapa layanan sekaligus dalam satu pesanan.</p>
                </div>
            </div>
            <button @click="addItem()" type="button" class="px-4 py-2 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg font-bold hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors text-sm">
                <i class="fa-solid fa-plus mr-1"></i> Tambah Layanan
            </button>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 md:p-8 transition-colors">
            <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <template x-for="(item, index) in items" :key="item.id">
                    <div class="p-6 bg-gray-50 dark:bg-slate-800/50 rounded-2xl border border-gray-200 dark:border-slate-700 relative">
                        <!-- Tombol Hapus -->
                        <button x-show="items.length > 1" @click="removeItem(index)" type="button" class="absolute -top-3 -right-3 w-8 h-8 bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-400 rounded-full flex items-center justify-center hover:bg-red-200 dark:hover:bg-red-800 transition-colors shadow-sm">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                            <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs mr-2" x-text="index + 1"></span>
                            Detail Layanan
                        </h3>

                        <div class="space-y-5">
                            <!-- Pilih Layanan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Jenis Layanan <span class="text-red-500">*</span></label>
                                <select :name="`items[${index}][id_layanan]`" x-model.number="item.id_layanan" @change="item.opsi = {}" required class="w-full h-11 px-4 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold">
                                    <option value="" disabled>-- Pilih Layanan --</option>
                                    <template x-for="lay in layanans" :key="lay.id">
                                        <option :value="lay.id" x-text="lay.nama_layanan + ' (Rp ' + new Intl.NumberFormat('id-ID').format(lay.harga_satuan) + '/' + lay.satuan + ')'"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Spesifikasi (Muncul jika layanan dipilih) -->
                            <div x-show="item.id_layanan" class="grid grid-cols-1 md:grid-cols-2 gap-5" style="display: none;">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jumlah / Qty <span class="text-red-500">*</span></label>
                                    <div class="flex items-center">
                                        <button type="button" @click="if(item.jumlah > 1) item.jumlah--" class="w-11 h-11 flex items-center justify-center bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-l-xl hover:bg-gray-100 transition-colors text-gray-700 dark:text-white">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <input type="number" :name="`items[${index}][jumlah_rangkap]`" x-model="item.jumlah" min="1" required class="w-full h-11 text-center border-y border-x-0 border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-white focus:ring-0 text-sm font-bold">
                                        <button type="button" @click="item.jumlah++" class="w-11 h-11 flex items-center justify-center bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-r-xl hover:bg-gray-100 transition-colors text-gray-700 dark:text-white">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <template x-for="(opsiList, kategori) in getOpsiGrouped(item.id_layanan)" :key="kategori">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2" x-text="kategori + ' *'"></label>
                                        <select :name="`items[${index}][opsi][]`" x-model.number="item.opsi[kategori]" required class="w-full h-11 px-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <template x-for="opsi in opsiList" :key="opsi.id">
                                                <option :value="opsi.id" x-text="opsi.nama_opsi + (opsi.harga > 0 ? ' (+Rp ' + new Intl.NumberFormat('id-ID').format(opsi.harga) + ')' : '')"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                            </div>

                            <!-- Upload File -->
                            <div x-show="item.id_layanan" style="display: none;">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Unggah Dokumen <span class="text-red-500">*</span></label>
                                <input type="file" :name="`items[${index}][file_dokumen]`" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800">
                            </div>

                            <!-- Catatan -->
                            <div x-show="item.id_layanan" style="display: none;">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan Khusus <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea :name="`items[${index}][catatan_tambahan]`" x-model="item.catatan" rows="2" placeholder="Contoh: Cetak halaman 1-5 saja..." class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 text-sm shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                @if($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 rounded-xl text-sm mb-4">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <hr class="border-gray-100 dark:border-slate-800 my-4 transition-colors">

                <div class="flex items-center justify-between border-t border-gray-100 dark:border-slate-800 pt-6 transition-colors">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Semua Layanan</p>
                        <p class="text-2xl font-black text-blue-600 dark:text-blue-400">Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalHarga)"></span></p>
                    </div>
                    <div class="flex justify-end space-x-3 ml-auto">
                        <a href="{{ route('pelanggan.dashboard') }}" class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-200 transition-colors text-sm">Batal</a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-md transition-colors text-sm flex items-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Buat Pesanan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>