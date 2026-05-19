@extends('admin.layout')

@section('page-title', 'Pengaturan Footer')

@section('content')
<div class="mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-900">Pengaturan Footer & Kontak</h1>
        <p class="text-sm text-gray-500 mt-1">Ubah informasi kontak, jam operasional, dan tautan media sosial di halaman footer.</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf

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
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Situs</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Operasional</label>
                    <input type="text" name="operational_hours" value="{{ old('operational_hours', $settings['operational_hours'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $settings['email'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link Facebook</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link Instagram</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link WhatsApp</label>
                    <input type="url" name="whatsapp_url" value="{{ old('whatsapp_url', $settings['whatsapp_url'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link TikTok</label>
                    <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition">Simpan Pengaturan</button>
            </div>
        </div>
    </div>
</form>
@endsection
