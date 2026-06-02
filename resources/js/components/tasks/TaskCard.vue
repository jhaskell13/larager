<script setup lang="ts">
import type { Task, Project } from '../../types/task'

const props = defineProps<{
  task: Task
  projects: Project[]
}>()

const emit = defineEmits<{
  edit: []
  delete: []
}>()

const getProjectName = (id?: number | null) => {
  if (!id) {
    return null
  }

  return (
    props.projects.find(
      project => project.id === id,
    )?.name ?? null
  )
}
</script>

<template>
  <div
    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md"
  >
    <div class="flex items-start justify-between gap-4">
      <div class="min-w-0 flex-1">
        <div class="flex items-center gap-3">
          <span
            class="cursor-move select-none text-lg text-slate-400"
          >
            ☰
          </span>

          <h3
            class="truncate text-lg font-semibold text-slate-900"
          >
            {{ task.name }}
          </h3>
        </div>

        <p
          v-if="task.description"
          class="mt-2 text-sm leading-relaxed text-slate-600"
        >
          {{ task.description }}
        </p>

        <div class="mt-4 flex flex-wrap gap-2">
          <span
            class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700"
          >
            Priority {{ task.priority }}
          </span>

          <span
            v-if="getProjectName(task.projectId)"
            class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700"
          >
            {{ getProjectName(task.projectId) }}
          </span>
        </div>
      </div>

      <div
        class="flex shrink-0 gap-2 opacity-0 transition-opacity duration-200 group-hover:opacity-100"
      >
        <button
          class="rounded-lg bg-slate-100 border border-indigo-600 text-indigo-600 px-3 py-2 text-sm font-medium hover:bg-slate-200"
          @click="emit('edit')"
        >
          Edit
        </button>

        <button
          class="rounded-lg bg-red-100 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-200"
          @click="emit('delete')"
        >
          Delete
        </button>
      </div>
    </div>
  </div>
</template>
