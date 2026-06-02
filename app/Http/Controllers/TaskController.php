<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskReorderRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Inertia\Inertia;
use App\Models\Task;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Inertia\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('TaskList', [
            'tasks' => TaskResource::collection(Task::orderBy('priority')->get()),
            'projects' => Project::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request): TaskResource
    {
        $validated = $request->validated();

        $task = Task::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'project_id' => $validated['projectId'] ?? null,
        ]);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task): TaskResource
    {
        $validated = $request->validated();

        $task->update([
            'name' => $validated['name'] ?? $task->name,
            'description' => $validated['description'] ?? $task->description,
            'priority' => $validated['priority'] ?? $task->priority,
            'project_id' => $validated['projectId'] ?? $task->project_id,
        ]);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->noContent();
    }

    /**
     * Reorder the list of tasks
     */
    public function reorder(TaskReorderRequest $request): AnonymousResourceCollection
    {
        $validatedTasks = collect($request->validated('tasks'))->keyBy('id');

        $tasks = Task::whereIn('id', $validatedTasks->keys())->get();

        DB::transaction(function ()  use (
            $validatedTasks,
            $tasks,
        ) {
            $tasks->each(function (Task $task) use ($validatedTasks) {
                $task->update([
                    'priority' => $validatedTasks[$task->id]['priority'],
                ]);
            });
        });

        return TaskResource::collection($tasks->sortBy('priority')->values());
    }
}
