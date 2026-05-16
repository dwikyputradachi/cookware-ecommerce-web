@extends('admin.layout')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk Baru')

@section('content')
@php
    function img_url($image) {
        if (!$image) return asset('img/no-image.png');

        return str_starts_with($image, 'http')
            ? $image
            : 'https://res.cloudinary.com/dzem84oat/image/upload/products/' . $image;
    }
@endphp
    <style>
        .form-card {
            background: white;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            padding: 32px;
        }

        .field { margin-bottom: 22px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
        }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-family: inherit;
            font-size: 14px;
            color: #1e293b;
            background: white;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field input:focus,
        .field textarea:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        .field input.is-error { border-color: #ef4444; }

        .field-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .input-prefix {
            display: flex;
            align-items: stretch;
        }

        .input-prefix .prefix-label {
            padding: 10px 14px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            border-radius: 9px 0 0 9px;
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }

        .input-prefix input {
            border-radius: 0 9px 9px 0 !important;
        }

        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 28px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .upload-zone:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-zone i { font-size: 28px; color: #94a3b8; display: block; margin-bottom: 8px; }
        .upload-zone p { color: #374151; font-size: 14px; font-weight: 500; margin: 0 0 3px; }
        .upload-zone span { color: #94a3b8; font-size: 12px; }

        .toggle-card {
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .toggle-card.blue   { background: #eff6ff; border: 1px solid #bfdbfe; }
        .toggle-card.orange { background: #fff7ed; border: 1px solid #fed7aa; }

        .toggle-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 600;
            color: #374151;
        }

        .toggle-label input[type="checkbox"] {
            width: 18px; height: 18px;
            flex-shrink: 0;
        }

        .toggle-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            padding-left: 28px;
        }

        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .btn-primary {
            flex: 1;
            padding: 12px 20px;
            background: #1e40af;
            color: white;
            border: none;
            border-radius: 9px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity 0.2s;
            text-decoration: none;
        }

        .btn-primary:hover { opacity: 0.88; }

        .btn-secondary {
            flex: 1;
            padding: 12px 20px;
            background: #f1f5f9;
            color: #475569;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-secondary:hover { background: #e2e8f0; }

        @media (max-width: 640px) {
            .form-card { padding: 20px 16px; }
            .two-col { grid-template-columns: 1fr; gap: 0; }
            .form-actions { flex-direction: column; }
            .btn-primary, .btn-secondary { flex: none; width: 100%; }
        }
    </style>

    <div style="max-width: 680px;">
        <div class="form-card">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama Produk --}}
                <div class="field">
                    <label for="name">Nama Produk <span style="color:#ef4444">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="Masukkan nama produk"
                        class="{{ $errors->has('name') ? 'is-error' : '' }}">
                    @error('name')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="field">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="Masukkan deskripsi produk"
                        class="{{ $errors->has('description') ? 'is-error' : '' }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Harga + Stok --}}
                <div class="two-col">
                    <div class="field">
                        <label for="price">Harga <span style="color:#ef4444">*</span></label>
                        <div class="input-prefix">
                            <span class="prefix-label">Rp</span>
                            <input type="number" id="price" name="price" value="{{ old('price') }}"
                                required step="0.01" min="0" placeholder="0"
                                class="{{ $errors->has('price') ? 'is-error' : '' }}">
                        </div>
                        @error('price')
                            <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="stock">Stok <span style="color:#ef4444">*</span></label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}"
                            required min="0" placeholder="0"
                            class="{{ $errors->has('stock') ? 'is-error' : '' }}">
                        @error('stock')
                            <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kategori --}}
                <div class="field">
                    <label for="category">Kategori</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}"
                        placeholder="Contoh: Panci, Wajan, dll"
                        class="{{ $errors->has('category') ? 'is-error' : '' }}">
                    @error('category')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="field">
                    <label>Gambar Produk</label>
                    <div class="upload-zone" onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <p>Klik untuk upload gambar</p>
                        <span>PNG, JPG, GIF — Maks 2MB</span>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </div>
                    <div id="imagePreview" class="hidden" style="margin-top:12px;">
                        <img id="previewImg" src="" alt="Preview"
                            style="height:100px;width:100px;object-fit:cover;border-radius:10px;border:1px solid #e2e8f0;">
                    </div>
                    @error('image')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- URL Video --}}
                <div class="field">
                    <label for="video_url">URL Video <span style="color:#94a3b8;font-weight:400;">(Opsional)</span></label>
                    <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}"
                        placeholder="https://..."
                        class="{{ $errors->has('video_url') ? 'is-error' : '' }}">
                    @error('video_url')
                        <p class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- COD --}}
                <div class="toggle-card blue">
                    <label class="toggle-label">
                        <input type="checkbox" id="is_cod_available" name="is_cod_available" value="1"
                            {{ old('is_cod_available') ? 'checked' : '' }}>
                        <i class="fas fa-motorcycle" style="color:#2563eb;"></i>
                        Aktifkan COD (Cash On Delivery)
                    </label>
                    <p class="toggle-hint">Pelanggan dapat memilih pembayaran COD untuk produk ini</p>
                </div>

                {{-- Buttons --}}
                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan Produk
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                        <i class="fas fa-xmark"></i> Batal
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
                reader.onload = e => {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
