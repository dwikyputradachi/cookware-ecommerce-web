<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl text-sm text-red-600">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Preview Gambar --}}
    <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Gambar Banner <span class="text-red-500">*</span>
            <span class="text-gray-400 font-normal ml-1">Rekomendasi 1920×600px</span>
        </label>

        {{-- Kotak Preview --}}
        <div id="preview-box"
             onclick="document.getElementById('image-input').click()"
             class="relative w-full rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 overflow-hidden cursor-pointer hover:border-blue-300 hover:bg-blue-50/20 transition"
             style="height: 180px;">

            {{-- Placeholder --}}
            <div id="preview-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-300"
                 style="{{ $banner?->image ? 'display:none' : '' }}">
                <i class="fas fa-cloud-arrow-up text-4xl mb-2"></i>
                <p class="text-sm text-gray-400 font-medium">Klik untuk pilih gambar</p>
                <p class="text-xs text-gray-300 mt-1">JPG, PNG, WebP — maks 5MB</p>
            </div>

            {{-- Gambar preview --}}
            <img id="preview-img"
                 src="{{ $banner?->image ?? '' }}"
                 alt="Preview"
                 class="w-full h-full object-cover"
                 style="{{ $banner?->image ? '' : 'display:none' }}">

            {{-- Badge ganti (muncul kalau ada preview) --}}
            <div id="preview-badge"
                 class="absolute bottom-2 right-2"
                 style="{{ $banner?->image ? '' : 'display:none' }}">
                <span class="px-3 py-1 bg-black/50 text-white text-xs rounded-lg backdrop-blur-sm font-medium">
                    <i class="fas fa-arrows-rotate mr-1"></i> Klik untuk ganti
                </span>
            </div>
        </div>

        {{-- Info file --}}
        <p id="file-info" class="text-xs text-gray-400 mt-1.5 hidden"></p>

        <input type="file" id="image-input" name="image"
               accept="image/jpeg,image/png,image/jpg,image/webp"
               class="hidden"
               {{ !$banner ? 'required' : '' }}
               onchange="previewImage(this)">
    </div>

    {{-- Judul --}}
    <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Judul <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $banner?->title) }}"
               placeholder="Promo Akhir Tahun..."
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
    </div>

    {{-- Link --}}
    <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
            Link <span class="text-gray-400 font-normal">(opsional)</span>
        </label>
        <input type="url" name="link" value="{{ old('link', $banner?->link) }}"
               placeholder="https://..."
               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
    </div>

    <div class="flex gap-4 mb-5">
        {{-- Sort Order --}}
        <div class="flex-1">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" min="0"
                   value="{{ old('sort_order', $banner?->sort_order ?? 0) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
            <p class="text-xs text-gray-400 mt-1">Angka kecil = tampil duluan</p>
        </div>

        {{-- Status --}}
        <div class="flex-1">
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
            <label class="flex items-center gap-3 mt-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 accent-blue-600">
                <span class="text-sm text-gray-600">Aktifkan banner ini</span>
            </label>
            @if($activeCount >= 5 && !$banner?->is_active)
            <p class="text-xs text-red-500 mt-1">Batas 5 banner aktif sudah tercapai</p>
            @endif
        </div>
    </div>

    <button type="submit"
            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition">
        {{ $banner ? 'Simpan Perubahan' : 'Tambah Banner' }}
    </button>
</form>

<script>
function previewImage(input) {
    if (!input.files || !input.files[0]) return;

    const file = input.files[0];

    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 5MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview-img').src = e.target.result;
        document.getElementById('preview-img').style.display = 'block';
        document.getElementById('preview-placeholder').style.display = 'none';
        document.getElementById('preview-badge').style.display = 'block';
        document.getElementById('file-info').textContent = file.name + ' — ' + (file.size / 1024).toFixed(0) + ' KB';
        document.getElementById('file-info').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}
</script>