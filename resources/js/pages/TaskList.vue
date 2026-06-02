<script setup lang="ts">
import { computed, ref } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'
import axios from 'axios'

import TaskCard from '../components/tasks/TaskCard.vue'
import TaskFormModal from '../components/tasks/TaskFormModal.vue'

import type { Task, Project } from '../types/task'

const props = defineProps<{
    tasks: Task[],
    projects: Project[],
}>()

const tasks = ref(props.tasks)
const selectedProject = ref('all')
const showModal = ref(false)
const selectedTask = ref<Task | null>(null)

const filteredTasks = computed(() => {
    if (selectedProject.value === 'all') {
        return tasks.value
    }

    if (selectedProject.value === 'none') {
        return tasks.value.filter(task => task.projectId == null)
    }

    return tasks.value.filter(task => task.projectId === Number(selectedProject.value)
    )
});

const saveTask = async (
  form: Omit<Task, 'id'>,
) => {
  try {
    if (selectedTask.value) {
      const res = await axios.put(
        `/tasks/${selectedTask.value.id}`,
        form,
      )

      const index = tasks.value.findIndex(
        t => t.id === selectedTask.value!.id,
      )

      if (index !== -1) {
        tasks.value[index] = res.data
      }
    } else {
      const res = await axios.post(
        '/tasks',
        form,
      )

      tasks.value.push(res.data)
    }

    reorderTasks();
    closeModal()
  } catch (err) {
    console.error('Save failed', err)
  }
}

const deleteTask = async (id: number) => {
  const confirmed = window.confirm(
    'Delete this task?',
  )

  if (!confirmed) return

  try {
    await axios.delete(`/tasks/${id}`)

    tasks.value = tasks.value.filter(
      t => t.id !== id,
    )
    reorderTasks();
  } catch (err) {
    console.error('Delete failed', err)
  }
}

const reorderTasks = async () => {
  try {
    await axios.post('/tasks/reorder', {
      tasks: tasks.value.map(
        (t, index) => ({
          id: t.id,
          priority: index + 1,
        }),
      ),
    }).then(res => tasks.value = res.data);
  } catch (err) {
    console.error('Reorder failed', err)
  }
}

const openCreate = () => {
  selectedTask.value = null
  showModal.value = true
}

const openEdit = (task: Task) => {
  selectedTask.value = task
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedTask.value = null
}
</script>

<template>
  <main class="min-h-screen bg-slate-100 px-6 py-10">
    <div class="mx-auto max-w-2xl">

      <div class="mb-4 flex items-center justify-between">
        <div>
          <h1 class="text-4xl font-bold text-slate-900">
            Larager
          </h1>
          <p class="text-slate-500">
            Task management, powered by Laravel.
          </p>
        </div>
        <button
            class="rounded-xl bg-indigo-600 px-5 py-3 text-white hover:bg-indigo-700"
            @click="openCreate"
        >
            + New Task
        </button>
      </div>

      <div class="text-slate-900 flex justify-end mb-4">
        <span class="mr-2 font-bold">
            Filter by Project
        </span>
        <select v-model="selectedProject">
            <option selected value="all">All</option>
            <option value="none">No Project</option>
            <option
                v-for="project in props.projects"
                :key="project.id"
                :value="project.id"
            >
                {{ project.name }}
            </option>
        </select>
      </div>

      <div
        v-if="filteredTasks.length === 0"
        class="rounded-2xl border border-dashed bg-white p-10 text-center"
      >
        No tasks yet.
      </div>

      <VueDraggable
        v-else-if="selectedProject === 'all'"
        v-model="tasks"
        item-key="id"
        handle=".cursor-move"
        class="space-y-4"
        @end="reorderTasks"
      >
        <TaskCard
            v-for="task in tasks"
            :key="task.id"
            :task="task"
            :projects="projects"
            @edit="openEdit(task)"
            @delete="deleteTask(task.id)"
        />
      </VueDraggable>

      <div
        v-else
        class="space-y-4"
        >
            <TaskCard
                v-for="task in filteredTasks"
                :key="task.id"
                :task="task"
                :projects="projects"
                @edit="openEdit(task)"
                @delete="deleteTask(task.id)"
            />
        </div>
    </div>

    <TaskFormModal
      :open="showModal"
      :task="selectedTask"
      :projects="projects"
      @close="closeModal"
      @save="saveTask"
    />
  </main>
</template>
