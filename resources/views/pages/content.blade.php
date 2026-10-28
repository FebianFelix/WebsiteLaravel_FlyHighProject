@extends('layouts.app')
@section('title', $page->title)
@section('content')
<section class="page-hero"><div class="container"><span class="eyebrow">FLYHIGH · INFORMATION</span><h1>{{ $page->title }}</h1><p>Diperbarui {{ $page->updated_at->format('d M Y') }}</p></div></section>
<section class="section container"><article class="card content-article"><p>{!! nl2br(e($page->body)) !!}</p></article></section>
@endsection
