@extends('admin.layout')
@section('page-title', 'Edit Banner')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-5">
        <a href="{{ route('admin.banners.index') }}" class="text-sm text-blue-500 hover:underline">← Kembali</a>
        <h1 class="text-xl font-bold text-gray-900 mt-1">Edit Banner</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @include('admin.banners._form', ['banner' => $banner, 'action' => route('admin.banners.update', $banner), 'method' => 'PUT'])
    </div>
</div>

@endsection