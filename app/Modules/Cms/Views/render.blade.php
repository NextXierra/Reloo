@extends('layouts.store')

@section('title', $page->title . ' · Reloo')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cms/canvas.css') }}">
@if(!empty($page->css_content))
<style>
    {!! $page->css_content !!}
</style>
@endif
@endpush

@section('content')
<div class="cms-rendered-content">
    {!! $page->html_content !!}
</div>
@endsection
