@extends('admin.layout')

@section('title', 'Keamanan Akun')
@section('page-title', 'Keamanan Akun')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-900">Keamanan Akun Admin</h1>
    <p class="text-sm text-gray-500 mt-1">
        Ubah email login dan password admin menggunakan verifikasi OTP.
    </p>
</div>

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
            <p class="text-sm text-gray-500">Email login saat ini:</p>
            <p class="font-bold text-gray-900">{{ $admin->email }}</p>
        </div>

        <form action="{{ route('admin.security.send-otp') }}" method="POST" class="mb-6">
            @csrf
            <button type="submit"
                class="px-5 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-bold text-sm transition">
                Kirim OTP ke Email Utama
            </button>
            <p class="text-xs text-gray-400 mt-2">
                OTP akan dikirim ke email utama Murazon dan berlaku 5 menit.
            </p>
        </form>

        <form action="{{ route('admin.security.update-account') }}" method="POST">
            @csrf

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Admin Baru</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode OTP</label>
                    <input type="text" name="otp_code" maxlength="6" placeholder="6 digit OTP"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diganti"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-400 transition">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection