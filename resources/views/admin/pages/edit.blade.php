@extends('admin.layout')

@section('page-title', 'Edit Halaman')

@section('content')
<div class="mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Halaman: {{ $page->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui isi halaman yang ditampilkan di frontend.</p>
    </div>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST">
    @csrf
    @method('PUT')

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-100 rounded-xl text-sm text-red-600">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                <input type="text" value="{{ $page->slug }}" disabled class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-sm text-gray-500">
                <p class="text-xs text-gray-400 mt-2">Slug digunakan untuk URL halaman. Jika ingin menambah halaman baru, tambahkan melalui seed/migrasi atau buat route baru.</p>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Halaman</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konten Halaman</label>
                <textarea name="content" rows="14" class="w-full border border-gray-200 rounded-3xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">{{ old('content', $page->content) }}</textarea>
                <p class="text-xs text-gray-400 mt-2">Konten bisa berupa teks biasa—baris baru akan otomatis ditampilkan. Jika dibutuhkan, HTML sederhana juga didukung.</p>
            </div>
            <div class="mb-5">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }} class="w-4 h-4 accent-blue-600">
                    <span class="text-sm text-gray-600">Aktifkan halaman agar dapat diakses oleh pengguna</span>
                </label>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition">Simpan Perubahan</button>
        </div>
    </div>
</form>
@endsection
