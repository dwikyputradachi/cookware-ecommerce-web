@extends('admin.layout') {{-- ganti sesuai layout admin kamu --}}

@section('page-title', 'Kelola Banner')

@section('content')
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Kelola Banner</h1>
        <p class="text-sm text-gray-500 mt-0.5">
            <span class="font-semibold {{ $activeCount >= 5 ? 'text-red-500' : 'text-green-600' }}">{{ $activeCount }}/5</span>
            banner aktif
        </p>
    </div>
    @if($activeCount < 5)
    <a href="{{ route('admin.banners.create') }}"
       class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition">
        <i class="fas fa-plus"></i> Tambah Banner
    </a>
    @else
    <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-xl text-sm font-semibold cursor-not-allowed">
        Batas 5 Banner Tercapai
    </span>
    @endif
</div>

{{-- Info limit --}}
<div class="mb-4 p-4 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-700 flex items-center gap-2">
    <i class="fas fa-circle-info"></i>
    Maksimal 5 banner aktif ditampilkan di homepage. Atur urutan dengan mengubah nilai <strong>Sort Order</strong>.
</div>

<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
    @if($banners->isEmpty())
    <div class="py-16 text-center text-gray-400">
        <i class="fas fa-image text-4xl mb-3 block opacity-30"></i>
        Belum ada banner. <a href="{{ route('admin.banners.create') }}" class="text-blue-500 underline">Tambah sekarang</a>
    </div>
    @else
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Banner</th>
                <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul / Link</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Order</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($banners as $banner)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-4 py-3">
                    <img src="{{ $banner->image }}" alt="{{ $banner->title }}"
                         class="w-32 h-16 object-cover rounded-lg border border-gray-100">
                </td>
                <td class="px-4 py-3">
                    <p class="font-semibold text-gray-800">{{ $banner->title ?? '—' }}</p>
                    @if($banner->link)
                    <a href="{{ $banner->link }}" target="_blank" class="text-xs text-blue-500 hover:underline truncate block max-w-[200px]">
                        {{ $banner->link }}
                    </a>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 bg-gray-100 rounded-lg font-bold text-gray-600">{{ $banner->sort_order }}</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="px-3 py-1 rounded-full text-xs font-bold transition
                                {{ $banner->is_active
                                    ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                    : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                            {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.banners.edit', $banner) }}"
                           class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-pen"></i> Edit
                        </a>
                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                              onsubmit="return confirm('Hapus banner ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg text-xs font-semibold transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $banners->links() }}
    </div>
    @endif
</div>
@endsection