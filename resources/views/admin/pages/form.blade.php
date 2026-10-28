@extends('layouts.app')
@section('title', $page->exists ? 'Edit halaman' : 'Halaman baru')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.pages.index') }}">← CMS</a><span class="eyebrow">CONTENT EDITOR</span><h1>{{ $page->exists ? 'Edit halaman' : 'Halaman baru' }}</h1></div></section>
<section class="section container"><div class="card form-card"><form class="stack-form" method="POST" action="{{ $page->exists ? route('admin.pages.update',$page) : route('admin.pages.store') }}">@csrf @if($page->exists)@method('PUT')@endif
<label>Slug URL<input name="slug" required maxlength="90" value="{{ old('slug',$page->slug) }}" placeholder="kebijakan-perjalanan"></label>
<label>Judul<input name="title" required maxlength="160" value="{{ old('title',$page->title) }}"></label>
<label>Isi konten (plain text)<textarea name="body" required rows="13" maxlength="20000">{{ old('body',$page->body) }}</textarea></label>
<label class="check-label"><input type="checkbox" name="is_published" value="1" {{ old('is_published',$page->is_published) ? 'checked' : '' }}>Tampilkan ke publik</label>
<button class="btn btn-primary" type="submit">Simpan halaman</button></form></div></section>
@endsection
