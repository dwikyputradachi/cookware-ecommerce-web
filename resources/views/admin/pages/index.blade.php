@extends('admin.layout')

@section('page-title', 'Kelola Halaman')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Kelola Halaman Konten</h1>
        <p class="text-sm text-gray-500 mt-0.5">Ubah teks halaman informasi seperti Tentang Kami, Garansi, Return, dan panduan tanpa perlu deploy ulang.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Terakhir Diperbarui</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($pages as $page)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-4 py-3 text-gray-700 font-semibold">{{ $page->slug }}</td>
                <td class="px-4 py-3">{{ $page->title }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $page->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        <span class="h-2.5 w-2.5 rounded-full {{ $page->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $page->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-gray-500 text-sm">{{ $page->updated_at->format('d M Y H:i') }}</td>
                <td class="px-4 py-3 text-center">
                    <a href="{{ route('admin.pages.edit', $page) }}"
                       class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
