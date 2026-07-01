<x-app-layout>
    <x-slot name="header">Pesan Cetak Online</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center">
            <a href="{{ route('pelanggan.dashboard') }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-gray-300 mr-3 transition-colors"><i class="fa-solid fa-arrow-left"></i></a>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">Formulir Cetak Dokumen</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Unggah file Anda, kasir kami akan segera memprosesnya.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 md:p-8 transition-colors">
            <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Pilih Jenis Layanan <span class="text-red-500">*</span></label>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($layanan as $lyn)
                            <label class="block relative cursor-pointer h-full group">
                                <input type="radio" name="id_layanan" value="{{ $lyn->id }}" class="peer sr-only" required {{ old('id_layanan') == $lyn->id ? 'checked' : '' }}>
                                
                                <div class="h-full p-5 rounded-2xl border-2 border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-300 dark:hover:border-slate-600 transition-all peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 shadow-sm flex flex-col justify-center relative overflow-hidden group-hover:shadow-md">
                                    
                                    <!-- Decoration -->
                                    <div class="absolute -right-6 -top-6 w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    
                                    <div class="relative z-10 pr-6">
                                        <h4 class="font-bold text-gray-900 dark:text-white text-base mb-1">{{ $lyn->nama_layanan }}</h4>
                                        <p class="text-blue-600 dark:text-blue-400 font-bold text-sm">Rp {{ number_format($lyn->harga_satuan, 0, ',', '.') }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">/ {{ $lyn->satuan }}</span></p>
                                    </div>
                                </div>
                                
                                <div class="absolute top-5 right-5 text-blue-600 dark:text-blue-400 opacity-0 peer-checked:opacity-100 transition-all duration-300 scale-50 peer-checked:scale-100 z-20">
                                    <i class="fa-solid fa-circle-check text-xl bg-white dark:bg-slate-900 rounded-full"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('id_layanan') <p class="text-red-500 dark:text-red-400 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Spesifikasi Cetak -->
                <div class="bg-gray-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-gray-200 dark:border-slate-700 space-y-4">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white flex items-center"><i class="fa-solid fa-sliders text-blue-500 mr-2"></i> Spesifikasi Cetakan</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Jumlah Rangkap -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Jumlah Rangkap <span class="text-red-500">*</span></label>
                            <div x-data="{ count: {{ old('jumlah_rangkap', 1) }} }" class="flex items-center">
                                <button type="button" @click="if(count > 1) count--" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-l-xl hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors text-gray-700 dark:text-gray-300">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" name="jumlah_rangkap" x-model="count" min="1" required readonly class="w-full h-10 text-center border-y border-x-0 border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-white focus:ring-0 focus:outline-none text-sm font-bold">
                                <button type="button" @click="count++" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-r-xl hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors text-gray-700 dark:text-gray-300">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Warna Cetak -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Warna Cetak <span class="text-red-500">*</span></label>
                            <select name="warna_cetak" required class="w-full h-10 px-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="Hitam Putih" {{ old('warna_cetak') == 'Hitam Putih' ? 'selected' : '' }}>Hitam Putih</option>
                                <option value="Warna" {{ old('warna_cetak') == 'Warna' ? 'selected' : '' }}>Warna</option>
                            </select>
                        </div>

                        <!-- Sisi Cetak -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">Sisi Cetak <span class="text-red-500">*</span></label>
                            <select name="sisi_cetak" required class="w-full h-10 px-3 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="Satu Sisi" {{ old('sisi_cetak') == 'Satu Sisi' ? 'selected' : '' }}>Satu Sisi</option>
                                <option value="Bolak Balik" {{ old('sisi_cetak') == 'Bolak Balik' ? 'selected' : '' }}>Bolak Balik</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Unggah Dokumen <span class="text-red-500">*</span></label>
                    
                    <div class="relative border-2 border-dashed border-gray-300 dark:border-slate-700 rounded-2xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-colors bg-gray-50 dark:bg-slate-800/50">
                        <input type="file" name="file_dokumen" id="file_dokumen" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="pointer-events-none">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 dark:text-gray-500 mb-3 transition-colors"></i>
                            <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-1">Klik atau Tarik file ke sini</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Mendukung: PDF, DOC, DOCX, JPG, PNG (Maks 5MB)</p>
                        </div>
                    </div>
                    @error('file_dokumen') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Catatan Pengerjaan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-2 font-medium bg-blue-50 dark:bg-blue-900/30 p-2.5 rounded-xl border border-blue-100 dark:border-blue-800/50 flex items-start">
                        <i class="fa-solid fa-circle-info mt-0.5 mr-2"></i>
                        <span>Instruksi: Tuliskan instruksi tambahan di bawah jika diperlukan.<br><span class="opacity-80">Contoh: Tolong dijilid lakban bening, halaman 1-5 dicetak warna, sisanya hitam putih.</span></span>
                    </p>
                    <textarea name="catatan_tambahan" rows="3" placeholder="Ketik catatan atau instruksi khusus Anda di sini..." class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 text-sm shadow-sm transition-colors">{{ old('catatan_tambahan') }}</textarea>
                    @error('catatan_tambahan') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <hr class="border-gray-100 dark:border-slate-800 my-4 transition-colors">

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('pelanggan.dashboard') }}" class="px-6 py-3 bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-slate-700 transition-colors text-sm">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md transition-colors text-sm flex items-center">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>