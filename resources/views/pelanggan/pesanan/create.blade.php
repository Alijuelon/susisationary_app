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
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Jenis Layanan <span class="text-red-500">*</span></label>
                    <select name="id_layanan" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 text-sm shadow-sm transition-colors">
                        <option value="" disabled selected>-- Pilih Layanan --</option>
                        @foreach($layanan as $lyn)
                            <option value="{{ $lyn->id }}">{{ $lyn->nama_layanan }} (Rp {{ number_format($lyn->harga_satuan, 0, ',', '.') }} / {{ $lyn->satuan }})</option>
                        @endforeach
                    </select>
                    @error('id_layanan') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
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
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Catatan Pengerjaan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="catatan" rows="3" placeholder="Contoh: Tolong print rangkap 3, dijilid lakban bening ya..." class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 text-sm shadow-sm transition-colors">{{ old('catatan') }}</textarea>
                    @error('catatan') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
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