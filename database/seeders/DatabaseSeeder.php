<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $p1 = Project::create(['name' => 'Personal Website']);
        $p2 = Project::create(['name' => 'Company App']);
        $p3 = Project::create(['name' => 'E-commerce Store']);

        Task::create([
            'project_id' => $p1->id,
            'name' => 'Integrate Blog Submissions',
            'description' => 'Allow authorized users to post blogs for others to read.',
            'priority' => 1,
        ]);

        Task::create([
            'project_id' => $p1->id,
            'name' => 'Setup DNS',
            'description' => 'Purchase domain, setup DNS for public deployments.',
            'priority' => 2,
        ]);

        Task::create([
            'project_id' => $p3->id,
            'name' => 'Admin Dashboard',
            'description' => 'Build dashboard for internal team to monitor app health and marketing trends based on user activity.',
            'priority' => 3,
        ]);
    }
}
