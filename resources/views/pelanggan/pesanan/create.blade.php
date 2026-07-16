<x-app-layout>
    <x-slot name="header">Pesan Cetak Online</x-slot>

    <div class="max-w-4xl mx-auto" x-data="{
        layanans: {{ Js::from($layanan) }},
        id_layanans: [],
        jumlah: 1,
        opsi: {},
        catatan: '',
        
        get totalHarga() {
            let total = 0;
            this.id_layanans.forEach(layId => {
                let lay = this.layanans.find(l => l.id == layId);
                if(!lay) return;
                
                let harga = lay.harga_satuan;
                Object.values(this.opsi).forEach(opsiId => {
                    if (opsiId) {
                        let opsi = lay.opsi_layanan.find(o => o.id == opsiId);
                        if (opsi) harga += opsi.harga;
                    }
                });
                total += (harga * this.jumlah);
            });
            return total;
        },

        getOpsiGrouped() {
            let groups = {};
            this.id_layanans.forEach(layId => {
                let lay = this.layanans.find(l => l.id == layId);
                if (lay && lay.opsi_layanan) {
                    lay.opsi_layanan.forEach(opsi => {
                        if(!groups[opsi.kategori]) groups[opsi.kategori] = [];
                        if (!groups[opsi.kategori].find(o => o.id === opsi.id)) {
                            groups[opsi.kategori].push(opsi);
                        }
                    });
                }
            });
            return groups;
        },

        toggleLayanan(id) {
            let index = this.id_layanans.indexOf(id);
            if (index > -1) {
                this.id_layanans.splice(index, 1);
            } else {
                this.id_layanans.push(id);
            }
            this.opsi = {}; // reset opsi when selection changes
        }
    }">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('pelanggan.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-gray-300 mr-3 transition-colors"><i class="fa-solid fa-arrow-left"></i></a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Form Cetak Dokumen</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih satu atau beberapa layanan sekaligus untuk dokumen Anda.</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 md:p-8 transition-colors">
            <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                    <div class="p-6 bg-gray-50 dark:bg-slate-800/50 rounded-2xl border border-gray-200 dark:border-slate-700 relative">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                            Detail Dokumen & Layanan
                        </h3>

                        <div class="space-y-6">
                            <!-- Upload File -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Unggah Dokumen <span class="text-red-500">*</span></label>
                                <input type="file" name="file_dokumen" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Maksimal 5MB (PDF, Word, Image).</p>
                            </div>

                            <!-- Pilih Layanan (Checkboxes) -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Jenis Layanan (Bisa lebih dari 1) <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <template x-for="lay in layanans" :key="lay.id">
                                        <label class="flex items-center p-3 border border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors"
                                               :class="id_layanans.includes(lay.id) ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700 ring-1 ring-blue-500' : 'bg-white dark:bg-slate-800'">
                                            <input type="checkbox" name="id_layanans[]" :value="lay.id" @change="toggleLayanan(lay.id)" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" :checked="id_layanans.includes(lay.id)">
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold text-gray-900 dark:text-white" x-text="lay.nama_layanan"></span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(lay.harga_satuan) + '/' + lay.satuan"></span>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <!-- Spesifikasi (Muncul jika ada layanan dipilih) -->
                            <div x-show="id_layanans.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-5 p-4 bg-white dark:bg-slate-900 rounded-xl border border-blue-100 dark:border-blue-900/50" x-transition>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jumlah / Qty <span class="text-red-500">*</span></label>
                                    <div class="flex items-center">
                                        <button type="button" @click="if(jumlah > 1) jumlah--" class="w-11 h-11 flex items-center justify-center bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-l-xl hover:bg-gray-100 transition-colors text-gray-700 dark:text-white">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <input type="number" name="jumlah_rangkap" x-model="jumlah" min="1" required class="w-full h-11 text-center border-y border-x-0 border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-white focus:ring-0 text-sm font-bold">
                                        <button type="button" @click="jumlah++" class="w-11 h-11 flex items-center justify-center bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-r-xl hover:bg-gray-100 transition-colors text-gray-700 dark:text-white">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <template x-for="(opsiList, kategori) in getOpsiGrouped()" :key="kategori">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2" x-text="kategori + ' *'"></label>
                                        <select name="opsi[]" x-model.number="opsi[kategori]" required class="w-full h-11 px-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            <template x-for="op in opsiList" :key="op.id">
                                                <option :value="op.id" x-text="op.nama_opsi + (op.harga > 0 ? ' (+Rp ' + new Intl.NumberFormat('id-ID').format(op.harga) + ')' : '')"></option>
                                            </template>
                                        </select>
                                    </div>
                                </template>
                            </div>

                            <!-- Catatan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan Khusus <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <textarea name="catatan_tambahan" x-model="catatan" rows="3" placeholder="Contoh: Cetak halaman 1-5 saja menggunakan kertas khusus..." class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 text-sm shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>

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