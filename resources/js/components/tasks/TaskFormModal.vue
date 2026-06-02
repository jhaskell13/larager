<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import type { Task, Project } from '../../types/task'

type TaskFormData = Omit<Task, 'id'>

const props = defineProps<{
  open: boolean
  task?: Task | null
  projects: Project[]
}>()

const emit = defineEmits<{
  close: []
  save: [task: TaskFormData]
}>()

const form = ref<TaskFormData>({
  name: '',
  description: '',
  priority: 1,
  projectId: null,
})

const isEditing = computed(() => !!props.task)

const resetForm = () => {
  form.value = {
    name: '',
    description: '',
    priority: 1,
    projectId: null,
  }
}

watch(
  () => props.task,
  task => {
    if (!task) {
      resetForm()
      return
    }

    form.value = {
      name: task.name,
      description:
        task.description ?? '',
      priority: task.priority,
      projectId:
        task.projectId ?? null,
    }
  },
  {
    immediate: true,
  },
)

const submit = () => {
  if (!form.value.name.trim()) {
    return
  }

  emit('save', {
    ...form.value,
    name: form.value.name.trim(),
  })

  resetForm()
}

const close = () => {
  resetForm()
  emit('close')
}
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="open"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm"
        @click.self="close"
      >
        <div
          class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl"
        >
          <div class="mb-6">
            <h2
              class="text-2xl font-bold text-slate-900"
            >
              {{
                isEditing
                  ? 'Edit Task'
                  : 'Create Task'
              }}
            </h2>

            <p class="mt-1 text-sm text-slate-500">
              {{
                isEditing
                  ? 'Update your task details.'
                  : 'Add a new task to your list.'
              }}
            </p>
          </div>

          <form
            class="space-y-5 text-slate-700"
            @submit.prevent="submit"
          >
            <div>
              <label
                class="mb-2 block text-sm font-medium text-slate-700"
              >
                Task Name
              </label>

              <input
                v-model="form.name"
                type="text"
                placeholder="Enter task name"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                required
              />
            </div>

            <div>
              <label
                class="mb-2 block text-sm font-medium text-slate-700"
              >
                Description
              </label>

              <textarea
                v-model="form.description"
                rows="4"
                placeholder="Optional description"
                class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
              />
            </div>

            <div
              class="grid grid-cols-1 gap-4 md:grid-cols-2"
            >
              <div>
                <label
                  class="mb-2 block text-sm font-medium text-slate-700"
                >
                  Priority
                </label>

                <input
                  v-model.number="form.priority"
                  type="number"
                  min="1"
                  class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                />
              </div>

              <div>
                <label
                  class="mb-2 block text-sm font-medium text-slate-700"
                >
                  Project
                </label>

                <select
                  v-model="form.projectId"
                  class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                >
                  <option :value="null">
                    No Project
                  </option>

                  <option
                    v-for="project in projects"
                    :key="project.id"
                    :value="project.id"
                  >
                    {{ project.name }}
                  </option>
                </select>
              </div>
            </div>

            <div
              class="flex justify-end gap-3 border-t border-slate-200 pt-5"
            >
              <button
                type="button"
                class="rounded-xl bg-slate-200 px-5 py-2.5 font-medium text-slate-700 hover:bg-slate-300"
                @click="close"
              >
                Cancel
              </button>

              <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-5 py-2.5 font-medium text-white hover:bg-indigo-700"
              >
                {{
                  isEditing
                    ? 'Save Changes'
                    : 'Create Task'
                }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
