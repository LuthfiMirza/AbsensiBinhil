<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use App\Models\Employee;
use App\Models\TaskTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DailyTaskController extends Controller
{
    public function index(Request $request): View
    {
        $dailyTasks = DailyTask::query()
            ->with(['employee', 'taskTemplate', 'completedBy'])
            ->when($request->filled('task_date'), fn ($query) => $query->whereDate('task_date', $request->task_date))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('area'), fn ($query) => $query->where('area', 'like', '%'.$request->area.'%'))
            ->when($request->filled('employee_id'), fn ($query) => $query->where('employee_id', $request->employee_id))
            ->latest('task_date')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('daily-tasks.index', [
            'dailyTasks' => $dailyTasks,
            'employees' => Employee::query()->orderBy('name')->get(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function create(): View
    {
        return view('daily-tasks.create', $this->formData(new DailyTask(['task_date' => today(), 'status' => DailyTask::STATUS_PENDING])));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $employee = Employee::findOrFail($data['employee_id']);
        $template = isset($data['task_template_id']) ? TaskTemplate::find($data['task_template_id']) : null;

        $data['assigned_by'] = $request->user()->id;
        $data['title'] = ($data['title'] ?? null) ?: $template?->name;
        $data['description'] = $data['description'] ?? $template?->description;
        $data['area'] = $data['area'] ?? $template?->default_area ?? $employee->area;
        $data['shift'] = $data['shift'] ?? $template?->default_shift ?? $employee->shift;
        $data['completed_at'] = $data['status'] === DailyTask::STATUS_COMPLETED ? now() : null;
        $data['completed_by'] = $data['status'] === DailyTask::STATUS_COMPLETED ? $request->user()->id : null;

        DailyTask::create($data);

        return redirect()->route('daily-tasks.index')->with('success', 'Tugas harian berhasil ditugaskan.');
    }

    public function edit(DailyTask $dailyTask): View
    {
        return view('daily-tasks.edit', $this->formData($dailyTask));
    }

    public function update(Request $request, DailyTask $dailyTask): RedirectResponse
    {
        $data = $this->validated($request);
        $employee = Employee::findOrFail($data['employee_id']);
        $template = isset($data['task_template_id']) ? TaskTemplate::find($data['task_template_id']) : null;

        $data['title'] = ($data['title'] ?? null) ?: $template?->name;
        $data['description'] = $data['description'] ?? $template?->description;
        $data['area'] = $data['area'] ?? $template?->default_area ?? $employee->area;
        $data['shift'] = $data['shift'] ?? $template?->default_shift ?? $employee->shift;

        if ($data['status'] === DailyTask::STATUS_COMPLETED && ! $dailyTask->completed_at) {
            $data['completed_at'] = now();
            $data['completed_by'] = $request->user()->id;
        }

        if ($data['status'] !== DailyTask::STATUS_COMPLETED) {
            $data['completed_at'] = null;
            $data['completed_by'] = null;
        }

        $dailyTask->update($data);

        return redirect()->route('daily-tasks.index')->with('success', 'Tugas harian berhasil diperbarui.');
    }

    public function destroy(DailyTask $dailyTask): RedirectResponse
    {
        $dailyTask->delete();

        return back()->with('success', 'Tugas harian dihapus.');
    }

    public function markCompleted(Request $request, DailyTask $dailyTask): RedirectResponse
    {
        if (! $dailyTask->isCompleted()) {
            $dailyTask->update([
                'status' => DailyTask::STATUS_COMPLETED,
                'completed_at' => now(),
                'completed_by' => $request->user()->id,
            ]);
        }

        return back()->with('success', 'Tugas ditandai selesai.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'task_date' => ['required', 'date'],
            'employee_id' => ['required', 'exists:employees,id'],
            'task_template_id' => ['nullable', 'exists:task_templates,id'],
            'title' => ['nullable', 'string', 'max:255', 'required_without:task_template_id'],
            'description' => ['nullable', 'string'],
            'area' => ['nullable', 'string', 'max:255'],
            'shift' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(array_keys($this->statuses()))],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function formData(DailyTask $dailyTask): array
    {
        return [
            'dailyTask' => $dailyTask,
            'employees' => Employee::query()->where('is_active', true)->orderBy('name')->get(),
            'taskTemplates' => TaskTemplate::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(),
            'statuses' => $this->statuses(),
        ];
    }

    private function statuses(): array
    {
        return [
            DailyTask::STATUS_PENDING => 'Belum dikerjakan',
            DailyTask::STATUS_IN_PROGRESS => 'Sedang dikerjakan',
            DailyTask::STATUS_COMPLETED => 'Selesai',
        ];
    }
}
