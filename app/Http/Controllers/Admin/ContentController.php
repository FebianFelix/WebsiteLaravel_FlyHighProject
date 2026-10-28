<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditEvent;
use App\Models\ContentPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View { return view('admin.pages.index', ['pages' => ContentPage::orderBy('slug')->paginate(20)]); }
    public function create(): View { return view('admin.pages.form', ['page' => new ContentPage]); }
    public function edit(ContentPage $page): View { return view('admin.pages.form', compact('page')); }
    public function store(Request $request): RedirectResponse
    {
        $page = ContentPage::create($this->validated($request));
        $this->audit($request, 'page.created', $page);
        return redirect()->route('admin.pages.index')->with('success', 'Halaman berhasil dibuat.');
    }
    public function update(Request $request, ContentPage $page): RedirectResponse
    {
        $page->update($this->validated($request, $page));
        $this->audit($request, 'page.updated', $page);
        return redirect()->route('admin.pages.index')->with('success', 'Halaman diperbarui.');
    }
    public function destroy(Request $request, ContentPage $page): RedirectResponse
    {
        $this->audit($request, 'page.deleted', $page);
        $page->delete();
        return back()->with('success', 'Halaman dihapus.');
    }
    private function validated(Request $request, ?ContentPage $page = null): array
    {
        $data = $request->validate([
            'slug' => ['required', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'max:90', Rule::unique('content_pages', 'slug')->ignore($page?->id)],
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:20000'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $data['is_published'] = $request->boolean('is_published');
        return $data;
    }
    private function audit(Request $request, string $action, ContentPage $page): void
    {
        AuditEvent::create(['actor_user_id' => $request->user()->id, 'action' => $action, 'subject' => $page->slug]);
    }
}
