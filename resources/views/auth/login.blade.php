<x-guest-layout>
    <x-slot name="title">Login - Susi Stationary</x-slot>

    <div class="mb-10 lg:mb-12">
        <h2 class="text-3xl lg:text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-tight transition-colors">Selamat Datang <br/><span class="text-blue-600 dark:text-blue-500">Kembali!</span></h2>
        <p class="text-sm md:text-base text-slate-500 dark:text-slate-400 mt-2 font-medium transition-colors">Masuk untuk melanjutkan pesanan Anda.</p>
    </div>

    <div x-data="{ showPwd: false }">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 transition-colors">Username / Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                        <i class="fa-solid fa-user text-sm"></i>
                    </div>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                        class="w-full pl-11 pr-4 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                        placeholder="Ketik username Anda">
                </div>
                @error('username') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
                @error('email') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 mt-4 ml-1 transition-colors">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 group-focus-within:text-blue-600 dark:group-focus-within:text-blue-400 transition-colors">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input id="password" :type="showPwd ? 'text' : 'password'" name="password" required
                        class="w-full pl-11 pr-12 py-3.5 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl focus:bg-white dark:focus:bg-slate-900 focus:ring-[3px] focus:ring-blue-100 dark:focus:ring-blue-900/30 focus:border-blue-500 dark:focus:border-blue-400 outline-none transition-all text-sm font-semibold input-transition text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500"
                        placeholder="••••••••">
                    <button type="button" @click="showPwd = !showPwd" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        <i class="fa-solid" :class="showPwd ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                @error('password') <p class="text-red-500 dark:text-red-400 text-[11px] font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between py-2">
                <label class="flex items-center cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-blue-600 dark:bg-slate-800 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                    <span class="ml-2 text-sm text-slate-500 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors font-medium">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">Lupa Sandi?</a>
                @endif
            </div>

            <button type="submit"
                class="w-full bg-blue-600 dark:bg-blue-700 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 dark:hover:bg-blue-600 transform transition hover:-translate-y-0.5 shadow-lg shadow-blue-200 dark:shadow-none text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i> MASUK
            </button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-slate-500 dark:text-slate-400 mt-8 pt-4 border-t border-slate-100 dark:border-slate-800 transition-colors">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline transition-colors">Daftar Sekarang</a>
                </p>
            @endif
        </form>
    </div>
</x-guest-layout>