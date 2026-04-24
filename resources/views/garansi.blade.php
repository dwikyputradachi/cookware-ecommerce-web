@extends('layouts.app')
@section('title', 'Garansi - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => 'garansi'])
    <div class="flex-1 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Kebijakan Garansi</h1>
        <div class="space-y-6">
            <div class="p-6 bg-orange-50 rounded-2xl border border-orange-100">
                <h3 class="font-bold text-gray-900 mb-2">Syarat & Ketentuan</h3>
                <ul class="list-disc ml-5 text-gray-600 space-y-2">
                    <li>Garansi berlaku 12 bulan untuk cacat produksi.</li>
                    <li>Wajib menyertakan video unboxing saat klaim.</li>
                    <li>Kerusakan akibat kelalaian pemakaian tidak ditanggung.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection