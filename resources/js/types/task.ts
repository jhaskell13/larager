export interface Project {
    id: number
    name: string
}

export interface Task {
    id: number
    projectId?: number | null
    name: string
    description?: string
    priority: number
}
