<?php

namespace App\Http\Controllers;

use App\Models\TaskTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskTemplateController extends Controller
{
    public function index(): View
    {
        $taskTemplates = TaskTemplate::query()
            ->orderBy('is_active', 'desc')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('task-templates.index', compact('taskTemplates'));
    }

    public function create(): View
    {
        return view('task-templates.create', ['taskTemplate' => new TaskTemplate()]);
    }

    public function store(Request $request): RedirectResponse
    {
        TaskTemplate::create($this->validated($request));

        return redirect()->route('task-templates.index')->with('success', 'Master tugas berhasil dibuat.');
    }

    public function edit(TaskTemplate $taskTemplate): View
    {
        return view('task-templates.edit', compact('taskTemplate'));
    }

    public function update(Request $request, TaskTemplate $taskTemplate): RedirectResponse
    {
        $taskTemplate->update($this->validated($request));

        return redirect()->route('task-templates.index')->with('success', 'Master tugas berhasil diperbarui.');
    }

    public function destroy(TaskTemplate $taskTemplate): RedirectResponse
    {
        $taskTemplate->update(['is_active' => ! $taskTemplate->is_active]);

        return back()->with('success', $taskTemplate->is_active ? 'Master tugas diaktifkan kembali.' : 'Master tugas dinonaktifkan.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'default_area' => ['nullable', 'string', 'max:255'],
            'default_shift' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]) + ['is_active' => false, 'sort_order' => 0];
    }
}
