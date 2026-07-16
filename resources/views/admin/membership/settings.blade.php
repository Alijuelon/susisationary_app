<x-app-layout>
    <x-slot name="header">Pengaturan Membership</x-slot>

    <div class="w-full max-w-2xl">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 transition-colors">
            Konfigurasi program keanggotaan pelanggan dan persentase diskon member.
            <a href="{{ route('admin.membership.index') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline ml-2"><i class="fa-solid fa-arrow-left mr-1"></i>Kembali ke Daftar</a>
        </p>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-800 transition-colors">
                <h3 class="font-bold text-gray-800 dark:text-white text-base"><i class="fa-solid fa-gear mr-2 text-blue-500"></i>Konfigurasi Membership</h3>
            </div>

            <form method="POST" action="{{ route('admin.membership.update_settings') }}" class="p-6 space-y-6">
                @csrf

                {{-- Toggle Membership Aktif --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700 transition-colors">
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-white text-sm">Program Membership</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Aktifkan/nonaktifkan fitur membership untuk pelanggan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="membership_aktif" value="0">
                        <input type="checkbox" name="membership_aktif" value="1" {{ ($pengaturan->membership_aktif ?? false) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-900/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 dark:after:border-slate-500 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>


                <div class="pt-4 border-t border-gray-100 dark:border-slate-800">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-md transition-all text-sm">
                        <i class="fa-solid fa-save mr-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
