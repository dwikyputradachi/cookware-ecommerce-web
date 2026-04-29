<aside class="w-full lg:w-1/4">
    <div class="sticky top-28 bg-white border border-gray-100 rounded-4xl p-3 shadow-sm space-y-1">
        {{-- Header Kecil biar lebih Streetwear/Modern --}}
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] px-5 py-3">Pusat Informasi</p>

        {{-- Navigasi --}}
        <a href="{{ route('about.us') }}" 
           class="flex items-center justify-between px-5 py-4 rounded-2xl text-sm transition-all duration-200 
           {{ $active == 'about' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Tentang Kami 
            <i data-lucide="chevron-right" class="w-4 h-4 {{ $active == 'about' ? 'opacity-100' : 'opacity-30' }}"></i>
        </a>

        <a href="{{ route('garansi') }}" 
           class="flex items-center justify-between px-5 py-4 rounded-2xl text-sm transition-all duration-200 
           {{ $active == 'garansi' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Kebijakan Garansi 
            <i data-lucide="chevron-right" class="w-4 h-4 {{ $active == 'garansi' ? 'opacity-100' : 'opacity-30' }}"></i>
        </a>

        <a href="{{ route('bantuan') }}" 
           class="flex items-center justify-between px-5 py-4 rounded-2xl text-sm transition-all duration-200 
           {{ $active == 'bantuan' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Bantuan Belanja 
            <i data-lucide="chevron-right" class="w-4 h-4 {{ $active == 'bantuan' ? 'opacity-100' : 'opacity-30' }}"></i>
        </a>

        <a href="{{ route('penipuan') }}" 
           class="flex items-center justify-between px-5 py-4 rounded-2xl text-sm transition-all duration-200 
           {{ $active == 'penipuan' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Waspada Penipuan 
            <i data-lucide="chevron-right" class="w-4 h-4 {{ $active == 'penipuan' ? 'opacity-100' : 'opacity-30' }}"></i>
        </a>

        <a href="{{ route('panduan') }}" 
           class="flex items-center justify-between px-5 py-4 rounded-2xl text-sm transition-all duration-200 
           {{ $active == 'panduan' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Panduan Belanja 
            <i data-lucide="chevron-right" class="w-4 h-4 {{ $active == 'panduan' ? 'opacity-100' : 'opacity-30' }}"></i>
        </a>

        <a href="{{ route('return') }}" 
           class="flex items-center justify-between px-5 py-4 rounded-2xl text-sm transition-all duration-200 
           {{ $active == 'return' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Ketentuan Return 
            <i data-lucide="chevron-right" class="w-4 h-4 {{ $active == 'return' ? 'opacity-100' : 'opacity-30' }}"></i>
        </a> {{-- Tadi di kode kamu cuma <a> doang, kurang penutupnya --}}
    </div> {{-- Ini penutup pembungkus link (p-3 shadow-sm) --}}
</aside>