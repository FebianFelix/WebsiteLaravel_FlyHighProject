@extends('layouts.app')
@section('title','Content management')
@section('content')
<section class="page-hero"><div class="container"><a class="back-link" href="{{ route('admin.portal') }}">← Control center</a><span class="eyebrow">CONTENT MANAGEMENT</span><h1>Halaman website</h1><p>Konten CMS menggunakan teks yang ditampilkan aman; HTML dari editor tidak dieksekusi.</p></div></section>
<section class="section container"><div class="section-heading"><h2>Halaman kustom</h2><a class="btn btn-primary" href="{{ route('admin.pages.create') }}">+ Buat halaman</a></div>
<div class="card table-wrap"><table><thead><tr><th>Judul</th><th>Slug</th><th>Publik</th><th>Aksi</th></tr></thead><tbody>
@forelse($pages as $page)<tr><td>{{ $page->title }}</td><td>/pages/{{ $page->slug }}</td><td>{{ $page->is_published ? 'Ya' : 'Draft' }}</td><td><a class="text-link" href="{{ route('admin.pages.edit',$page) }}">Edit</a> · <a class="text-link" href="{{ route('pages.show',$page->slug) }}">Preview publik</a>
<form method="POST" action="{{ route('admin.pages.destroy',$page) }}" onsubmit="return confirm('Hapus halaman?')">@csrf @method('DELETE')<button class="link-danger" type="submit">Hapus</button></form></td></tr>
@empty<tr><td colspan="4">Belum ada halaman.</td></tr>@endforelse
</tbody></table></div>{{ $pages->links() }}</section>
@endsection
