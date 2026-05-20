@extends('admin.layout')

@section('page-title', 'Kelola Halaman')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Kelola Halaman Konten</h1>
        <p class="text-sm text-gray-500 mt-0.5">Ubah teks halaman informasi seperti Tentang Kami, Garansi, Return, dan panduan tanpa perlu deploy ulang.</p>
    </div>
</div>

<div class="space-y-4 sm:hidden">
    @foreach($pages as $page)
    <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0 flex-1">
                <p class="font-semibold text-gray-900 truncate">{{ $page->title }}</p>
                <p class="mt-1 text-xs text-gray-500 break-all">{{ $page->slug }}</p>
                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    <span class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-2.5 py-1 text-gray-600">
                        <span class="h-2.5 w-2.5 rounded-full {{ $page->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $page->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-gray-700 border border-gray-200">
                        {{ $page->updated_at->format('d M Y H:i') }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">
                <i class="fas fa-pen"></i> Edit
            </a>
        </div>
    </div>
    @endforeach
</div>
<div class="hidden sm:block bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
    <table class="min-w-full text-sm whitespace-nowrap">
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
                    <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
