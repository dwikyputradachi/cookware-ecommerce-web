<aside class="w-full lg:w-1/4">
    <div class="sticky top-28 bg-white border border-gray-100 rounded-2xl p-2 shadow-sm space-y-1">
        <a href="{{ route('about.us') }}" class="flex items-center justify-between px-5 py-4 rounded-xl text-sm transition-all {{ $active == 'about' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Tentang Kami <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
        <a href="{{ route('garansi') }}" class="flex items-center justify-between px-5 py-4 rounded-xl text-sm transition-all {{ $active == 'garansi' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Kebijakan Garansi <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
        <a href="{{ route('bantuan') }}" class="flex items-center justify-between px-5 py-4 rounded-xl text-sm transition-all {{ $active == 'bantuan' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Bantuan Belanja <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
        <a href="{{ route('penipuan') }}" class="flex items-center justify-between px-5 py-4 rounded-xl text-sm transition-all {{ $active == 'penipuan' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Waspada Penipuan <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
        <a href="{{ route('panduan') }}" class="flex items-center justify-between px-5 py-4 rounded-xl text-sm transition-all {{ $active == 'panduan' ? 'bg-orange-600 text-white font-bold shadow-lg shadow-orange-100' : 'text-gray-500 font-semibold hover:bg-gray-50 hover:text-orange-600' }}">
            Panduan Belanja <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </a>
    </div>
</aside>