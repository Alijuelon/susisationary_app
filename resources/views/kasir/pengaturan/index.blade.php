<x-app-layout>
    <x-slot name="header">Pengaturan Struk</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Format Kertas Struk</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ubah identitas toko yang akan dicetak pada struk fisik maupun digital.</p>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 bg-green-500 dark:bg-green-600 border border-green-600 dark:border-green-500 text-white px-5 py-4 rounded-xl shadow-md flex items-center justify-between transition-colors">
                <div class="flex items-center space-x-3"><i class="fa-solid fa-circle-check text-xl"></i><p class="font-bold text-sm">{{ session('success') }}</p></div>
                <button @click="show = false" class="text-white hover:text-green-200 transition-colors"><i class="fa-solid fa-times"></i></button>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 p-6 md:p-8 transition-colors">
            <form action="{{ route('kasir.pengaturan.update') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Toko (Header Struk) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_toko" value="{{ old('nama_toko', $pengaturan->nama_toko) }}" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 uppercase font-bold text-sm shadow-sm transition-colors">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Toko <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 text-sm shadow-sm transition-colors">{{ old('alamat', $pengaturan->alamat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon / WA <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $pengaturan->no_telp) }}" required class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 text-sm shadow-sm transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pesan Penutup (Footer Struk)</label>
                    <textarea name="pesan_penutup" rows="2" class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 text-sm shadow-sm text-center italic transition-colors">{{ old('pesan_penutup', $pengaturan->pesan_penutup) }}</textarea>
                </div>

                <hr class="border-gray-100 dark:border-slate-800 my-4 transition-colors">

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-xl font-bold hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md transition-colors text-sm flex items-center">
                        <i class="fa-solid fa-save mr-2"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>