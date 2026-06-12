@extends('layouts.app')
@section('title', $page->title . ' - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => $page->slug])
    <div class="flex-1 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">{{ $page->title }}</h1>
        <div class="space-y-6 text-gray-600 leading-relaxed">
            @php
                $content = $page->content;
                if (preg_match('/<[^>]+>/', $content)) {
                    echo $content;
                } else {
                    echo nl2br(e($content));
                }
            @endphp
        </div>
    </div>
</div>
@endsection
