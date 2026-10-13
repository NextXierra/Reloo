@extends('layouts.store')

@section('title', $page->title . ' - Reloo Marketplace')

@push('styles')
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
