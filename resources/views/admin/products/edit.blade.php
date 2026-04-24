@extends('admin.layout')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk: ' . $product->name)

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nama Produk --}}
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="Masukkan nama produk">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror" placeholder="Masukkan deskripsi produk">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <span class="px-4 py-2 bg-gray-100 border border-gray-300 border-r-0 rounded-l-lg text-gray-700">Rp</span>
                            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0" class="flex-1 px-4 py-2 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror" placeholder="0">
                        </div>
                        @error('price')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Stok --}}
                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 @enderror" placeholder="0">
                        @error('stock')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="mb-6">
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $product->category) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror" placeholder="Contoh: Panci, Wajan, dll">
                    @error('category')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="mb-6">
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                    
                    @if ($product->image)
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-700 mb-2 font-medium">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded-lg">
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors" onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2 block"></i>
                        <p class="text-gray-700 font-medium">Klik untuk ganti gambar</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, GIF (Maks 2MB)</p>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </div>
                    <div id="imagePreview" class="mt-3 hidden">
                        <p class="text-sm text-gray-700 mb-2 font-medium">Pratinjau Gambar Baru:</p>
                        <img id="previewImg" src="" alt="Preview" class="h-32 w-32 object-cover rounded-lg">
                    </div>
                    @error('image')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- URL Video --}}
                <div class="mb-6">
                    <label for="video_url" class="block text-sm font-semibold text-gray-700 mb-2">URL Video (Opsional)</label>
                    <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $product->video_url) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('video_url') border-red-500 @enderror" placeholder="https://...">
                    @error('video_url')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- COD Available --}}
                <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="is_cod_available" name="is_cod_available" value="1" {{ old('is_cod_available', $product->is_cod_available) ? 'checked' : '' }} class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">
                            <i class="fas fa-check-circle text-blue-600 mr-2"></i>Aktifkan COD (Cash On Delivery)
                        </span>
                    </label>
                    <p class="text-xs text-gray-600 mt-2 ml-8">Jika diaktifkan, pelanggan dapat memilih pembayaran COD untuk produk ini</p>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Perbarui Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection