@extends('admin.layout')

@section('title', 'Keamanan Akun')
@section('page-title', 'Keamanan Akun')

@section('content')
<style>
    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,.05);
        margin-bottom: 18px;
    }

    .title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 4px;
    }

    .desc {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 18px;
    }

    .alert {
        padding: 12px 14px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .success { background:#ecfdf5; color:#15803d; border:1px solid #bbf7d0; }
    .error { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }

    .field { margin-bottom: 14px; }

    .field label {
        display:block;
        font-size:13px;
        font-weight:700;
        color:#374151;
        margin-bottom:6px;
    }

    .field input {
        width:100%;
        border:1px solid #dbe3ef;
        border-radius:12px;
        padding:12px 14px;
        font-size:14px;
        outline:none;
    }

    .field input:focus {
        border-color:#2563eb;
    }

    .grid-2 {
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }

    .btn {
        border:0;
        border-radius:12px;
        padding:12px 18px;
        font-size:13px;
        font-weight:800;
        cursor:pointer;
        color:white;
    }

    .btn-blue { background:#2563eb; width:100%; }
    .btn-orange { background:#f97316; }
    .btn-red { background:#dc2626; }

    .qr-wrap {
        display:flex;
        gap:20px;
        align-items:flex-start;
        flex-wrap:wrap;
    }

    .qr-box {
        background:#fff;
        border:1px solid #fed7aa;
        border-radius:16px;
        padding:14px;
    }

    .secret {
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:12px;
        padding:12px;
        font-size:13px;
        font-weight:800;
        word-break:break-all;
    }

    .badge {
        display:inline-block;
        padding:6px 10px;
        border-radius:999px;
        font-size:12px;
        font-weight:800;
        margin-bottom:16px;
    }

    .badge-on { background:#dcfce7; color:#15803d; }
    .badge-off { background:#ffedd5; color:#c2410c; }

    @media(max-width:700px) {
        .grid-2 { grid-template-columns:1fr; }
    }
</style>

<div class="card">
    <h1 class="title">Keamanan Akun Admin</h1>
    <p class="desc">Ubah email dan password admin menggunakan kode dari Authenticator.</p>

    <p style="font-size:13px;color:#6b7280;margin-bottom:4px;">Email saat ini:</p>
    <p style="font-weight:800;color:#111827;">{{ $admin->email }}</p>
</div>

@if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert error">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert error">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(!$authenticatorEnabled)
    <div class="card">
        <span class="badge badge-off">Authenticator Belum Aktif</span>

        <h2 class="title">Setup Authenticator</h2>
        <p class="desc">Scan QR menggunakan Google Authenticator atau Microsoft Authenticator.</p>

        <div class="qr-wrap">
            <div class="qr-box">
                {!! $qrSvg !!}
            </div>

            <div style="flex:1;min-width:240px;">
                <p style="font-size:12px;font-weight:700;color:#6b7280;margin-bottom:6px;">Secret Key Manual</p>
                <div class="secret">{{ $setupSecret }}</div>

                <form action="{{ route('admin.security.setup-authenticator') }}" method="POST" style="margin-top:16px;">
                    @csrf

                    <div class="field">
                        <label>Kode Authenticator</label>
                        <input type="text" name="authenticator_code" maxlength="6" inputmode="numeric" placeholder="6 digit kode" required>
                    </div>

                    <button type="submit" class="btn btn-orange">
                        Aktifkan Authenticator
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <span class="badge badge-on">Authenticator Aktif</span>

        <h2 class="title">Ubah Email / Password Admin</h2>
        <p class="desc">Masukkan kode Authenticator untuk menyimpan perubahan.</p>

        <form action="{{ route('admin.security.update-account') }}" method="POST">
            @csrf

            <div class="grid-2">
                <div class="field">
                    <label>Email Admin</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                </div>

                <div class="field">
                    <label>Kode Authenticator</label>
                    <input type="text" name="authenticator_code" maxlength="6" inputmode="numeric" placeholder="6 digit kode" required>
                </div>

                <div class="field">
                    <label>Password Baru</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                </div>

                <div class="field">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
                </div>
            </div>

            <button type="submit" class="btn btn-blue">
                Simpan Perubahan
            </button>
        </form>
    </div>

    <div class="card">
        <h2 class="title">Reset Authenticator</h2>
        <p class="desc">Gunakan jika ingin mengganti HP/aplikasi Authenticator.</p>

        <form action="{{ route('admin.security.reset-authenticator') }}" method="POST"
              onsubmit="return confirm('Reset Authenticator? Admin harus scan QR ulang.');">
            @csrf

            <div class="field">
                <label>Kode Authenticator Saat Ini</label>
                <input type="text" name="authenticator_code" maxlength="6" inputmode="numeric" placeholder="6 digit kode" required>
            </div>

            <button type="submit" class="btn btn-red">
                Reset Authenticator
            </button>
        </form>
    </div>
@endif

@endsection